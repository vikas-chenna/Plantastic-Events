<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (!empty($_SESSION['admin'])) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    csrf_verify();
    $username = post_string('username1', 100);
    $password = (string)($_POST['password1'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        $stmt = $conn->prepare('SELECT user_id, user_name, password FROM tbl_admin WHERE user_name = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        if ($row && ems_verify_password(
            $password,
            (string)$row['password'],
            $conn,
            'UPDATE tbl_admin SET password = ? WHERE user_id = ?',
            'i',
            (int)$row['user_id']
        )) {
            session_regenerate_id(true);
            $_SESSION['admin'] = $row['user_name'];
            $_SESSION['admin_id'] = (int)$row['user_id'];
            redirect('index.php');
        }
        $error = 'Username or password is incorrect.';
    }
}

$flash = flash_get();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Plantastic Events</title>
  <link rel="stylesheet" href="assets/css/admin-theme.css">
  <script>(function(){try{var t=localStorage.getItem('ems_admin_theme');if(t==='dark')document.documentElement.classList.add('dark');}catch(e){}})();</script>
  <style>
    body.admin-login {
      min-height: 100vh;
      margin: 0;
      display: grid;
      place-items: center;
      padding: 24px 12px;
      font-family: var(--font);
      color: var(--text);
      background:
        radial-gradient(900px 500px at 10% -10%, rgba(99,102,241,.24), transparent 60%),
        radial-gradient(800px 420px at 100% 0%, rgba(6,182,212,.18), transparent 55%),
        var(--bg);
    }
    .login-shell {
      width: min(920px, 100%);
      display: grid;
      grid-template-columns: 1.05fr .95fr;
      border-radius: 24px;
      overflow: hidden;
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      background: var(--bg-elevated);
    }
    .login-art {
      padding: 36px 28px;
      background:
        linear-gradient(160deg, rgba(5,9,20,.94), rgba(10,18,38,.82)),
        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 40 40"><circle cx="2" cy="2" r="1.2" fill="%2306b6d4" opacity=".35"/></svg>'),
        linear-gradient(135deg, #111b3a, #4338ca 58%, #0891b2);
      color: #fff7ed;
      display: flex; flex-direction: column; justify-content: space-between; gap: 24px;
    }
    .login-art h2 { margin: 12px 0 8px; font-size: 1.7rem; letter-spacing: -.03em; }
    .login-art p { margin: 0; opacity: .9; line-height: 1.5; }
    .login-form-wrap { padding: 34px 28px; }
    .login-form-wrap h1 { margin: 0 0 6px; font-size: 1.35rem; }
    .login-form-wrap .sub { color: var(--text-muted); margin-bottom: 18px; font-size: .92rem; }
    .brand-row { display:flex; align-items:center; gap:10px; margin-bottom: 8px; }
    .brand-row .mark {
      width: 42px; height: 42px; border-radius: 14px; display:grid; place-items:center;
      background: linear-gradient(135deg,#6366f1,#4f46e5 58%,#06b6d4); color:#fff; font-weight:900;
    }
    @media (max-width: 800px) {
      .login-shell { grid-template-columns: 1fr; }
      .login-art { min-height: 180px; }
    }
  </style>
</head>
<body class="admin-login">
  <div class="login-shell">
    <section class="login-art">
      <div>
        <div class="brand-row">
          <div class="mark">PE</div>
          <strong>Plantastic Events</strong>
        </div>
        <h2>Admin Control Center</h2>
        <p>Approve organizers, support customers, track bookings, and keep every celebration running smoothly.</p>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <span class="pill" style="background:rgba(255,255,255,.14);color:#fff">Organizer approvals</span>
        <span class="pill" style="background:rgba(255,255,255,.14);color:#fff">Live messaging</span>
        <span class="pill" style="background:rgba(255,255,255,.14);color:#fff">Light / Dark</span>
      </div>
    </section>
    <section class="login-form-wrap">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
        <div>
          <h1>Welcome back</h1>
          <div class="sub">Sign in to manage the platform</div>
        </div>
        <button class="icon-btn" type="button" data-theme-toggle title="Toggle theme">◐</button>
      </div>

      <?php if ($flash): ?>
        <div class="alert <?= $flash['type'] === 'error' ? 'err' : 'ok' ?>" style="margin-bottom:12px"><?= e($flash['message']) ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert err" style="margin-bottom:12px"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="" class="form-grid">
        <?= csrf_field() ?>
        <label class="field">Username
          <input type="text" name="username1" placeholder="admin" required autocomplete="username">
        </label>
        <label class="field">Password
          <input type="password" name="password1" placeholder="••••••••" required autocomplete="current-password">
        </label>
        <button class="btn btn-primary btn-block" type="submit" name="login" value="1">Sign in</button>
      </form>
      <div style="margin-top:16px;text-align:center">
        <a class="btn btn-ghost" href="../Customers/index-3.php">← Back to website</a>
      </div>
    </section>
  </div>
  <script src="assets/js/admin-ui.js"></script>
</body>
</html>
