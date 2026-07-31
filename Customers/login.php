<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (!empty($_SESSION['customer'])) {
  $back = get_int('org_id1');
  if ($back > 0) {
    redirect('project-single.php?display_org=' . $back);
  }
  redirect('index-3.php');
}
// if (!empty($_SESSION['organizer'])) {
//     redirect('../Organizers/index.php');
// }

$error = '';
$activeTab = 'customer';
$orgRedirect = get_int('org_id1');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  csrf_verify();
  $role = post_string('userType', 20) ?: 'customer';
  $activeTab = $role === 'organizer' ? 'organizer' : 'customer';

  if ($activeTab === 'customer') {
    $email = post_string('ccemail', 150);
    $password = (string) ($_POST['ccpassword'] ?? '');

    if ($email === '' || $password === '') {
      $error = 'Please enter email and password.';
    } else {
      $stmt = $conn->prepare('SELECT cust_id, user_name, email, password, status FROM tbl_customer WHERE email = ? LIMIT 1');
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $row = $stmt->get_result()->fetch_assoc();
      $stmt->close();

      if (
        !$row || !ems_verify_password(
          $password,
          (string) $row['password'],
          $conn,
          'UPDATE tbl_customer SET password = ? WHERE cust_id = ?',
          'i',
          (int) $row['cust_id']
        )
      ) {
        $error = 'Email or password is incorrect.';
      } elseif ((string) $row['status'] !== '1') {
        $error = 'Please verify your email before logging in.';
      } else {
        session_regenerate_id(true);
        $_SESSION['customer'] = (int) $row['cust_id'];
        $_SESSION['customer_name'] = $row['user_name'];
        $dest = get_int('org_id1');
        if ($dest > 0) {
          redirect('project-single.php?display_org=' . $dest);
        }
        redirect('index-3.php');
      }
    }
  } else {
    $email = post_string('ggemail', 150);
    $password = (string) ($_POST['ggpassword'] ?? '');

    if ($email === '' || $password === '') {
      $error = 'Please enter email and password.';
    } else {
      $stmt = $conn->prepare('SELECT org_id, user_name, email, password, approve, block, v_status FROM tbl_organizer WHERE email = ? LIMIT 1');
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $row = $stmt->get_result()->fetch_assoc();
      $stmt->close();

      if (
        !$row || !ems_verify_password(
          $password,
          (string) $row['password'],
          $conn,
          'UPDATE tbl_organizer SET password = ? WHERE org_id = ?',
          'i',
          (int) $row['org_id']
        )
      ) {
        $error = 'Email or password is incorrect.';
      } elseif ((string) $row['v_status'] !== '1') {
        $error = 'Please verify your organizer email first.';
      } elseif (strcasecmp((string) $row['approve'], 'Approve') !== 0) {
        $error = 'Your organizer account is waiting for admin approval.';
      } elseif (strcasecmp((string) $row['block'], 'block') === 0) {
        $error = 'Your organizer account is blocked.';
      } else {
        session_regenerate_id(true);
        $_SESSION['organizer'] = (int) $row['org_id'];
        $_SESSION['organizer_name'] = $row['user_name'];
        redirect('../Organizers/index.php');
      }
    }
  }
}

$flash = flash_get();
$action = $orgRedirect > 0 ? ('login.php?org_id1=' . $orgRedirect) : 'login.php';
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Plantastic Events</title>
  <link rel="stylesheet" href="../assets/auth.css">
  <link rel="stylesheet" href="assets/css/customer-auth-gold.css">
</head>

<body class="auth-page">
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="auth-brand">
        <div class="logo-mark">PE</div>
        <h1>Welcome back</h1>
        <p>Login to book or manage events</p>
      </div>

      <?php if ($flash): ?>
        <div
          class="auth-alert <?= e($flash['type'] === 'error' ? 'error' : ($flash['type'] === 'info' ? 'info' : 'success')) ?>">
          <?= e($flash['message']) ?>
        </div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="auth-alert error"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="<?= e($action) ?>" id="loginForm">
        <?= csrf_field() ?>
        <div class="auth-tabs">
          <input type="radio" name="userType" id="tabCustomer" value="customer" <?= $activeTab === 'customer' ? 'checked' : '' ?> onchange="toggleRole()">
          <label for="tabCustomer">Customer</label>
          <input type="radio" name="userType" id="tabOrganizer" value="organizer" <?= $activeTab === 'organizer' ? 'checked' : '' ?> onchange="toggleRole()">
          <label for="tabOrganizer">Organizer</label>
        </div>

        <div id="customerFields">
          <div class="form-group">
            <label for="ccemail">Customer email</label>
            <input type="email" id="ccemail" name="ccemail" placeholder="you@example.com">
          </div>
          <div class="form-group">
            <label for="ccpassword">Password</label>
            <input type="password" id="ccpassword" name="ccpassword" placeholder="Enter password">
          </div>
        </div>

        <div id="organizerFields" style="display:none">
          <div class="form-group">
            <label for="ggemail">Organizer email</label>
            <input type="email" id="ggemail" name="ggemail" placeholder="organizer@example.com">
          </div>
          <div class="form-group">
            <label for="ggpassword">Password</label>
            <input type="password" id="ggpassword" name="ggpassword" placeholder="Enter password">
          </div>
        </div>

        <button type="submit" name="login" value="1" class="btn-auth">Login Now</button>
      </form>

      <div class="auth-links">
        <a href="forgot-password.php">Forgot password?</a><br>
        New here? <a href="registration.php">Create account</a><br>
        Organizer portal: <a href="../Organizers/login.php">Organizer login</a> ·
        <a href="../Organizers/register.php">Register</a><br>
        <a href="index-3.php">← Home</a>
      </div>
    </div>
  </div>
  <script>
    function toggleRole() {
      var org = document.getElementById('tabOrganizer').checked;
      document.getElementById('customerFields').style.display = org ? 'none' : 'block';
      document.getElementById('organizerFields').style.display = org ? 'block' : 'none';
      document.getElementById('ccemail').required = !org;
      document.getElementById('ccpassword').required = !org;
      document.getElementById('ggemail').required = org;
      document.getElementById('ggpassword').required = org;
    }
    toggleRole();
  </script>
</body>

</html>