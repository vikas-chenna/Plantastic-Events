<?php
if (!isset($cust_page)) $cust_page = '';
if (!isset($cust_title)) $cust_title = 'My Account';
if (!isset($cust_subtitle)) $cust_subtitle = 'Plantastic Events';
if (!isset($cust_wide)) $cust_wide = false;

function cust_nav_active(string $key, string $page): string {
    return $key === $page ? 'active' : '';
}

$is_logged = !empty($_SESSION['customer']);
$cust_name = (string)($_SESSION['customer_name'] ?? 'Customer');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($cust_title) ?> | Plantastic Events</title>
  <link rel="stylesheet" href="assets/css/customer-theme.css">
  <script>(function(){try{var t=localStorage.getItem('ems_customer_theme');if(t==='dark')document.documentElement.classList.add('dark');}catch(e){}})();</script>
</head>
<body class="customer-app">
<div class="sidebar-backdrop" data-sidebar-close></div>
<div class="app-shell" style="<?= $cust_wide ? 'grid-template-columns:1fr' : '' ?>">
  <?php if (!$cust_wide): ?>
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-mark">PE</div>
      <div class="brand-text">
        <strong>Plantastic</strong>
        <span>Customer Hub</span>
      </div>
    </div>
    <div class="nav-section">Account</div>
    <nav class="side-nav">
      <a class="<?= cust_nav_active('profile', $cust_page) ?>" href="profile.php"><span class="ico">👤</span> My Profile</a>
      <a class="<?= cust_nav_active('bookings', $cust_page) ?>" href="profile.php#your-events"><span class="ico">📅</span> My Bookings</a>
      <a class="<?= cust_nav_active('messages', $cust_page) ?>" href="profile.php#messages"><span class="ico">💬</span> Messages</a>
      <div class="nav-section">Explore</div>
      <a href="index-3.php"><span class="ico">⌂</span> Home</a>
      <a href="booking.php"><span class="ico">🎪</span> Browse organizers</a>
      <a href="Birthday.php"><span class="ico">🎂</span> Birthday</a>
      <a href="Ceremony.php"><span class="ico">💍</span> Ceremony</a>
      <a href="contact.php"><span class="ico">☎</span> Contact</a>
    </nav>
    <div class="sidebar-foot">
      <?php if ($is_logged): ?>
        <div class="meta">Hi, <strong><?= e($cust_name) ?></strong></div>
        <a class="btn btn-ghost" style="color:#fff;border-color:rgba(255,255,255,.12)" href="profile.php?logout=1">Logout</a>
      <?php else: ?>
        <a class="btn btn-primary" href="login.php">Login</a>
      <?php endif; ?>
    </div>
  </aside>
  <?php endif; ?>

  <div class="main-wrap">
    <header class="topbar">
      <div class="topbar-left">
        <?php if (!$cust_wide): ?>
          <button class="menu-btn" type="button" data-sidebar-open>☰</button>
        <?php else: ?>
          <div class="brand-mark" style="width:36px;height:36px;border-radius:12px;display:grid;place-items:center;background:linear-gradient(135deg,#e0bd67,#c99a2e 58%,#9b6b12);color:#fff;font-weight:900;font-size:.85rem">PE</div>
        <?php endif; ?>
        <div class="page-title">
          <h1><?= e($cust_title) ?></h1>
          <p><?= e($cust_subtitle) ?></p>
        </div>
      </div>
      <div class="topbar-actions">
        <button class="icon-btn" type="button" data-theme-toggle title="Toggle theme">◐</button>
        <?php if ($cust_wide): ?>
          <nav class="site-topnav">
            <a href="index-3.php">Home</a>
            <a href="booking.php">Organizers</a>
            <a href="Birthday.php">Events</a>
            <?php if ($is_logged): ?>
              <a class="active" href="profile.php">Profile</a>
            <?php else: ?>
              <a class="active" href="login.php">Login</a>
            <?php endif; ?>
          </nav>
        <?php else: ?>
          <a class="btn" href="booking.php">Find organizers</a>
          <a class="btn btn-primary" href="index-3.php">Home</a>
        <?php endif; ?>
      </div>
    </header>
    <main class="content">
