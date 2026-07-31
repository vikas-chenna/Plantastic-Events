<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_organizer();
$org_id = (int)$_SESSION['organizer'];

$flash = '';

if (isset($_POST['send_msg'])) {
    $org_msg = trim((string)($_POST['org_msg'] ?? ''));
    if ($org_msg !== '') {
        $empty = '';
        $stmt = $conn->prepare('INSERT INTO tbl_org_admin_msg (org_id, admin_msg, org_msg) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $org_id, $empty, $org_msg);
        $stmt->execute(); $stmt->close();
        ems_admin_notify('Message from organizer #' . $org_id, '<p>' . e($org_msg) . '</p>');
        $flash = 'Message sent to admin.';
    } else {
        $flash = 'Please type a message.';
    }
}

if (isset($_POST['send_reply'])) {
    $reply = trim((string)($_POST['reply'] ?? ''));
    $admin_msg_id = (int)($_POST['admin_msg_id'] ?? 0);
    if ($reply !== '' && $admin_msg_id > 0) {
        $stmt = $conn->prepare('UPDATE tbl_org_admin_msg SET org_msg = ? WHERE admin_msg_id = ? AND org_id = ?');
        $stmt->bind_param('sii', $reply, $admin_msg_id, $org_id);
        $stmt->execute(); $stmt->close();
        $flash = 'Reply sent.';
    }
}

$thread = $conn->query("SELECT * FROM tbl_org_admin_msg WHERE org_id = $org_id ORDER BY admin_msg_id DESC");

$org_page = 'ping_admin';
$org_title = 'Message Admin';
$org_subtitle = 'Ask questions or reply to platform support';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>

<div class="grid-2">
  <div class="card">
    <div class="card-header"><h2>New message</h2></div>
    <div class="card-body">
      <form method="post" class="form-grid">
        <label class="field">Message to admin
          <textarea name="org_msg" required placeholder="Write your message..."></textarea>
        </label>
        <button class="btn btn-primary" type="submit" name="send_msg" value="1">Send to admin</button>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Conversation</h2></div>
    <div class="card-body" style="display:grid;gap:12px;max-height:560px;overflow:auto">
      <?php if ($thread && $thread->num_rows > 0): while ($m = $thread->fetch_assoc()): ?>
        <div class="msg-item">
          <?php if (!empty($m['admin_msg'])): ?>
            <div class="who">Admin <span class="pill purple">Support</span></div>
            <div><?= e($m['admin_msg']) ?></div>
          <?php endif; ?>
          <?php if (!empty($m['org_msg'])): ?>
            <div class="who" style="margin-top:6px">You</div>
            <div><?= e($m['org_msg']) ?></div>
          <?php endif; ?>
          <?php if (!empty($m['admin_msg']) && empty($m['org_msg'])): ?>
            <form method="post" class="form-grid">
              <input type="hidden" name="admin_msg_id" value="<?= (int)$m['admin_msg_id'] ?>">
              <textarea name="reply" placeholder="Reply to admin..." required></textarea>
              <button class="btn btn-sm btn-success" name="send_reply" value="1">Send reply</button>
            </form>
          <?php endif; ?>
        </div>
      <?php endwhile; else: ?>
        <div class="empty"><strong>No messages yet</strong>Start a conversation with admin.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
