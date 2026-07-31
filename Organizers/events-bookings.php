<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_organizer();
$org_id = (int)$_SESSION['organizer'];

if (isset($_POST['confirm']) && isset($_POST['b_id'])) {
    $bid = post_int('b_id');
    $chk = $conn->prepare("SELECT b_id FROM tbl_booking WHERE b_id = ? AND org_id = ? LIMIT 1");
    $chk->bind_param('ii', $bid, $org_id);
    $chk->execute();
    $ok = $chk->get_result()->num_rows > 0;
    $chk->close();
    if ($ok) {
        $status = 'confirm';
        $up = $conn->prepare("UPDATE tbl_booking SET status = ? WHERE b_id = ? AND org_id = ?");
        $up->bind_param('sii', $status, $bid, $org_id);
        $up->execute(); $up->close();
    }
    $_SESSION['org_flash'] = 'Booking confirmed';
    header('Location: events-bookings.php');
    exit;
}

if (isset($_POST['reject']) && isset($_POST['b_id'])) {
    $bid = post_int('b_id');
    $chk = $conn->prepare("SELECT b_id FROM tbl_booking WHERE b_id = ? AND org_id = ? LIMIT 1");
    $chk->bind_param('ii', $bid, $org_id);
    $chk->execute();
    $ok = $chk->get_result()->num_rows > 0;
    $chk->close();
    if ($ok) {
        $del = $conn->prepare("DELETE FROM tbl_booking WHERE b_id = ? AND org_id = ? AND (status = '' OR status IS NULL)");
        $del->bind_param('ii', $bid, $org_id);
        $del->execute(); $del->close();
    }
    $_SESSION['org_flash'] = 'Booking rejected';
    header('Location: events-bookings.php');
    exit;
}

$pending = $conn->prepare(
    "SELECT b.*, c.user_name, c.email, c.contact
     FROM tbl_booking b
     INNER JOIN tbl_customer c ON c.cust_id = b.cust_id
     WHERE b.org_id = ? AND (b.status = '' OR b.status IS NULL)
     ORDER BY b.b_id DESC"
);
$pending->bind_param('i', $org_id);
$pending->execute();
$pending_rows = $pending->get_result();

$done = $conn->prepare(
    "SELECT b.*, c.user_name, c.email, c.contact
     FROM tbl_booking b
     INNER JOIN tbl_customer c ON c.cust_id = b.cust_id
     WHERE b.org_id = ? AND b.status = 'confirm'
     ORDER BY b.b_id DESC"
);
$done->bind_param('i', $org_id);
$done->execute();
$done_rows = $done->get_result();

$org_page = 'bookings';
$org_title = 'Bookings';
$org_subtitle = 'Accept or decline customer event requests';
require __DIR__ . '/includes/layout_top.php';
?>

<div class="toolbar">
  <div class="tabs">
    <button class="tab-btn active" type="button" data-tab-group="bk" data-tab="pending">Pending <span class="badge"><?= $pending_rows ? (int)$pending_rows->num_rows : 0 ?></span></button>
    <button class="tab-btn" type="button" data-tab-group="bk" data-tab="confirmed">Confirmed <span class="badge soft"><?= $done_rows ? (int)$done_rows->num_rows : 0 ?></span></button>
  </div>
  <div class="search">
    <span class="sico">⌕</span>
    <input type="search" placeholder="Search bookings..." data-search-input="books">
  </div>
</div>

<div class="tab-panel active" data-tab-panel-group="bk" data-tab-panel="pending">
  <div class="grid-auto">
    <?php if ($pending_rows && $pending_rows->num_rows > 0): while ($b = $pending_rows->fetch_assoc()): ?>
      <div class="card entity-card" data-search-item="books">
        <div class="card-body">
          <div class="title">
            <div>
              <h3><?= e($b['eve_name']) ?></h3>
              <div style="color:var(--text-muted);font-size:.85rem"><?= e($b['eve_type']) ?> · Booking #<?= (int)$b['b_id'] ?></div>
            </div>
            <span class="pill">Pending</span>
          </div>
          <div class="kv">
            <div class="k">Customer</div><div><?= e($b['user_name']) ?></div>
            <div class="k">Email</div><div><?= e($b['email']) ?></div>
            <div class="k">Phone</div><div><?= e($b['contact']) ?></div>
            <div class="k">When</div><div><?= e($b['strt_date']) ?> <?= e(substr((string)$b['start_time'],0,5)) ?> → <?= e($b['end_date']) ?></div>
            <div class="k">Venue</div><div><?= e($b['venue_name'] ?: '') ?> <?= e($b['venue_addr'] ?: '') ?></div>
            <div class="k">Guests</div><div><?= e($b['expect_guests'] ?: '—') ?></div>
            <div class="k">Budget</div><div><?= e($b['event_budget'] ?: '—') ?></div>
            <div class="k">Extras</div><div>Catering: <?= e($b['catering'] ?: '—') ?> · Photo: <?= e($b['photography'] ?: '—') ?></div>
            <div class="k">Notes</div><div><?= e($b['eve_desc'] ?: '—') ?></div>
          </div>
        </div>
        <div class="card-footer">
          <form method="post" style="display:flex;gap:8px;flex-wrap:wrap">
            <input type="hidden" name="b_id" value="<?= (int)$b['b_id'] ?>">
            <button class="btn btn-success btn-sm" name="confirm" value="1">Confirm</button>
            <button class="btn btn-danger btn-sm" name="reject" value="1" onclick="return confirm('Reject this booking?')">Reject</button>
          </form>
        </div>
      </div>
    <?php endwhile; else: ?>
      <div class="card"><div class="empty"><strong>No pending bookings</strong>New requests will appear here.</div></div>
    <?php endif; ?>
  </div>
</div>

<div class="tab-panel" data-tab-panel-group="bk" data-tab-panel="confirmed">
  <div class="card">
    <div class="card-body" style="padding:0">
      <div class="table-wrap" style="border:0;border-radius:0">
        <table class="data">
          <thead><tr><th>Event</th><th>Customer</th><th>Contact</th><th>Date</th><th>Budget</th><th>Status</th></tr></thead>
          <tbody>
          <?php if ($done_rows && $done_rows->num_rows > 0): while ($b = $done_rows->fetch_assoc()): ?>
            <tr data-search-item="books">
              <td><strong><?= e($b['eve_name']) ?></strong><div style="color:var(--text-muted);font-size:.78rem"><?= e($b['eve_type']) ?></div></td>
              <td><?= e($b['user_name']) ?></td>
              <td><?= e($b['contact']) ?><div style="color:var(--text-muted);font-size:.78rem"><?= e($b['email']) ?></div></td>
              <td><?= e($b['strt_date']) ?></td>
              <td><?= e($b['event_budget']) ?></td>
              <td><span class="pill green">Confirmed</span></td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="6"><div class="empty">No confirmed bookings yet.</div></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
