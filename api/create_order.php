<?php
/** API: Create Razorpay Order */
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/auth.php';
Security::startSession(); Security::setHeaders(); Security::verifyCsrf();
Auth::requireLogin();

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$pkgId = Security::int($body['package_id'] ?? 0);

$pkg = DB::row("SELECT * FROM coin_packages WHERE id=?", [$pkgId]);
if (!$pkg) Security::jsonError('Package not found.');

$user = Auth::user();
$paise = (int)($pkg['price'] * 100); // Razorpay uses paise

$payload = json_encode([
    'amount'   => $paise,
    'currency' => 'INR',
    'receipt'  => 'wb_' . $user['id'] . '_' . time(),
    'notes'    => ['user_id' => $user['id'], 'package_id' => $pkg['id']],
]);

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_USERPWD        => RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_TIMEOUT        => 15,
]);
$res  = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$order = json_decode($res, true);
if ($code !== 200 || empty($order['id'])) {
    Security::jsonError('Payment order creation failed. Try again.');
}

Security::jsonOk([
    'order_id'   => $order['id'],
    'amount'     => $paise,
    'currency'   => 'INR',
    'key_id'     => RAZORPAY_KEY_ID,
    'package'    => ['id' => $pkg['id'], 'name' => $pkg['name'], 'coins' => $pkg['coins'], 'price' => $pkg['price']],
    'user_name'  => $user['name'],
    'user_phone' => $user['phone'],
]);
