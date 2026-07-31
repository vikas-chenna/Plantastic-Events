<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$org_ratings = $conn->query(
    "SELECT r.rating, r.disc, r.rating_date, o.user_name, o.company_name, o.email
     FROM tbl_org_rating r
     INNER JOIN tbl_organizer o ON o.org_id = r.org_id
     WHERE o.approve = 'Approve'
     ORDER BY r.rating_id DESC"
);

$cust_ratings = $conn->query(
    "SELECT r.rating, r.description, r.rating_date, c.user_name, c.email, o.user_name AS org_name, o.company_name
     FROM tbl_cust_rating r
     INNER JOIN tbl_customer c ON c.cust_id = r.cust_id
     LEFT JOIN tbl_organizer o ON o.org_id = r.org_id
     ORDER BY r.rating_id DESC"
);

$admin_page = 'feedback';
$admin_title = 'Feedback & Ratings';
$admin_subtitle = 'See how customers rate organizers and overall sentiment';
require __DIR__ . '/includes/layout_top.php';
?>

<div class="tabs" style="width:fit-content">
  <button class="tab-btn active" type="button" data-tab-group="fb" data-tab="customer">Customer → Organizer</button>
  <button class="tab-btn" type="button" data-tab-group="fb" data-tab="organizer">Other ratings</button>
</div>

<div class="tab-panel active" data-tab-panel-group="fb" data-tab-panel="customer">
  <div class="card">
    <div class="card-body" style="padding:0">
      <div class="table-wrap" style="border:0;border-radius:0">
        <table class="data">
          <thead>
            <tr><th>Customer</th><th>Organizer</th><th>Rating</th><th>Feedback</th><th>Date</th></tr>
          </thead>
          <tbody>
          <?php if ($cust_ratings && $cust_ratings->num_rows > 0): while ($r = $cust_ratings->fetch_assoc()): ?>
            <tr>
              <td><strong><?= e($r['user_name']) ?></strong><div style="color:var(--text-muted);font-size:.78rem"><?= e($r['email']) ?></div></td>
              <td><?= e($r['org_name'] ?: '—') ?><div style="color:var(--text-muted);font-size:.78rem"><?= e($r['company_name'] ?: '') ?></div></td>
              <td><span class="pill purple"><?= e($r['rating']) ?> ★</span></td>
              <td><?= e($r['description']) ?></td>
              <td><?= e((string)$r['rating_date']) ?></td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="5"><div class="empty">No customer ratings yet.</div></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="tab-panel" data-tab-panel-group="fb" data-tab-panel="organizer">
  <div class="card">
    <div class="card-body" style="padding:0">
      <div class="table-wrap" style="border:0;border-radius:0">
        <table class="data">
          <thead>
            <tr><th>Organizer</th><th>Company</th><th>Rating</th><th>Notes</th><th>Date</th></tr>
          </thead>
          <tbody>
          <?php if ($org_ratings && $org_ratings->num_rows > 0): while ($r = $org_ratings->fetch_assoc()): ?>
            <tr>
              <td><strong><?= e($r['user_name']) ?></strong><div style="color:var(--text-muted);font-size:.78rem"><?= e($r['email']) ?></div></td>
              <td><?= e($r['company_name']) ?></td>
              <td><span class="pill"><?= e($r['rating']) ?> ★</span></td>
              <td><?= e($r['disc']) ?></td>
              <td><?= e((string)$r['rating_date']) ?></td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="5"><div class="empty">No organizer rating rows yet.</div></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
