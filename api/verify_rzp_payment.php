<?php
/**
 * API: Verify Razorpay Payment & Credit Coins
 * POST /api/verify_rzp_payment.php
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Security::verifyCsrf();

if (!Auth::check()) {
    Security::jsonError('Session expired. Please login again.', 401);
}

$body      = json_decode(file_get_contents('php://input'), true) ?? [];
$paymentId = $body['rzp_payment_id'] ?? '';
$orderId   = $body['rzp_order_id'] ?? '';
$signature = $body['rzp_signature'] ?? '';
$coins     = (int)($body['coins'] ?? 0);

if (!$paymentId || !$orderId || !$signature || !$coins) {
    Security::jsonError('Incomplete payment details.');
}

// ── 1. Verify Signature ─────────────────────────
$keySecret = RAZORPAY_KEY_SECRET;
$expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);

if ($expectedSignature !== $signature) {
    Logger::warn("Fraudulent payment attempt! User ID: " . Auth::id());
    Security::jsonError('Payment verification failed. Invalid signature.');
}

try {
    DB::beginTransaction();

    // Update freelancer coin balance
    DB::query(
        "UPDATE freelancer_profiles SET coin_balance = coin_balance + ? WHERE user_id = ?",
        [$coins, Auth::id()]
    );

    // Record Transaction
    DB::query(
        "INSERT INTO coin_transactions (user_id, amount, type, description, created_at)
         VALUES (?, ?, 'purchase', ?, NOW())",
        [Auth::id(), $coins, "Purchased $coins coins via Razorpay"]
    );

    DB::commit();

    Logger::info("User ID " . Auth::id() . " purchased $coins coins (Payment ID: $paymentId)");

    Security::jsonOk(['message' => 'Payment verified! Coins added to your wallet.']);

} catch (Exception $e) {
    DB::rollBack();
    Logger::error("Payment Success Processing Failed: " . $e->getMessage());
    Security::jsonError('Payment was successful but we failed to credit coins. Please contact support.', 500);
}
