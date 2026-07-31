<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$flash = '';
if (isset($_POST['delete']) && isset($_POST['cust_id'])) {
    $cust_id = (int)$_POST['cust_id'];
    $del = $conn->prepare('DELETE FROM tbl_customer WHERE cust_id = ?');
    $del->bind_param('i', $cust_id);
    if ($del->execute()) $flash = 'Customer deleted.';
    $del->close();
}

$res = $conn->query("SELECT cust_id, user_name, gender, contact, email, city, state, address2, created_at FROM tbl_customer WHERE status = '1' ORDER BY cust_id DESC");
$pending = $conn->query("SELECT COUNT(*) AS total FROM tbl_customer WHERE status = '0'");
$pending_count = $pending ? (int)$pending->fetch_assoc()['total'] : 0;

$admin_page = 'customers';
$admin_title = 'Customers';
$admin_subtitle = 'Verified customer accounts registered on Plantastic Events';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>

<div class="toolbar">
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <span class="pill green"><?= $res ? (int)$res->num_rows : 0 ?> verified</span>
    <span class="pill"><?= (int)$pending_count ?> awaiting email verify</span>
  </div>
  <div class="search">
    <span class="sico">⌕</span>
    <input type="search" placeholder="Search customers..." data-search-input="cust">
  </div>
</div>

<div class="card">
  <div class="card-body" style="padding:0">
    <div class="table-wrap" style="border:0;border-radius:0">
      <table class="data">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Location</th>
            <th>Joined</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        <?php if ($res && $res->num_rows > 0): while ($row = $res->fetch_assoc()): ?>
          <tr data-search-item="cust">
            <td>#<?= (int)$row['cust_id'] ?></td>
            <td><strong><?= e($row['user_name']) ?></strong><div style="color:var(--text-muted);font-size:.78rem"><?= e($row['gender']) ?></div></td>
            <td><?= e($row['contact']) ?></td>
            <td><?= e($row['email']) ?></td>
            <td><?= e(trim($row['city'] . ', ' . $row['state'], ' ,')) ?><div style="color:var(--text-muted);font-size:.78rem"><?= e($row['address2']) ?></div></td>
            <td><?= e((string)$row['created_at']) ?></td>
            <td>
              <form method="post" onsubmit="return confirm('Delete this customer?');">
                <input type="hidden" name="cust_id" value="<?= (int)$row['cust_id'] ?>">
                <button class="btn btn-sm btn-danger" name="delete" value="1">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="7"><div class="empty"><strong>No verified customers</strong>They appear here after email verification.</div></td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
