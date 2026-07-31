<?php
/**
 * Shared Admin layout helpers.
 * Usage:
 *   $admin_page = 'dashboard';
 *   $admin_title = 'Dashboard';
 *   $admin_subtitle = '...';
 *   require __DIR__ . '/layout_top.php';
 *   ... content ...
 *   require __DIR__ . '/layout_bottom.php';
 */

if (!isset($admin_page)) {
    $admin_page = '';
}
if (!isset($admin_title)) {
    $admin_title = 'Admin';
}
if (!isset($admin_subtitle)) {
    $admin_subtitle = 'Plantastic Events control center';
}

function admin_nav_active(string $key, string $page): string
{
    return $key === $page ? 'active' : '';
}

// unread notifications count
$__admin_notif_count = 0;
if (function_exists('ems_admin_inbox_path')) {
    $__path = ems_admin_inbox_path();
    if (is_file($__path)) {
        $__items = json_decode(file_get_contents($__path) ?: '[]', true);
        if (is_array($__items)) {
            foreach ($__items as $it) {
                if (empty($it['read'])) {
                    $__admin_notif_count++;
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($admin_title) ?> | Plantastic Admin</title>
  <link rel="stylesheet" href="assets/css/admin-theme.css">
  <script>
    // prevent theme flash
    (function(){try{var t=localStorage.getItem('ems_admin_theme');if(t==='dark')document.documentElement.classList.add('dark');}catch(e){}})();
  </script>
</head>
<body class="admin-app">
<div class="sidebar-backdrop" data-sidebar-close></div>
<div class="app-shell">
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-mark">PE</div>
      <div class="brand-text">
        <strong>Plantastic</strong>
        <span>Admin Console</span>
      </div>
    </div>

    <div class="nav-section">Main</div>
    <nav class="side-nav">
      <a class="<?= admin_nav_active('dashboard', $admin_page) ?>" href="index.php"><span class="ico">⌂</span> Dashboard</a>
      <a class="<?= admin_nav_active('notifications', $admin_page) ?>" href="notifications.php">
        <span class="ico">🔔</span> Notifications
        <?php if ($__admin_notif_count > 0): ?><span class="badge" style="margin-left:auto"><?= (int)$__admin_notif_count ?></span><?php endif; ?>
      </a>

      <div class="nav-section">People</div>
      <a class="<?= admin_nav_active('organizers', $admin_page) ?>" href="project-Organizers.php"><span class="ico">🎪</span> Organizers</a>
      <a class="<?= admin_nav_active('customers', $admin_page) ?>" href="project-Customers.php"><span class="ico">👥</span> Customers</a>

      <div class="nav-section">Communication</div>
      <a class="<?= admin_nav_active('ping_customers', $admin_page) ?>" href="ping-customers.php"><span class="ico">💬</span> Message Customers</a>
      <a class="<?= admin_nav_active('ping_organizers', $admin_page) ?>" href="ping-organizers.php"><span class="ico">📨</span> Message Organizers</a>

      <div class="nav-section">Insights</div>
      <a class="<?= admin_nav_active('feedback', $admin_page) ?>" href="project-Feedback.php"><span class="ico">★</span> Feedback</a>
      <a class="<?= admin_nav_active('reports', $admin_page) ?>" href="project-Reports.php"><span class="ico">⚑</span> Reports</a>
    </nav>

    <div class="sidebar-foot">
      <div class="meta">Signed in as <strong><?= e((string)($_SESSION['admin'] ?? 'admin')) ?></strong></div>
      <a class="btn btn-ghost" style="color:#fff;border-color:rgba(255,255,255,.12)" href="logout.php">Logout</a>
    </div>
  </aside>

  <div class="main-wrap">
    <header class="topbar">
      <div class="topbar-left">
        <button class="menu-btn" type="button" data-sidebar-open aria-label="Open menu">☰</button>
        <div class="page-title">
          <h1><?= e($admin_title) ?></h1>
          <p><?= e($admin_subtitle) ?></p>
        </div>
      </div>
      <div class="topbar-actions">
        <button class="icon-btn" type="button" data-theme-toggle title="Toggle theme">◐</button>
        <a class="btn" href="notifications.php">Alerts<?php if ($__admin_notif_count): ?> <span class="badge"><?= (int)$__admin_notif_count ?></span><?php endif; ?></a>
        <a class="btn btn-primary" href="../Customers/index-3.php" target="_blank">View site</a>
      </div>
    </header>
    <main class="content">
