<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (!empty($_SESSION['organizer'])) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    csrf_verify();
    $email = post_string('email', 150);
    $password = (string)($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Please enter email and password.';
    } else {
        $stmt = $conn->prepare('SELECT org_id, user_name, email, password, status, approve, block, v_status FROM tbl_organizer WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row || !ems_verify_password(
            $password,
            (string)$row['password'],
            $conn,
            'UPDATE tbl_organizer SET password = ? WHERE org_id = ?',
            'i',
            (int)$row['org_id']
        )) {
            $error = 'Email or password is incorrect.';
        } elseif ((string)$row['v_status'] !== '1') {
            $error = 'Please verify your email before logging in.';
        } elseif (strcasecmp((string)$row['approve'], 'Approve') !== 0) {
            $error = 'Your account is pending admin approval.';
        } elseif (strcasecmp((string)$row['block'], 'block') === 0) {
            $error = 'Your account has been blocked. Contact admin.';
        } else {
            session_regenerate_id(true);
            $_SESSION['organizer'] = (int)$row['org_id'];
            $_SESSION['organizer_name'] = $row['user_name'];
            redirect('index.php');
        }
    }
}

$flash = flash_get();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organizer Login | Plantastic Events</title>
  <link rel="stylesheet" href="assets/css/org-theme.css">
  <script>(function(){try{var t=localStorage.getItem('ems_org_theme');if(t==='dark')document.documentElement.classList.add('dark');}catch(e){}})();</script>
  <style>
    body.org-login{min-height:100vh;margin:0;display:grid;place-items:center;padding:24px 12px;font-family:var(--font);color:var(--text);background:radial-gradient(900px 500px at 10% -10%, rgba(139,92,246,.20), transparent 60%), radial-gradient(800px 420px at 100% 0%, rgba(236,72,153,.14), transparent 55%), var(--bg);}
    .login-shell{width:min(920px,100%);display:grid;grid-template-columns:1.05fr .95fr;border-radius:24px;overflow:hidden;border:1px solid var(--border);box-shadow:var(--shadow);background:var(--bg-elevated);}
    .login-art{padding:36px 28px;background:linear-gradient(160deg, rgba(7,11,22,.94), rgba(13,20,36,.78)), linear-gradient(135deg,#8b5cf6,#a855f7 55%,#ec4899);color:#f8fafc;display:flex;flex-direction:column;justify-content:space-between;gap:24px;}
    .login-art h2{margin:12px 0 8px;font-size:1.7rem;letter-spacing:-.03em;}
    .login-art p{margin:0;opacity:.92;line-height:1.5;}
    .login-form-wrap{padding:34px 28px;}
    .mark{width:42px;height:42px;border-radius:14px;display:grid;place-items:center;background:linear-gradient(135deg,#8b5cf6,#a855f7 55%,#ec4899);color:#fff;font-weight:900;}
    @media (max-width:800px){.login-shell{grid-template-columns:1fr}}
  </style>
</head>
<body class="org-login">
  <div class="login-shell">
    <section class="login-art">
      <div>
        <div style="display:flex;align-items:center;gap:10px"><div class="mark">PE</div><strong>Plantastic Events</strong></div>
        <h2>Organizer Studio</h2>
        <p>Sell your event packages, manage bookings, and chat with customers — all in one festive workspace.</p>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <span class="pill" style="background:rgba(255,255,255,.14);color:#fff">Bookings inbox</span>
        <span class="pill" style="background:rgba(255,255,255,.14);color:#fff">Service catalog</span>
        <span class="pill" style="background:rgba(255,255,255,.14);color:#fff">Light / Dark</span>
      </div>
    </section>
    <section class="login-form-wrap">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:8px">
        <div>
          <h1 style="margin:0 0 6px;font-size:1.35rem">Organizer login</h1>
          <div style="color:var(--text-muted);font-size:.92rem">Access your dashboard</div>
        </div>
        <button class="icon-btn" type="button" data-theme-toggle title="Toggle theme">◐</button>
      </div>

      <?php if ($flash): ?><div class="alert <?= $flash['type']==='error'?'err':'ok' ?>" style="margin-bottom:12px"><?= e($flash['message']) ?></div><?php endif; ?>
      <?php if ($error): ?><div class="alert err" style="margin-bottom:12px"><?= e($error) ?></div><?php endif; ?>

      <form method="POST" class="form-grid">
        <?= csrf_field() ?>
        <label class="field">Email<input type="email" name="email" required placeholder="you@example.com"></label>
        <label class="field">Password<input type="password" name="password" required placeholder="••••••••"></label>
        <button class="btn btn-primary btn-block" type="submit" name="login" value="1">Sign in</button>
      </form>
      <div style="margin-top:16px;text-align:center;display:grid;gap:8px">
        <a href="forgot-password.php">Forgot password?</a>
        <div>New organizer? <a href="register.php"><strong>Create account</strong></a></div>
        <a class="btn btn-ghost" href="../Customers/index-3.php">← Back to website</a>
      </div>
    </section>
  </div>
  <script src="assets/js/org-ui.js"></script>
</body>
</html>
