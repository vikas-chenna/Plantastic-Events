<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$current_date = date('Y-m-d');
$conn->query("
    UPDATE tbl_event
    SET status = CASE
        WHEN start_date > '$current_date' THEN 'Pending'
        WHEN start_date <= '$current_date' AND end_date >= '$current_date' THEN 'Ongoing'
        WHEN end_date < '$current_date' THEN 'Completed'
        ELSE status
    END
");

function ems_count(mysqli $conn, string $sql): int {
    $r = $conn->query($sql);
    if (!$r) return 0;
    $row = $r->fetch_assoc();
    return (int)($row['total'] ?? 0);
}

$total_org = ems_count($conn, "SELECT COUNT(*) AS total FROM tbl_organizer");
$total_cus = ems_count($conn, "SELECT COUNT(*) AS total FROM tbl_customer WHERE status = '1'");
$pending_org = ems_count($conn, "SELECT COUNT(*) AS total FROM tbl_organizer WHERE (approve = '' OR approve IS NULL OR approve = ' ') AND v_status = '1'");
$blocked_org = ems_count($conn, "SELECT COUNT(*) AS total FROM tbl_organizer WHERE block = 'block'");
$com_eve = ems_count($conn, "SELECT COUNT(*) AS total FROM tbl_event WHERE LOWER(status) = 'completed'");
$ongo_eve = ems_count($conn, "SELECT COUNT(*) AS total FROM tbl_event WHERE LOWER(status) = 'ongoing'");
$pen_eve = ems_count($conn, "SELECT COUNT(*) AS total FROM tbl_event WHERE LOWER(status) = 'pending'");
$total_book = ems_count($conn, "SELECT COUNT(*) AS total FROM tbl_booking");
$open_book = ems_count($conn, "SELECT COUNT(*) AS total FROM tbl_booking WHERE status = '' OR status IS NULL");

$events = $conn->query(
    "SELECT e.eve_name, e.event_type, e.start_date, e.end_date, e.status, o.user_name, o.company_name
     FROM tbl_event e
     INNER JOIN tbl_organizer o ON o.org_id = e.org_id
     ORDER BY e.evn_id DESC
     LIMIT 12"
);

$recent_customers = $conn->query("SELECT user_name, email, contact, created_at FROM tbl_customer WHERE status = '1' ORDER BY cust_id DESC LIMIT 6");
$pending_list = $conn->query("SELECT org_id, user_name, company_name, email, created_at FROM tbl_organizer WHERE (approve = '' OR approve IS NULL OR approve = ' ') AND v_status = '1' ORDER BY org_id DESC LIMIT 6");

// notifications preview
$notifs = [];
$npath = ems_admin_inbox_path();
if (is_file($npath)) {
    $decoded = json_decode(file_get_contents($npath) ?: '[]', true);
    if (is_array($decoded)) $notifs = array_slice($decoded, 0, 5);
}

$admin_page = 'dashboard';
$admin_title = 'Dashboard';
$admin_subtitle = 'Overview of people, events, bookings and alerts';
require __DIR__ . '/includes/layout_top.php';
?>

<div class="stat-grid">
  <div class="stat-card orange">
    <div class="label">Organizers</div>
    <div class="value"><?= (int)$total_org ?></div>
    <div class="hint"><?= (int)$pending_org ?> pending approval · <?= (int)$blocked_org ?> blocked</div>
  </div>
  <div class="stat-card purple">
    <div class="label">Verified Customers</div>
    <div class="value"><?= (int)$total_cus ?></div>
    <div class="hint">Ready to book events</div>
  </div>
  <div class="stat-card green">
    <div class="label">Bookings</div>
    <div class="value"><?= (int)$total_book ?></div>
    <div class="hint"><?= (int)$open_book ?> awaiting organizer action</div>
  </div>
  <div class="stat-card blue">
    <div class="label">Events</div>
    <div class="value"><?= (int)($com_eve + $ongo_eve + $pen_eve) ?></div>
    <div class="hint"><?= (int)$ongo_eve ?> ongoing · <?= (int)$pen_eve ?> upcoming · <?= (int)$com_eve ?> done</div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-header">
      <h2>Latest events</h2>
      <a class="btn btn-sm" href="project-Organizers.php">Manage organizers</a>
    </div>
    <div class="card-body" style="padding:0">
      <div class="table-wrap" style="border:0;border-radius:0">
        <table class="data">
          <thead>
            <tr>
              <th>Event</th>
              <th>Type</th>
              <th>Organizer</th>
              <th>Dates</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($events && $events->num_rows > 0): while ($row = $events->fetch_assoc()): ?>
            <tr>
              <td><strong><?= e($row['eve_name']) ?></strong></td>
              <td><?= e($row['event_type']) ?></td>
              <td><?= e($row['user_name']) ?><div class="hint" style="color:var(--text-muted);font-size:.78rem"><?= e($row['company_name']) ?></div></td>
              <td><?= e($row['start_date']) ?> → <?= e($row['end_date']) ?></td>
              <td>
                <?php
                  $st = strtolower((string)$row['status']);
                  $cls = $st === 'completed' ? 'green' : ($st === 'ongoing' ? 'purple' : '');
                ?>
                <span class="pill <?= $cls ?>"><?= e($row['status'] ?: '—') ?></span>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="5"><div class="empty">No events yet. Approved organizers can add services.</div></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h2>Needs attention</h2>
      <a class="btn btn-sm btn-primary" href="project-Organizers.php">Review requests</a>
    </div>
    <div class="card-body" style="display:grid;gap:12px">
      <?php if ($pending_list && $pending_list->num_rows > 0): while ($p = $pending_list->fetch_assoc()): ?>
        <div class="msg-item">
          <div class="who"><?= e($p['user_name']) ?> <span class="pill">Request</span></div>
          <div><?= e($p['company_name'] ?: 'No company') ?> · <?= e($p['email']) ?></div>
          <div class="when">Registered <?= e((string)$p['created_at']) ?></div>
        </div>
      <?php endwhile; else: ?>
        <div class="empty"><strong>All clear</strong>No pending organizer requests.</div>
      <?php endif; ?>

      <hr style="border:0;border-top:1px solid var(--border);margin:4px 0">
      <h3 style="margin:0;font-size:.95rem">Recent alerts</h3>
      <?php if ($notifs): foreach ($notifs as $n): ?>
        <div class="msg-item">
          <div class="who"><?= e($n['title'] ?? 'Alert') ?><?php if (empty($n['read'])): ?> <span class="badge">new</span><?php endif; ?></div>
          <div class="when"><?= e($n['created_at'] ?? '') ?></div>
        </div>
      <?php endforeach; else: ?>
        <div class="empty" style="padding:18px"><strong>No alerts</strong>Registration and message alerts appear here.</div>
      <?php endif; ?>
      <a class="btn btn-block" href="notifications.php">Open notifications</a>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2>Newest customers</h2>
    <a class="btn btn-sm" href="project-Customers.php">View all</a>
  </div>
  <div class="card-body" style="padding:0">
    <div class="table-wrap" style="border:0;border-radius:0">
      <table class="data">
        <thead><tr><th>Name</th><th>Email</th><th>Contact</th><th>Joined</th></tr></thead>
        <tbody>
        <?php if ($recent_customers && $recent_customers->num_rows > 0): while ($c = $recent_customers->fetch_assoc()): ?>
          <tr>
            <td><strong><?= e($c['user_name']) ?></strong></td>
            <td><?= e($c['email']) ?></td>
            <td><?= e($c['contact']) ?></td>
            <td><?= e((string)$c['created_at']) ?></td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="4"><div class="empty">No verified customers yet.</div></td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
