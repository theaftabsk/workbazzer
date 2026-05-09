<?php
/**
 * WorkBazar — Chat System
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Auth::requireLogin();

$user = Auth::user();
$proposalId = (int)($_GET['proposal_id'] ?? 0);

// Fetch Proposal details and verify access
$proposal = DB::row("SELECT p.*, j.title as job_title, j.client_id,
                            u_f.fullname as freelancer_name, u_f.phone as freelancer_phone, u_f.avatar as freelancer_avatar,
                            u_c.fullname as client_name, u_c.phone as client_phone, u_c.avatar as client_avatar
                     FROM proposals p
                     JOIN jobs j ON p.job_id = j.id
                     JOIN users u_f ON p.freelancer_id = u_f.id
                     JOIN users u_c ON j.client_id = u_c.id
                     WHERE p.id = ?", [$proposalId]);

if (!$proposal) {
    die("Invalid conversation.");
}

// Security Check: Only the client or the freelancer involved can access this chat
if ($user['id'] != $proposal['client_id'] && $user['id'] != $proposal['freelancer_id']) {
    die("Unauthorized access.");
}

// Only allow chat if proposal is accepted
if ($proposal['status'] !== 'accepted' && Auth::role() !== 'admin') {
    die("Chat is only available after a proposal is accepted.");
}

$otherPartyId = ($user['id'] == $proposal['client_id']) ? $proposal['freelancer_id'] : $proposal['client_id'];
$otherPartyName = ($user['id'] == $proposal['client_id']) ? $proposal['freelancer_name'] : $proposal['client_name'];
$otherPartyPhone = ($user['id'] == $proposal['client_id']) ? $proposal['freelancer_phone'] : $proposal['client_phone'];
$otherPartyAvatar = ($user['id'] == $proposal['client_id']) ? $proposal['freelancer_avatar'] : $proposal['client_avatar'];

$pageTitle = "Chat with " . $otherPartyName . " — WorkBazar";
include __DIR__ . '/../includes/layouts/header.php';
include __DIR__ . '/../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/chat.css'); ?>">

<main class="chat-layout">
  <!-- Sidebar -->
  <aside class="chat-sidebar">
    <div class="contact-box">
      <h4>Direct Contact Info</h4>
      <div class="contact-item">
        <i class="ri-phone-fill"></i> <?=$otherPartyPhone ?: 'Not provided'?>
      </div>
      <?php if($otherPartyPhone): ?>
        <a href="https://wa.me/<?=preg_replace('/\D/','',$otherPartyPhone)?>" target="_blank" class="whatsapp-btn">
          <i class="ri-whatsapp-line"></i> Message on WhatsApp
        </a>
      <?php endif; ?>
    </div>

    <div style="border-top:1px solid var(--border); padding-top:20px;">
      <h4 style="font-size:0.8rem; color:var(--muted); text-transform:uppercase; margin-bottom:12px;">Project Details</h4>
      <p style="font-weight:700; font-size:0.9rem;"><?=htmlspecialchars($proposal['job_title'])?></p>
      <div style="margin-top:10px; font-size:0.85rem; color:var(--muted);">
        Bid Amount: <strong>₹<?=number_format($proposal['bid_amount'])?></strong><br>
        Delivery: <strong><?=$proposal['delivery_days']?> Days</strong>
      </div>
    </div>
  </aside>

  <!-- Main Chat -->
  <div class="chat-main">
    <div class="chat-header">
      <div class="party-info">
        <div class="party-avatar">
          <?php if($otherPartyAvatar): ?>
            <img src="<?=$otherPartyAvatar?>" alt="">
          <?php else: ?>
            <i class="ri-user-3-line"></i>
          <?php endif; ?>
        </div>
        <div class="party-details">
          <h3><?=htmlspecialchars($otherPartyName)?></h3>
          <span>Online</span>
        </div>
      </div>
      <button class="btn-outline" style="padding:8px 16px; border-radius:8px; font-size:0.8rem;">
        <i class="ri-more-2-fill"></i>
      </button>
    </div>

    <div class="messages-area" id="msgArea">
      <!-- Messages will load here via JS -->
      <div style="text-align:center; padding:40px; color:var(--muted);">
        <i class="ri-loader-4-line ri-spin" style="font-size:2rem;"></i><br>
        Loading conversation...
      </div>
    </div>

    <div class="chat-input-area">
      <form class="chat-form" id="chatForm">
        <input type="text" class="chat-input" id="chatInput" placeholder="Type your message here..." autocomplete="off">
        <button type="submit" class="btn-send"><i class="ri-send-plane-2-fill"></i></button>
      </form>
    </div>
  </div>
</main>

<script>
const proposalId = <?=$proposalId?>;
const myId       = <?=$user['id']?>;
const msgArea    = document.getElementById('msgArea');
const chatForm   = document.getElementById('chatForm');
const chatInput  = document.getElementById('chatInput');

let lastMsgId = 0;

async function fetchMessages() {
    try {
        const res = await fetch(`/api/chat_messages.php?proposal_id=${proposalId}&last_id=${lastMsgId}`);
        const data = await res.json();
        if (data.success && data.messages.length > 0) {
            if (lastMsgId === 0) msgArea.innerHTML = ''; // Clear loader
            
            data.messages.forEach(m => {
                const div = document.createElement('div');
                div.className = `msg ${m.sender_id == myId ? 'msg-sent' : 'msg-received'}`;
                div.innerHTML = `
                    ${m.message}
                    <span class="msg-time">${m.time}</span>
                `;
                msgArea.appendChild(div);
                lastMsgId = m.id;
            });
            msgArea.scrollTo({ top: msgArea.scrollHeight, behavior: 'smooth' });
        } else if (lastMsgId === 0) {
            msgArea.innerHTML = '<div style="text-align:center; padding:40px; color:var(--muted);">No messages yet. Start the conversation!</div>';
        }
    } catch (e) { console.error("Chat Error:", e); }
}

chatForm.onsubmit = async (e) => {
    e.preventDefault();
    const msg = chatInput.value.trim();
    if (!msg) return;

    chatInput.value = '';
    try {
        const res = await fetch('/api/send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?= Security::csrfToken() ?>' },
            body: JSON.stringify({ proposal_id: proposalId, message: msg })
        });
        const result = await res.json();
        if (result.success) {
            fetchMessages();
        } else {
            alert(result.message);
        }
    } catch (e) { alert("Failed to send message."); }
};

// Polling for new messages
setInterval(fetchMessages, 3000);
fetchMessages();
</script>

<?php include __DIR__ . '/../includes/layouts/footer.php'; ?>
