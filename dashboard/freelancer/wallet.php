<?php
/**
 * WorkBazar — Freelancer Wallet & Coin Purchase
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('freelancer');

$user    = Auth::user();
$profile = Auth::freelancerProfile();

// Fetch Transaction History
$transactions = DB::all("SELECT * FROM coin_transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 20", [$user['id']]);

$pageTitle = "My Wallet — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/freelancer/wallet.css'); ?>">
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<main class="dashboard-wrap">
  <div class="page-header">
    <h1>My Wallet 💰</h1>
    <p>Manage your bidding coins and view transaction history.</p>
  </div>

  <div class="wallet-layout">
    
    <!-- Left: History -->
    <div class="admin-table-card">
      <h3 class="history-title">Transaction History</h3>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($transactions as $t): ?>
          <tr>
            <td><?=date('M d, Y', strtotime($t['created_at']))?></td>
            <td><span class="role-badge role-<?=$t['type']?>"><?=ucfirst($t['type'])?></span></td>
            <td><strong style="color:<?=$t['amount']>0?'var(--green)':'#ef4444'?>"><?=$t['amount']>0?'+':''?><?=$t['amount']?> Coins</strong></td>
            <td><?=$t['description']?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Right: Buy Coins -->
    <aside>
      <div class="balance-card">
        <span>Available Balance</span>
        <strong><i class="ri-coin-line"></i> <?=$profile['coin_balance']?></strong>
        <p>Coins are used to bid on projects. 1 bid usually costs 2 coins.</p>
      </div>

      <div class="coin-plans-card">
        <h3>Buy More Coins</h3>
        <div class="coin-plans">
          <div class="coin-plan" onclick="initPayment(100, 50)">
            <div class="coin-plan-header">
              <span>50 Coins</span>
              <span class="price">₹100</span>
            </div>
            <small>Best for beginners</small>
          </div>

          <div class="coin-plan" onclick="initPayment(199, 120)">
            <div class="coin-plan-header">
              <span>120 Coins</span>
              <span class="price">₹199</span>
            </div>
            <small>Most Popular (Save 15%)</small>
          </div>

          <div class="coin-plan" onclick="initPayment(499, 350)">
            <div class="coin-plan-header">
              <span>350 Coins</span>
              <span class="price">₹499</span>
            </div>
            <small>Enterprise Pack (Save 30%)</small>
          </div>
        </div>
      </div>
    </aside>
  </div>
</main>

<script>
async function initPayment(amount, coins) {
    try {
        const res = await fetch('/api/create_rzp_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?= Security::csrfToken() ?>' },
            body: JSON.stringify({ amount: amount, coins: coins })
        });
        const order = await res.json();
        
        if (!order.success) {
            alert(order.message);
            return;
        }

        const options = {
            "key": "<?= RAZORPAY_KEY_ID ?>",
            "amount": order.rzp_amount,
            "currency": "INR",
            "name": "WorkBazar",
            "description": `Purchase ${coins} Bidding Coins`,
            "order_id": order.rzp_order_id,
            "handler": function (response){
                verifyPayment(response, order.order_id, coins);
            },
            "prefill": {
                "name": "<?= $user['fullname'] ?>",
                "email": "<?= $user['email'] ?>"
            },
            "theme": { "color": "#1dbf73" }
        };
        const rzp = new Razorpay(options);
        rzp.open();
    } catch (e) { alert("Payment initialization failed."); }
}

async function verifyPayment(rzpResponse, internalOrderId, coins) {
    try {
        const res = await fetch('/api/verify_rzp_payment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?= Security::csrfToken() ?>' },
            body: JSON.stringify({
                order_id: internalOrderId,
                coins: coins,
                rzp_payment_id: rzpResponse.razorpay_payment_id,
                rzp_order_id: rzpResponse.razorpay_order_id,
                rzp_signature: rzpResponse.razorpay_signature
            })
        });
        const result = await res.json();
        if (result.success) {
            alert("Payment successful! Coins added to your wallet.");
            window.location.reload();
        } else {
            alert("Verification failed: " + result.message);
        }
    } catch (e) { alert("Payment verification failed."); }
}
</script>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
