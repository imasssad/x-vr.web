<?php
/**
 * The enquiry form.
 *
 * Two ways in, sharing one validator: a REST route the page calls with
 * JavaScript so the form answers without reloading, and a plain POST for
 * anyone without JavaScript. Mail goes out through wp_mail(), so whatever
 * the site is already configured to send with is what gets used.
 */
defined('ABSPATH') || exit;

function tsa_contact_recipient() {
	$set = get_theme_mod('tsa_contact_to', '');
	return is_email($set) ? $set : get_option('admin_email');
}

/**
 * Returns array('ok' => bool, 'message' => string).
 * $data is raw, unslashed input.
 */
function tsa_contact_handle($data) {
	// A field hidden from people but filled in by most bots.
	if (!empty($data['company'])) {
		return array('ok' => true, 'message' => __('Thank you - your message has been sent.', 'tradeskillapps'));
	}

	$name    = isset($data['name']) ? sanitize_text_field($data['name']) : '';
	$email   = isset($data['email']) ? sanitize_email($data['email']) : '';
	$message = isset($data['message']) ? sanitize_textarea_field($data['message']) : '';

	if ($name === '' || $email === '' || $message === '') {
		return array('ok' => false, 'message' => __('Please complete every field.', 'tradeskillapps'));
	}
	if (!is_email($email)) {
		return array('ok' => false, 'message' => __('That email address does not look right.', 'tradeskillapps'));
	}
	if (mb_strlen($message) > 5000) {
		return array('ok' => false, 'message' => __('That message is too long.', 'tradeskillapps'));
	}

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
		: array('ok' => false, 'message' => __('Sorry, the message could not be sent. Please try again later.', 'tradeskillapps'));
}

/** The route the page's JavaScript calls. */
function tsa_register_contact_route() {
	register_rest_route('tsa/v1', '/contact', array(
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => function (WP_REST_Request $request) {
			$result = tsa_contact_handle($request->get_params());
			return new WP_REST_Response($result, $result['ok'] ? 200 : 400);
		},
	));
}
add_action('rest_api_init', 'tsa_register_contact_route');

/** The no-JavaScript path. */
function tsa_contact_post() {
	$ok_nonce = isset($_POST['tsa_contact_nonce'])
		&& wp_verify_nonce(wp_unslash($_POST['tsa_contact_nonce']), 'tsa_contact');

	$result = $ok_nonce
		? tsa_contact_handle(wp_unslash($_POST))
		: array('ok' => false, 'message' => __('That form had expired. Please try again.', 'tradeskillapps'));

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
		: __('Sorry, the message could not be sent. Please try again.', 'tradeskillapps');
}
