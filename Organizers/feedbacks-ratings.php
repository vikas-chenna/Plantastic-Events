<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_organizer();
$org_id = (int)$_SESSION['organizer'];

$result1 = $conn->prepare(
    "SELECT r.rating, r.description, r.rating_date, c.user_name, c.email
     FROM tbl_cust_rating r
     INNER JOIN tbl_customer c ON c.cust_id = r.cust_id
     WHERE r.org_id = ?
     ORDER BY r.rating_id DESC"
);
$result1->bind_param('i', $org_id);
$result1->execute();
$ratings = $result1->get_result();

$avg = 0.0; $count = 0;
$ar = $conn->query("SELECT AVG(CAST(rating AS DECIMAL(10,2))) AS a, COUNT(*) AS c FROM tbl_cust_rating WHERE org_id = $org_id");
if ($ar && ($row = $ar->fetch_assoc())) {
    $avg = $row['a'] !== null ? round((float)$row['a'], 1) : 0.0;
    $count = (int)$row['c'];
}

$org_page = 'ratings';
$org_title = 'Ratings & Feedback';
$org_subtitle = 'See what customers say about your services';
require __DIR__ . '/includes/layout_top.php';
?>

<div class="stat-grid" style="grid-template-columns: repeat(2, minmax(0,1fr));">
  <div class="stat-card purple">
    <div class="label">Average rating</div>
    <div class="value"><?= $avg > 0 ? e((string)$avg) . ' ★' : '—' ?></div>
    <div class="hint">Across all customer reviews</div>
  </div>
  <div class="stat-card orange">
    <div class="label">Total reviews</div>
    <div class="value"><?= (int)$count ?></div>
    <div class="hint">Keep delivering great events</div>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2>Customer reviews</h2></div>
  <div class="card-body" style="padding:0">
    <div class="table-wrap" style="border:0;border-radius:0">
      <table class="data">
        <thead><tr><th>Customer</th><th>Rating</th><th>Feedback</th><th>Date</th></tr></thead>
        <tbody>
        <?php if ($ratings && $ratings->num_rows > 0): while ($r = $ratings->fetch_assoc()): ?>
          <tr>
            <td><strong><?= e($r['user_name']) ?></strong><div style="color:var(--text-muted);font-size:.78rem"><?= e($r['email']) ?></div></td>
            <td><span class="pill purple"><?= e($r['rating']) ?> ★</span></td>
            <td><?= e($r['description']) ?></td>
            <td><?= e((string)$r['rating_date']) ?></td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="4"><div class="empty"><strong>No ratings yet</strong>Reviews appear after customers rate your events.</div></td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
