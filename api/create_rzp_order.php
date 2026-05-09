<?php
/**
 * API: Create Razorpay Order
 * POST /api/create_rzp_order.php
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Security::verifyCsrf();

if (!Auth::check()) {
    Security::jsonError('Please login to purchase coins.');
}

$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$amount = (float)($body['amount'] ?? 0);
$coins  = (int)($body['coins'] ?? 0);

if ($amount <= 0 || $coins <= 0) {
    Security::jsonError('Invalid amount or coin quantity.');
}

$keyId     = RAZORPAY_KEY_ID;
$keySecret = RAZORPAY_KEY_SECRET;

// ── Create Order via Razorpay API (using cURL) ────────────────
$amountPaise = $amount * 100;
$receiptId   = 'rcpt_' . Auth::id() . '_' . time();

$data = [
    'amount'   => $amountPaise,
    'currency' => 'INR',
    'receipt'  => $receiptId,
];

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$keyId:$keySecret");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$err      = curl_error($ch);
curl_close($ch);

if ($err) {
    Logger::error("Razorpay Order Creation Error: " . $err);
    Security::jsonError('Failed to connect to payment gateway.');
}

$order = json_decode($response, true);

if (isset($order['id'])) {
    // We can store the internal order if needed, but for now we just return the RZP ID
    Security::jsonOk([
        'rzp_order_id' => $order['id'],
        'rzp_amount'   => $amountPaise,
        'order_id'     => $receiptId, // Using receipt as internal ID for simplicity
        'currency'     => 'INR'
    ]);
} else {
    Logger::error("Razorpay API Error: " . $response);
    Security::jsonError('Failed to create order. Please try again.');
}
