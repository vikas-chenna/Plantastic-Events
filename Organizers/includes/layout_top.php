<?php
if (!isset($org_page)) $org_page = '';
if (!isset($org_title)) $org_title = 'Organizer';
if (!isset($org_subtitle)) $org_subtitle = 'Manage your event services';

function org_nav_active(string $key, string $page): string {
    return $key === $page ? 'active' : '';
}

$org_id = (int)($_SESSION['organizer'] ?? 0);
$org_name = (string)($_SESSION['organizer_name'] ?? 'Organizer');

// pending bookings badge
$__pending_bookings = 0;
if (isset($conn) && $org_id > 0) {
    $r = $conn->query("SELECT COUNT(*) AS total FROM tbl_booking WHERE org_id = $org_id AND (status = '' OR status IS NULL)");
    if ($r) $__pending_bookings = (int)$r->fetch_assoc()['total'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($org_title) ?> | Plantastic Organizer</title>
  <link rel="stylesheet" href="assets/css/org-theme.css">
  <script>(function(){try{var t=localStorage.getItem('ems_org_theme');if(t==='dark')document.documentElement.classList.add('dark');}catch(e){}})();</script>
</head>
<body class="org-app">
<div class="sidebar-backdrop" data-sidebar-close></div>
<div class="app-shell">
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-mark">PE</div>
      <div class="brand-text">
        <strong>Plantastic</strong>
        <span>Organizer Studio</span>
      </div>
    </div>

    <div class="nav-section">Workspace</div>
    <nav class="side-nav">
      <a class="<?= org_nav_active('dashboard', $org_page) ?>" href="index.php"><span class="ico">⌂</span> Dashboard</a>
      <a class="<?= org_nav_active('events', $org_page) ?>" href="events.php"><span class="ico">🎉</span> My Events</a>
      <a class="<?= org_nav_active('bookings', $org_page) ?>" href="events-bookings.php">
        <span class="ico">📅</span> Bookings
        <?php if ($__pending_bookings > 0): ?><span class="badge" style="margin-left:auto"><?= (int)$__pending_bookings ?></span><?php endif; ?>
      </a>
      <a class="<?= org_nav_active('ratings', $org_page) ?>" href="feedbacks-ratings.php"><span class="ico">★</span> Ratings</a>

      <div class="nav-section">Communication</div>
      <a class="<?= org_nav_active('ping_admin', $org_page) ?>" href="ping-admin.php"><span class="ico">🛡️</span> Message Admin</a>
      <a class="<?= org_nav_active('ping_customers', $org_page) ?>" href="ping-customers.php"><span class="ico">💬</span> Message Customers</a>

      <div class="nav-section">Account</div>
      <a class="<?= org_nav_active('profile', $org_page) ?>" href="users-profile.php"><span class="ico">👤</span> Profile</a>
    </nav>

    <div class="sidebar-foot">
      <div class="meta">Signed in as <strong><?= e($org_name) ?></strong></div>
      <a class="btn btn-ghost" style="color:#fff;border-color:rgba(255,255,255,.12)" href="logout.php">Logout</a>
    </div>
  </aside>

  <div class="main-wrap">
    <header class="topbar">
      <div class="topbar-left">
        <button class="menu-btn" type="button" data-sidebar-open aria-label="Open menu">☰</button>
        <div class="page-title">
          <h1><?= e($org_title) ?></h1>
          <p><?= e($org_subtitle) ?></p>
        </div>
      </div>
      <div class="topbar-actions">
        <button class="icon-btn" type="button" data-theme-toggle title="Toggle theme">◐</button>
        <a class="btn" href="events-bookings.php">Bookings<?php if ($__pending_bookings): ?> <span class="badge"><?= (int)$__pending_bookings ?></span><?php endif; ?></a>
        <a class="btn btn-primary" href="events.php">Add event</a>
      </div>
    </header>
    <main class="content">
