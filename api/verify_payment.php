<?php
/** API: Verify Razorpay Payment + Credit Coins */
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/auth.php';
Security::startSession(); Security::setHeaders(); Security::verifyCsrf();
Auth::requireLogin();

$body       = json_decode(file_get_contents('php://input'), true) ?? [];
$orderId    = Security::clean($body['razorpay_order_id'] ?? '');
$paymentId  = Security::clean($body['razorpay_payment_id'] ?? '');
$signature  = Security::clean($body['razorpay_signature'] ?? '');
$pkgId      = Security::int($body['package_id'] ?? 0);

if (!$orderId || !$paymentId || !$signature || !$pkgId)
    Security::jsonError('Invalid payment data.');

// ── Signature Verification ────────────────────────────────
$expected = hash_hmac('sha256', $orderId . '|' . $paymentId, RAZORPAY_KEY_SECRET);
if (!hash_equals($expected, $signature)) {
    Security::jsonError('Payment verification failed. Contact support.', 422);
}

// ── Prevent duplicate credit ──────────────────────────────
$dup = DB::row("SELECT id FROM coin_transactions WHERE reference_id=?", [$paymentId]);
if ($dup) Security::jsonError('Payment already processed.');

$pkg  = DB::row("SELECT * FROM coin_packages WHERE id=?", [$pkgId]);
if (!$pkg) Security::jsonError('Package not found.');

$userId = Auth::id();
DB::conn()->beginTransaction();
try {
    DB::query("UPDATE freelancer_profiles SET coin_balance=coin_balance+? WHERE user_id=?", [$pkg['coins'], $userId]);
    DB::query(
        "INSERT INTO coin_transactions (user_id, amount, type, description, reference_id) VALUES (?,?,?,?,?)",
        [$userId, $pkg['coins'], 'purchase', "Bought {$pkg['coins']} coins ({$pkg['name']}) — ₹{$pkg['price']}", $paymentId]
    );
    DB::conn()->commit();
} catch (Exception $e) {
    DB::conn()->rollBack();
    Security::jsonError('Failed to credit coins. Contact support with payment ID: ' . $paymentId);
}

$newBal = (int)(DB::row("SELECT coin_balance FROM freelancer_profiles WHERE user_id=?", [$userId])['coin_balance']);
Security::jsonOk([
    'message'     => "✅ {$pkg['coins']} coins added to your wallet!",
    'coins_added' => $pkg['coins'],
    'new_balance' => $newBal,
]);
