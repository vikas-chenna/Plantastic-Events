<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$flash = '';

// Customer -> Admin inbox
$inbox = $conn->query(
    "SELECT m.cust_admin_msg_id, m.cust_id, m.cust_msg, m.admin_msg, c.user_name, c.email
     FROM tbl_cust_admin_msg m
     INNER JOIN tbl_customer c ON c.cust_id = m.cust_id
     WHERE m.cust_msg IS NOT NULL AND m.cust_msg <> ''
     ORDER BY m.cust_admin_msg_id DESC"
);

$sent = $conn->query(
    "SELECT m.cust_admin_msg_id, m.cust_id, m.cust_msg, m.admin_msg, c.user_name, c.email
     FROM tbl_cust_admin_msg m
     INNER JOIN tbl_customer c ON c.cust_id = m.cust_id
     WHERE m.admin_msg IS NOT NULL AND m.admin_msg <> ''
     ORDER BY m.cust_admin_msg_id DESC"
);

$customers = $conn->query("SELECT cust_id, user_name, email FROM tbl_customer WHERE status = '1' ORDER BY user_name ASC");

if (isset($_POST['reply_cust'])) {
    $reply = trim((string)($_POST['reply'] ?? ''));
    $cust_id = (int)($_POST['cust_id'] ?? 0);
    $msg_id = (int)($_POST['cust_admin_msg_id'] ?? 0);
    if ($reply !== '' && $cust_id > 0) {
        if ($msg_id > 0) {
            $stmt = $conn->prepare('UPDATE tbl_cust_admin_msg SET admin_msg = ? WHERE cust_admin_msg_id = ? AND cust_id = ?');
            $stmt->bind_param('sii', $reply, $msg_id, $cust_id);
            $stmt->execute(); $stmt->close();
        } else {
            $empty = '';
            $stmt = $conn->prepare('INSERT INTO tbl_cust_admin_msg (cust_id, cust_msg, admin_msg) VALUES (?, ?, ?)');
            $stmt->bind_param('iss', $cust_id, $empty, $reply);
            $stmt->execute(); $stmt->close();
        }
        $flash = 'Reply sent to customer.';
    }
}

if (isset($_POST['send'])) {
    $cust_id = (int)($_POST['cust_email'] ?? 0);
    $msg = trim((string)($_POST['msg'] ?? ''));
    if ($cust_id > 0 && $msg !== '') {
        $empty = '';
        $stmt = $conn->prepare('INSERT INTO tbl_cust_admin_msg (cust_id, cust_msg, admin_msg) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $cust_id, $empty, $msg);
        $stmt->execute(); $stmt->close();
        $flash = 'Message sent to customer.';
    } else {
        $flash = 'Select a customer and type a message.';
    }
}

$admin_page = 'ping_customers';
$admin_title = 'Message Customers';
$admin_subtitle = 'Send announcements and reply to customer messages';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>

<div class="grid-2">
  <div class="card">
    <div class="card-header"><h2>Compose</h2></div>
    <div class="card-body">
      <form method="post" class="form-grid">
        <label class="field">Customer
          <select name="cust_email" required>
            <option value="">Choose customer</option>
            <?php if ($customers) while ($c = $customers->fetch_assoc()): ?>
              <option value="<?= (int)$c['cust_id'] ?>"><?= e($c['user_name'] . ' — ' . $c['email']) ?></option>
            <?php endwhile; ?>
          </select>
        </label>
        <label class="field">Message
          <textarea name="msg" required placeholder="Write your message..."></textarea>
        </label>
        <button class="btn btn-primary" type="submit" name="send" value="1">Send message</button>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Inbox from customers</h2></div>
    <div class="card-body" style="display:grid;gap:12px;max-height:520px;overflow:auto">
      <?php if ($inbox && $inbox->num_rows > 0): while ($row = $inbox->fetch_assoc()): ?>
        <div class="msg-item" data-search-item="cmsg">
          <div class="who"><?= e($row['user_name']) ?> <span class="pill">Customer</span></div>
          <div><?= e($row['cust_msg']) ?></div>
          <?php if (!empty($row['admin_msg'])): ?><div style="color:var(--text-muted)"><strong>Your reply:</strong> <?= e($row['admin_msg']) ?></div><?php endif; ?>
          <form method="post" class="form-grid">
            <input type="hidden" name="cust_id" value="<?= (int)$row['cust_id'] ?>">
            <input type="hidden" name="cust_admin_msg_id" value="<?= (int)$row['cust_admin_msg_id'] ?>">
            <textarea name="reply" placeholder="Type reply..." required></textarea>
            <button class="btn btn-sm btn-success" name="reply_cust" value="1">Send reply</button>
          </form>
        </div>
      <?php endwhile; else: ?>
        <div class="empty"><strong>No customer messages</strong>Replies and new chats appear here.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2>Recently sent by admin</h2></div>
  <div class="card-body" style="display:grid;gap:10px">
    <?php if ($sent && $sent->num_rows > 0): while ($row = $sent->fetch_assoc()): ?>
      <div class="msg-item">
        <div class="who">To <?= e($row['user_name']) ?> · <?= e($row['email']) ?></div>
        <div><?= e($row['admin_msg']) ?></div>
      </div>
    <?php endwhile; else: ?>
      <div class="empty">No outbound messages yet.</div>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
