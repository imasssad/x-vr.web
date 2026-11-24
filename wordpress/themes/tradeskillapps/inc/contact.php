<?php
/**
 * The enquiry form.
 *
 * Two ways in, sharing one validator: a REST route the page calls with
 * JavaScript so the form answers without reloading, and a plain POST for
 * anyone without JavaScript.
 *
 * Deliberately NO nonce on either path. This is a public, unauthenticated
 * form: any visitor could fetch a fresh nonce, so a nonce is not CSRF
 * protection here — but baked into page-cached HTML it outlives its
 * lifetime and then rejects every real visitor (the documented stale-nonce
 * failure under LiteSpeed/etc). The real defences are the honeypot, strict
 * validation, and rate limiting, below.
 *
 * Every valid enquiry is also stored as an "Enquiries" entry in wp-admin
 * BEFORE mail is attempted, so a broken mailbox can delay a message but
 * never lose one.
 */
defined('ABSPATH') || exit;

function tsa_contact_recipient() {
	$set = get_theme_mod('tsa_contact_to', '');
	return is_email($set) ? $set : get_option('admin_email');
}

/**
 * How many sends this hour, per sender and overall. Counting is cheap
 * transients, so a flood costs the attacker more than it costs the site.
 */
function tsa_contact_throttled() {
	$ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';

	$ip_key = 'tsa_contact_ip_' . md5('tsa' . $ip);
	$sent   = (int) get_transient($ip_key);
	if ($sent >= 5) return true;

	$all_key = 'tsa_contact_all';
	$total   = (int) get_transient($all_key);
	if ($total >= 100) return true;

	set_transient($ip_key, $sent + 1, HOUR_IN_SECONDS);
	set_transient($all_key, $total + 1, HOUR_IN_SECONDS);
	return false;
}

/** Keep a copy where the owner can see it, whatever the mailbox does. */
function tsa_store_enquiry($name, $email, $message) {
	$id = wp_insert_post(array(
		'post_type'    => 'tsa_msg',
		'post_status'  => 'private',
		'post_title'   => $name . ' <' . $email . '>',
		'post_content' => $message,
	), true);
	return is_wp_error($id) ? 0 : $id;
}

/**
 * Returns array('ok' => bool, 'message' => string[, 'status' => int]).
 * $data is raw, unslashed input and may contain anything a client sends,
 * including arrays where strings belong.
 */
function tsa_contact_handle($data) {
	$field = function ($key) use ($data) {
		return (isset($data[$key]) && is_string($data[$key])) ? $data[$key] : '';
	};

	// A field hidden from people but filled in by most bots.
	if ($field('company') !== '' || (isset($data['company']) && !is_string($data['company']))) {
		return array('ok' => true, 'message' => __('Thank you - your message has been sent.', 'tradeskillapps'));
	}

	// Bound the raw payload before any processing walks it.
	if (strlen($field('message')) > 50000 || strlen($field('name')) > 2000 || strlen($field('email')) > 1000) {
		return array('ok' => false, 'message' => __('That message is too long.', 'tradeskillapps'));
	}

	$name    = mb_substr(sanitize_text_field($field('name')), 0, 200);
	$email   = sanitize_email($field('email'));
	$message = sanitize_textarea_field($field('message'));

	if ($name === '' || $email === '' || $message === '') {
		return array('ok' => false, 'message' => __('Please complete every field.', 'tradeskillapps'));
	}
	if (strlen($email) > 254 || !is_email($email)) {
		return array('ok' => false, 'message' => __('That email address does not look right.', 'tradeskillapps'));
	}
	if (mb_strlen($message) > 5000) {
		return array('ok' => false, 'message' => __('That message is too long.', 'tradeskillapps'));
	}

	if (tsa_contact_throttled()) {
		return array(
			'ok'      => false,
			'status'  => 429,
			'message' => __('Please wait a little while before sending another message.', 'tradeskillapps'),
		);
	}

	tsa_store_enquiry($name, $email, $message);

	// Two separate concerns. Newlines would let someone append headers of their
	// own, so they go first. Then the display name is quoted, and the few
	// characters that mean something to an address parser are removed, so a
	// name like 'Dana", Bcc: <x@y>' cannot reshape the header it sits in.
	$safe_name  = trim(preg_replace('/[\r\n]+/', ' ', $name));
	$safe_name  = str_replace(array('"', '\\', '<', '>', ',', ';'), '', $safe_name);
	$safe_email = trim(preg_replace('/[^A-Za-z0-9._%+\-@]/', '', $email));

	$sent = wp_mail(
		tsa_contact_recipient(),
		sprintf(__('Website enquiry from %s', 'tradeskillapps'), $safe_name),
		sprintf("%s\n\n---\n%s\n%s\n", $message, $safe_name, $safe_email),
		array(
			'Content-Type: text/plain; charset=UTF-8',
			'Reply-To: "' . $safe_name . '" <' . $safe_email . '>',
		)
	);

	return $sent
		? array('ok' => true, 'message' => __('Thank you - your message has been sent.', 'tradeskillapps'))
		: array('ok' => true, 'message' => __('Thank you - your message has been received.', 'tradeskillapps'));
	// Mail failure still reads as received: the enquiry is stored in
	// wp-admin either way, and the visitor is not asked to retry what the
	// site has already kept.
}

/** The route the page's JavaScript calls. */
function tsa_register_contact_route() {
	register_rest_route('tsa/v1', '/contact', array(
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => function (WP_REST_Request $request) {
			$result = tsa_contact_handle($request->get_params());
			$status = isset($result['status']) ? (int) $result['status'] : ($result['ok'] ? 200 : 400);
			unset($result['status']);
			return new WP_REST_Response($result, $status);
		},
	));
}
add_action('rest_api_init', 'tsa_register_contact_route');

/** The no-JavaScript path. Same validator, same throttle, same storage. */
function tsa_contact_post() {
	$result = tsa_contact_handle(wp_unslash($_POST));
	$back = wp_get_referer() ? wp_get_referer() : home_url('/');
	wp_safe_redirect(add_query_arg('tsa_sent', $result['ok'] ? '1' : '0', $back) . '#contact');
	exit;
}
add_action('admin_post_nopriv_tsa_contact', 'tsa_contact_post');
add_action('admin_post_tsa_contact', 'tsa_contact_post');

/** The message shown after a no-JavaScript submission. */
function tsa_contact_flash() {
	if (!isset($_GET['tsa_sent'])) return '';
	return $_GET['tsa_sent'] === '1'
		? __('Thank you - your message has been sent.', 'tradeskillapps')
		: __('Sorry, the message could not be sent. Please check the fields and try again.', 'tradeskillapps');
}
