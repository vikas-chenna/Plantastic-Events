<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$path = ems_admin_inbox_path();
$items = [];
if (is_file($path)) {
    $decoded = json_decode(file_get_contents($path) ?: '[]', true);
    if (is_array($decoded)) $items = $decoded;
}

if (isset($_POST['mark_read']) && isset($_POST['id'])) {
    $id = (string)$_POST['id'];
    foreach ($items as &$it) {
        if (($it['id'] ?? '') === $id) $it['read'] = true;
    }
    unset($it);
    file_put_contents($path, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    redirect('notifications.php');
}
if (isset($_POST['mark_all'])) {
    foreach ($items as &$it) $it['read'] = true;
    unset($it);
    file_put_contents($path, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    redirect('notifications.php');
}
if (isset($_POST['clear_all'])) {
    file_put_contents($path, '[]');
    redirect('notifications.php');
}

$admin_page = 'notifications';
$admin_title = 'Notifications';
$admin_subtitle = 'Registration alerts, approvals needed, and inbound messages';
require __DIR__ . '/includes/layout_top.php';
?>

<div class="toolbar">
  <div>
    <span class="pill purple"><?= count($items) ?> total</span>
    <span class="pill"><?= count(array_filter($items, fn($i) => empty($i['read']))) ?> unread</span>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <form method="post"><button class="btn" name="mark_all" value="1">Mark all read</button></form>
    <form method="post" onsubmit="return confirm('Clear all notifications?');"><button class="btn btn-danger" name="clear_all" value="1">Clear all</button></form>
  </div>
</div>

<div class="card">
  <div class="card-body" style="display:grid;gap:12px">
    <?php if (!$items): ?>
      <div class="empty">
        <strong>Inbox is empty</strong>
        When organizers register/verify or someone messages admin, alerts show up here.
      </div>
    <?php else: foreach ($items as $it): ?>
      <div class="msg-item" style="<?= empty($it['read']) ? 'border-color: color-mix(in srgb, var(--brand) 40%, var(--border));' : '' ?>">
        <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap">
          <div class="who"><?= e($it['title'] ?? 'Notification') ?><?php if (empty($it['read'])): ?> <span class="badge">new</span><?php endif; ?></div>
          <div class="when"><?= e($it['created_at'] ?? '') ?></div>
        </div>
        <div><?= $it['body'] ?? '' ?></div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <?php if (empty($it['read'])): ?>
          <form method="post">
            <input type="hidden" name="id" value="<?= e($it['id'] ?? '') ?>">
            <button class="btn btn-sm btn-primary" name="mark_read" value="1">Mark read</button>
          </form>
          <?php else: ?>
            <span class="pill green">Read</span>
          <?php endif; ?>
          <a class="btn btn-sm" href="project-Organizers.php">Organizers</a>
          <a class="btn btn-sm" href="ping-customers.php">Customer messages</a>
          <a class="btn btn-sm" href="ping-organizers.php">Organizer messages</a>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2>Email delivery notes</h2></div>
  <div class="card-body">
    <p style="margin-top:0;color:var(--text-muted)">
      If Gmail SMTP is not configured, verification and approval emails are saved as HTML files in
      <code>storage/mail_log/</code>. Configure SMTP in <code>config.php</code> for real inbox delivery.
    </p>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
