<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$flash = '';
if (isset($_POST['reply_report'])) {
    $repo_id = (int)($_POST['repo_id'] ?? 0);
    $reply = trim((string)($_POST['admin_reply'] ?? ''));
    if ($repo_id > 0 && $reply !== '') {
        // column may exist as admin_reply
        $stmt = $conn->prepare('UPDATE tbl_report SET admin_reply = ? WHERE repo_id = ?');
        if ($stmt) {
            $stmt->bind_param('si', $reply, $repo_id);
            $stmt->execute();
            $stmt->close();
            $flash = 'Reply saved on report.';
        }
    }
}

$reports = $conn->query(
    "SELECT r.repo_id, r.repo_name, r.repo_desc, r.admin_reply, r.repo_date,
            r.org_name, c.user_name AS customer_name, c.email AS customer_email,
            o.user_name AS organizer_name, o.email AS organizer_email,
            b.eve_name, b.b_id
     FROM tbl_report r
     LEFT JOIN tbl_customer c ON c.cust_id = r.cust_id
     LEFT JOIN tbl_organizer o ON o.org_id = r.org_id
     LEFT JOIN tbl_booking b ON b.b_id = r.b_id
     ORDER BY r.repo_id DESC"
);

$admin_page = 'reports';
$admin_title = 'Reports';
$admin_subtitle = 'Customer-submitted issues against organizers / bookings';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>

<div class="card">
  <div class="card-header">
    <h2>All reports</h2>
    <span class="pill"><?= $reports ? (int)$reports->num_rows : 0 ?> total</span>
  </div>
  <div class="card-body" style="display:grid;gap:12px">
    <?php if ($reports && $reports->num_rows > 0): while ($r = $reports->fetch_assoc()): ?>
      <div class="msg-item">
        <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap">
          <div class="who"><?= e($r['repo_name'] ?: 'Report') ?> <span class="pill red">#<?= (int)$r['repo_id'] ?></span></div>
          <div class="when"><?= e((string)$r['repo_date']) ?></div>
        </div>
        <div class="kv" style="margin-top:4px">
          <div class="k">Customer</div><div><?= e($r['customer_name'] ?: '—') ?> · <?= e($r['customer_email'] ?: '') ?></div>
          <div class="k">Organizer</div><div><?= e($r['organizer_name'] ?: ($r['org_name'] ?: '—')) ?> · <?= e($r['organizer_email'] ?: '') ?></div>
          <div class="k">Booking</div><div><?= e($r['eve_name'] ?: '—') ?><?= !empty($r['b_id']) ? ' (#' . (int)$r['b_id'] . ')' : '' ?></div>
          <div class="k">Details</div><div><?= e($r['repo_desc']) ?></div>
        </div>
        <?php if (!empty($r['admin_reply'])): ?>
          <div class="alert info" style="margin:0"><strong>Admin reply:</strong> <?= e($r['admin_reply']) ?></div>
        <?php endif; ?>
        <form method="post" class="form-grid">
          <input type="hidden" name="repo_id" value="<?= (int)$r['repo_id'] ?>">
          <textarea name="admin_reply" placeholder="Write admin response / resolution note..." required></textarea>
          <button class="btn btn-sm btn-primary" name="reply_report" value="1">Save reply</button>
        </form>
      </div>
    <?php endwhile; else: ?>
      <div class="empty"><strong>No reports filed</strong>Customer complaints will appear here.</div>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
