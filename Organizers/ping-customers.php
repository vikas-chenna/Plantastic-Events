<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_organizer();
$org_id = (int)$_SESSION['organizer'];

$flash = '';

$select = $conn->query(
    "SELECT DISTINCT c.cust_id, c.user_name, c.email
     FROM tbl_customer c
     INNER JOIN tbl_booking b ON b.cust_id = c.cust_id
     WHERE b.org_id = $org_id
     ORDER BY c.user_name ASC"
);
if (!$select || $select->num_rows === 0) {
    $select = $conn->query("SELECT cust_id, user_name, email FROM tbl_customer WHERE status = '1' ORDER BY user_name ASC");
}

if (isset($_POST['send'])) {
    $cust_id = (int)($_POST['cust_email'] ?? 0);
    $msg = trim((string)($_POST['msg'] ?? ''));
    if ($cust_id > 0 && $msg !== '') {
        $empty = '';
        $stmt = $conn->prepare('INSERT INTO tbl_org_cust_msg (cust_id, org_id, org_msg, cust_msg) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iiss', $cust_id, $org_id, $msg, $empty);
        $stmt->execute(); $stmt->close();
        $flash = 'Message sent to customer.';
    } else {
        $flash = 'Select a customer and type a message.';
    }
}

if (isset($_POST['reply_org'])) {
    $reply = trim((string)($_POST['reply'] ?? ''));
    $cust_id = (int)($_POST['cust_id'] ?? 0);
    if ($reply !== '' && $cust_id > 0) {
        $empty = '';
        $stmt = $conn->prepare('INSERT INTO tbl_org_cust_msg (cust_id, org_id, org_msg, cust_msg) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iiss', $cust_id, $org_id, $reply, $empty);
        $stmt->execute(); $stmt->close();
        $flash = 'Reply sent.';
    }
}

$inbox = $conn->query(
    "SELECT m.cust_msg_id, m.cust_id, m.org_msg, m.cust_msg, c.user_name, c.email
     FROM tbl_org_cust_msg m
     INNER JOIN tbl_customer c ON c.cust_id = m.cust_id
     WHERE m.org_id = $org_id
     ORDER BY m.cust_msg_id DESC"
);

$org_page = 'ping_customers';
$org_title = 'Message Customers';
$org_subtitle = 'Coordinate event details with your clients';
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
            <?php if ($select) { $select->data_seek(0); while ($c = $select->fetch_assoc()): ?>
              <option value="<?= (int)$c['cust_id'] ?>"><?= e($c['user_name'] . ' — ' . $c['email']) ?></option>
            <?php endwhile; } ?>
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
    <div class="card-header"><h2>Thread history</h2></div>
    <div class="card-body" style="display:grid;gap:12px;max-height:560px;overflow:auto">
      <?php if ($inbox && $inbox->num_rows > 0): while ($m = $inbox->fetch_assoc()): ?>
        <div class="msg-item">
          <div class="who"><?= e($m['user_name']) ?> <span class="pill">Customer</span></div>
          <?php if (!empty($m['org_msg'])): ?><div><strong>You:</strong> <?= e($m['org_msg']) ?></div><?php endif; ?>
          <?php if (!empty($m['cust_msg'])): ?><div><strong>Customer:</strong> <?= e($m['cust_msg']) ?></div><?php endif; ?>
          <form method="post" class="form-grid">
            <input type="hidden" name="cust_id" value="<?= (int)$m['cust_id'] ?>">
            <textarea name="reply" placeholder="Reply..." required></textarea>
            <button class="btn btn-sm btn-success" name="reply_org" value="1">Send reply</button>
          </form>
        </div>
      <?php endwhile; else: ?>
        <div class="empty"><strong>No messages yet</strong></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
