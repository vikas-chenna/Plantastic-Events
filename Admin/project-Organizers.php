<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $org_id = post_int('org_id');
    if ($org_id <= 0) {
        $flash = 'Invalid organizer.';
    } else {
        $stmt = $conn->prepare('SELECT org_id, email, user_name, company_name FROM tbl_organizer WHERE org_id = ? LIMIT 1');
        $stmt->bind_param('i', $org_id);
        $stmt->execute();
        $org = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$org) {
            $flash = 'Organizer not found.';
        } elseif (isset($_POST['approve'])) {
            $approve = 'Approve'; $status = 'Active'; $block = '';
            $up = $conn->prepare('UPDATE tbl_organizer SET approve = ?, status = ?, block = ? WHERE org_id = ?');
            $up->bind_param('sssi', $approve, $status, $block, $org_id);
            $up->execute(); $up->close();
            // Approval is a moderation action: keep it instant and do not block on SMTP.
            // The organizer can immediately log in after the DB update.
            $flash = 'Organizer approved.';
        } elseif (isset($_POST['reject'])) {
            // Do not make admin wait for SMTP before completing moderation.
            $del = $conn->prepare('DELETE FROM tbl_organizer WHERE org_id = ?');
            $del->bind_param('i', $org_id);
            $del->execute(); $del->close();
            $flash = 'Organizer rejected and removed.';
        } elseif (isset($_POST['block'])) {
            $block = 'block';
            $up = $conn->prepare('UPDATE tbl_organizer SET block = ? WHERE org_id = ?');
            $up->bind_param('si', $block, $org_id);
            $up->execute(); $up->close();
            // Keep moderation actions fast: blocking is a DB-only action.
            // Email is intentionally skipped here because synchronous SMTP made the admin wait seconds.
            $flash = 'Organizer blocked.';
        } elseif (isset($_POST['unblock'])) {
            $block = '';
            $up = $conn->prepare('UPDATE tbl_organizer SET block = ? WHERE org_id = ?');
            $up->bind_param('si', $block, $org_id);
            $up->execute(); $up->close();
            // Keep moderation actions fast: unblocking is a DB-only action.
            $flash = 'Organizer unblocked.';
        } elseif (isset($_POST['delete'])) {
            $del = $conn->prepare('DELETE FROM tbl_organizer WHERE org_id = ?');
            $del->bind_param('i', $org_id);
            $del->execute(); $del->close();
            $flash = 'Organizer deleted.';
        }
    }
}

$org_columns = 'org_id, user_name, company_name, email, mobile_no, experience, city, state, created_at, address';
$present = $conn->query("SELECT $org_columns FROM tbl_organizer WHERE approve = 'Approve' AND (block = '' OR block IS NULL) ORDER BY org_id DESC");
$requests = $conn->query("SELECT $org_columns FROM tbl_organizer WHERE (approve = '' OR approve IS NULL OR approve = ' ') AND (block = '' OR block IS NULL) AND v_status = '1' ORDER BY org_id DESC");
$blocked = $conn->query("SELECT $org_columns FROM tbl_organizer WHERE block = 'block' ORDER BY org_id DESC");

$c_present = $present ? $present->num_rows : 0;
$c_req = $requests ? $requests->num_rows : 0;
$c_blk = $blocked ? $blocked->num_rows : 0;

function render_org_card(array $row, string $mode): void {
    $id = (int)$row['org_id'];
    ?>
    <div class="card entity-card" data-search-item="orgs">
      <div class="card-body">
        <div class="title">
          <div>
            <h3><?= e($row['company_name'] ?: $row['user_name']) ?></h3>
            <div style="color:var(--text-muted);font-size:.85rem;margin-top:2px"><?= e($row['user_name']) ?> · ID #<?= $id ?></div>
          </div>
          <?php if ($mode === 'present'): ?>
            <span class="pill green">Active</span>
          <?php elseif ($mode === 'request'): ?>
            <span class="pill">Pending</span>
          <?php else: ?>
            <span class="pill red">Blocked</span>
          <?php endif; ?>
        </div>
        <div class="kv">
          <div class="k">Email</div><div><?= e($row['email']) ?></div>
          <div class="k">Mobile</div><div><?= e($row['mobile_no']) ?></div>
          <div class="k">Experience</div><div><?= e($row['experience'] ?: '—') ?></div>
          <div class="k">City</div><div><?= e(trim(($row['city'] ?? '') . ' ' . ($row['state'] ?? '')) ?: '—') ?></div>
          <div class="k">Joined</div><div><?= e((string)($row['created_at'] ?? '—')) ?></div>
          <div class="k">Address</div><div><?= e($row['address'] ?: '—') ?></div>
        </div>
      </div>
      <div class="card-footer">
        <form method="post" style="display:flex;gap:8px;flex-wrap:wrap;width:100%">
          <input type="hidden" name="org_id" value="<?= $id ?>">
          <?php if ($mode === 'request'): ?>
            <button class="btn btn-success btn-sm" name="approve" value="1" type="submit">Approve</button>
            <button class="btn btn-danger btn-sm" name="reject" value="1" type="submit" onclick="return confirm('Reject and remove this organizer?')">Reject</button>
          <?php elseif ($mode === 'present'): ?>
            <button class="btn btn-sm" name="block" value="1" type="submit">Block</button>
            <button class="btn btn-danger btn-sm" name="delete" value="1" type="submit" onclick="return confirm('Delete this organizer permanently?')">Delete</button>
          <?php else: ?>
            <button class="btn btn-success btn-sm" name="unblock" value="1" type="submit">Unblock</button>
            <button class="btn btn-danger btn-sm" name="delete" value="1" type="submit" onclick="return confirm('Delete this organizer permanently?')">Delete</button>
          <?php endif; ?>
        </form>
      </div>
    </div>
    <?php
}

$admin_page = 'organizers';
$admin_title = 'Organizers';
$admin_subtitle = 'Approve requests, manage active partners, and block abuse';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>

<div class="toolbar">
  <div class="tabs">
    <button class="tab-btn active" type="button" data-tab-group="org" data-tab="present">Present <span class="badge soft"><?= (int)$c_present ?></span></button>
    <button class="tab-btn" type="button" data-tab-group="org" data-tab="request">Request <span class="badge"><?= (int)$c_req ?></span></button>
    <button class="tab-btn" type="button" data-tab-group="org" data-tab="block">Block <span class="badge gray"><?= (int)$c_blk ?></span></button>
  </div>
  <div class="search">
    <span class="sico">⌕</span>
    <input type="search" placeholder="Search organizers..." data-search-input="orgs">
  </div>
</div>

<div class="tab-panel active" data-tab-panel-group="org" data-tab-panel="present">
  <div class="grid-auto">
    <?php if ($present && $present->num_rows > 0): while ($row = $present->fetch_assoc()) render_org_card($row, 'present'); else: ?>
      <div class="card"><div class="empty"><strong>No active organizers</strong>Approved organizers appear here.</div></div>
    <?php endif; ?>
  </div>
</div>

<div class="tab-panel" data-tab-panel-group="org" data-tab-panel="request">
  <div class="grid-auto">
    <?php if ($requests && $requests->num_rows > 0): while ($row = $requests->fetch_assoc()) render_org_card($row, 'request'); else: ?>
      <div class="card"><div class="empty"><strong>No requests</strong>Email-verified organizers waiting for approval show here.</div></div>
    <?php endif; ?>
  </div>
</div>

<div class="tab-panel" data-tab-panel-group="org" data-tab-panel="block">
  <div class="grid-auto">
    <?php if ($blocked && $blocked->num_rows > 0): while ($row = $blocked->fetch_assoc()) render_org_card($row, 'block'); else: ?>
      <div class="card"><div class="empty"><strong>Block list empty</strong>Blocked organizers show here.</div></div>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
