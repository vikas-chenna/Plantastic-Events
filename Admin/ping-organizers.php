<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$flash = '';

$inbox = $conn->query(
    "SELECT m.admin_msg_id, m.org_id, m.admin_msg, m.org_msg, o.user_name, o.email, o.company_name
     FROM tbl_org_admin_msg m
     INNER JOIN tbl_organizer o ON o.org_id = m.org_id
     WHERE m.org_msg IS NOT NULL AND m.org_msg <> ''
     ORDER BY m.admin_msg_id DESC"
);

$sent = $conn->query(
    "SELECT m.admin_msg_id, m.org_id, m.admin_msg, m.org_msg, o.user_name, o.email, o.company_name
     FROM tbl_org_admin_msg m
     INNER JOIN tbl_organizer o ON o.org_id = m.org_id
     WHERE m.admin_msg IS NOT NULL AND m.admin_msg <> ''
     ORDER BY m.admin_msg_id DESC"
);

$organizers = $conn->query("SELECT org_id, user_name, email, company_name FROM tbl_organizer WHERE approve = 'Approve' ORDER BY user_name ASC");

if (isset($_POST['reply_org'])) {
    $reply = trim((string)($_POST['reply'] ?? ''));
    $admin_msg_id = (int)($_POST['admin_msg_id'] ?? 0);
    $org_id = (int)($_POST['org_id'] ?? 0);
    if ($reply !== '') {
        if ($admin_msg_id > 0) {
            $stmt = $conn->prepare('UPDATE tbl_org_admin_msg SET admin_msg = ? WHERE admin_msg_id = ?');
            $stmt->bind_param('si', $reply, $admin_msg_id);
            $stmt->execute(); $stmt->close();
        } elseif ($org_id > 0) {
            $empty = '';
            $stmt = $conn->prepare('INSERT INTO tbl_org_admin_msg (org_id, admin_msg, org_msg) VALUES (?, ?, ?)');
            $stmt->bind_param('iss', $org_id, $reply, $empty);
            $stmt->execute(); $stmt->close();
        }
        $flash = 'Reply sent to organizer.';
    }
}

if (isset($_POST['send'])) {
    $org_id = (int)($_POST['org_email'] ?? 0);
    $msg = trim((string)($_POST['msg'] ?? ''));
    if ($org_id > 0 && $msg !== '') {
        $empty = '';
        $stmt = $conn->prepare('INSERT INTO tbl_org_admin_msg (org_id, admin_msg, org_msg) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $org_id, $msg, $empty);
        $stmt->execute(); $stmt->close();
        $flash = 'Message sent to organizer.';
    } else {
        $flash = 'Select an organizer and type a message.';
    }
}

$admin_page = 'ping_organizers';
$admin_title = 'Message Organizers';
$admin_subtitle = 'Coordinate with event partners and answer their requests';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>

<div class="grid-2">
  <div class="card">
    <div class="card-header"><h2>Compose</h2></div>
    <div class="card-body">
      <form method="post" class="form-grid">
        <label class="field">Organizer
          <select name="org_email" required>
            <option value="">Choose organizer</option>
            <?php if ($organizers) while ($o = $organizers->fetch_assoc()): ?>
              <option value="<?= (int)$o['org_id'] ?>"><?= e(($o['company_name'] ?: $o['user_name']) . ' — ' . $o['email']) ?></option>
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
    <div class="card-header"><h2>Inbox from organizers</h2></div>
    <div class="card-body" style="display:grid;gap:12px;max-height:520px;overflow:auto">
      <?php if ($inbox && $inbox->num_rows > 0): while ($row = $inbox->fetch_assoc()): ?>
        <div class="msg-item">
          <div class="who"><?= e($row['user_name']) ?> <span class="pill purple"><?= e($row['company_name'] ?: 'Organizer') ?></span></div>
          <div><?= e($row['org_msg']) ?></div>
          <?php if (!empty($row['admin_msg'])): ?><div style="color:var(--text-muted)"><strong>Your reply:</strong> <?= e($row['admin_msg']) ?></div><?php endif; ?>
          <form method="post" class="form-grid">
            <input type="hidden" name="org_id" value="<?= (int)$row['org_id'] ?>">
            <input type="hidden" name="admin_msg_id" value="<?= (int)$row['admin_msg_id'] ?>">
            <textarea name="reply" placeholder="Type reply..." required></textarea>
            <button class="btn btn-sm btn-success" name="reply_org" value="1">Send reply</button>
          </form>
        </div>
      <?php endwhile; else: ?>
        <div class="empty"><strong>No organizer messages</strong></div>
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
