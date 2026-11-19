<?php
/**
 * Contact handler for the STATIC build, where there is no Node server.
 * Mirrors app/api/contact/route.ts: same validation, same honeypot,
 * same JSON shape, so the React form needs no changes.
 *
 * Only used when the site is deployed as a static export. The Node build
 * uses /api/contact and ignores this file.
 */
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

function out(array $body, int $code = 200): never {
    http_response_code($code);
    echo json_encode($body);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    out(['ok' => false, 'error' => 'Method not allowed.'], 405);
}

$raw  = file_get_contents('php://input') ?: '';
$data = json_decode($raw, true);
if (!is_array($data)) {
    out(['ok' => false, 'error' => 'Invalid request.'], 400);
}

// Honeypot: people leave this empty, bots fill it in. Answer OK so they stop.
if (!empty($data['company'])) {
    out(['ok' => true]);
}

$name    = trim((string)($data['name'] ?? ''));
$email   = trim((string)($data['email'] ?? ''));
$message = trim((string)($data['message'] ?? ''));

if ($name === '' || $email === '' || $message === '') {
    out(['ok' => false, 'error' => 'Please complete every field.'], 400);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    out(['ok' => false, 'error' => 'That email address does not look right.'], 400);
}
if (mb_strlen($message) > 5000) {
    out(['ok' => false, 'error' => 'That message is too long.'], 400);
}

// Strip anything that could forge extra headers.
$safeName  = preg_replace('/[\r\n]+/', ' ', $name);
$safeEmail = preg_replace('/[\r\n]+/', '', $email);

$to      = 'hello@tradeskillapps.com';         // <- change to your mailbox
$from    = 'noreply@tradeskillapps.com';       // <- must be a real mailbox on the domain
$subject = 'Enquiry from ' . $safeName;
$body    = "From: {$safeName} <{$safeEmail}>\n\n{$message}";

$headers = implode("\r\n", [
    'From: X-VR Skilled Trades <' . $from . '>',
    'Reply-To: ' . $safeName . ' <' . $safeEmail . '>',
    'Content-Type: text/plain; charset=utf-8',
    'MIME-Version: 1.0',
]);

if (@mail($to, $subject, $body, $headers)) {
    out(['ok' => true]);
}
error_log('[contact.php] mail() failed');
out(['ok' => false, 'error' => 'Could not send just now. Please try again shortly.'], 502);
