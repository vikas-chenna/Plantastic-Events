<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_organizer();
$org_id = (int)$_SESSION['organizer'];

function ems_count_i(mysqli $conn, string $sql): int {
    $r = $conn->query($sql);
    if (!$r) return 0;
    $row = $r->fetch_assoc();
    return (int)($row['total'] ?? 0);
}

$total_book = ems_count_i($conn, "SELECT COUNT(*) AS total FROM tbl_booking WHERE org_id = $org_id");
$pending_book = ems_count_i($conn, "SELECT COUNT(*) AS total FROM tbl_booking WHERE org_id = $org_id AND (status = '' OR status IS NULL)");
$confirmed_book = ems_count_i($conn, "SELECT COUNT(*) AS total FROM tbl_booking WHERE org_id = $org_id AND status = 'confirm'");
$total_events = ems_count_i($conn, "SELECT COUNT(*) AS total FROM tbl_event WHERE org_id = $org_id");
$total_ratings = ems_count_i($conn, "SELECT COUNT(*) AS total FROM tbl_cust_rating WHERE org_id = $org_id");
$avg_rating = 0.0;
$ar = $conn->query("SELECT AVG(CAST(rating AS DECIMAL(10,2))) AS avg_r FROM tbl_cust_rating WHERE org_id = $org_id");
if ($ar && ($row = $ar->fetch_assoc()) && $row['avg_r'] !== null) {
    $avg_rating = round((float)$row['avg_r'], 1);
}

$pending = $conn->query(
    "SELECT b.b_id, b.eve_name, b.eve_type, b.strt_date, b.start_time, b.event_budget, b.expect_guests,
            c.user_name, c.email, c.contact
     FROM tbl_booking b
     INNER JOIN tbl_customer c ON c.cust_id = b.cust_id
     WHERE b.org_id = $org_id AND (b.status = '' OR b.status IS NULL)
     ORDER BY b.b_id DESC LIMIT 8"
);

$confirmed = $conn->query(
    "SELECT b.b_id, b.eve_name, b.eve_type, b.strt_date, b.status, c.user_name
     FROM tbl_booking b
     INNER JOIN tbl_customer c ON c.cust_id = b.cust_id
     WHERE b.org_id = $org_id AND b.status = 'confirm'
     ORDER BY b.b_id DESC LIMIT 8"
);

$events = $conn->query("SELECT evn_id, eve_name, event_type, city, start_date, end_date, status FROM tbl_event WHERE org_id = $org_id ORDER BY evn_id DESC LIMIT 6");

$me = $conn->query("SELECT user_name, company_name, email, approve, block, v_status FROM tbl_organizer WHERE org_id = $org_id LIMIT 1");
$profile = $me ? $me->fetch_assoc() : null;
if ($profile) {
    $_SESSION['organizer_name'] = $profile['user_name'];
}

$org_page = 'dashboard';
$org_title = 'Dashboard';
$org_subtitle = 'Your bookings, events, and performance at a glance';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($profile && strcasecmp((string)$profile['approve'], 'Approve') !== 0): ?>
  <div class="alert info">Your account is not fully approved yet. Some features may be limited until admin approval.</div>
<?php endif; ?>

<div class="stat-grid">
  <div class="stat-card orange">
    <div class="label">Total bookings</div>
    <div class="value"><?= (int)$total_book ?></div>
    <div class="hint"><?= (int)$pending_book ?> waiting for your action</div>
  </div>
  <div class="stat-card green">
    <div class="label">Confirmed</div>
    <div class="value"><?= (int)$confirmed_book ?></div>
    <div class="hint">Accepted event requests</div>
  </div>
  <div class="stat-card purple">
    <div class="label">My events</div>
    <div class="value"><?= (int)$total_events ?></div>
    <div class="hint">Services listed on the marketplace</div>
  </div>
  <div class="stat-card blue">
    <div class="label">Rating</div>
    <div class="value"><?= $avg_rating > 0 ? e((string)$avg_rating) : '—' ?></div>
    <div class="hint"><?= (int)$total_ratings ?> customer reviews</div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-header">
      <h2>Pending booking requests</h2>
      <a class="btn btn-sm btn-primary" href="events-bookings.php">Open bookings</a>
    </div>
    <div class="card-body" style="padding:0">
      <div class="table-wrap" style="border:0;border-radius:0">
        <table class="data">
          <thead>
            <tr><th>Event</th><th>Customer</th><th>Date</th><th>Budget</th><th></th></tr>
          </thead>
          <tbody>
          <?php if ($pending && $pending->num_rows > 0): while ($b = $pending->fetch_assoc()): ?>
            <tr>
              <td><strong><?= e($b['eve_name']) ?></strong><div style="color:var(--text-muted);font-size:.78rem"><?= e($b['eve_type']) ?></div></td>
              <td><?= e($b['user_name']) ?><div style="color:var(--text-muted);font-size:.78rem"><?= e($b['contact']) ?></div></td>
              <td><?= e($b['strt_date']) ?> <?= e(substr((string)$b['start_time'], 0, 5)) ?></td>
              <td><?= e($b['event_budget']) ?></td>
              <td><a class="btn btn-sm" href="events-bookings.php">Review</a></td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="5"><div class="empty"><strong>No pending requests</strong>New customer bookings will show here.</div></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h2>My services</h2>
      <a class="btn btn-sm" href="events.php">Manage</a>
    </div>
    <div class="card-body" style="display:grid;gap:10px">
      <?php if ($events && $events->num_rows > 0): while ($e = $events->fetch_assoc()): ?>
        <div class="msg-item">
          <div class="who"><?= e($e['eve_name']) ?> <span class="pill"><?= e($e['event_type']) ?></span></div>
          <div style="color:var(--text-muted);font-size:.85rem">
            <?= e($e['city'] ?: '—') ?> · <?= e((string)$e['start_date']) ?> → <?= e((string)$e['end_date']) ?>
          </div>
          <div><a class="btn btn-sm" href="edit-event.php?evn_id=<?= (int)$e['evn_id'] ?>">Edit</a></div>
        </div>
      <?php endwhile; else: ?>
        <div class="empty"><strong>No events yet</strong>Add your first birthday, wedding, or party package.</div>
        <a class="btn btn-primary btn-block" href="events.php">Add event</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2>Recently confirmed</h2>
  </div>
  <div class="card-body" style="padding:0">
    <div class="table-wrap" style="border:0;border-radius:0">
      <table class="data">
        <thead><tr><th>Booking</th><th>Customer</th><th>Type</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        <?php if ($confirmed && $confirmed->num_rows > 0): while ($b = $confirmed->fetch_assoc()): ?>
          <tr>
            <td><strong><?= e($b['eve_name']) ?></strong></td>
            <td><?= e($b['user_name']) ?></td>
            <td><?= e($b['eve_type']) ?></td>
            <td><?= e($b['strt_date']) ?></td>
            <td><span class="pill green">Confirmed</span></td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="5"><div class="empty">No confirmed bookings yet.</div></td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
