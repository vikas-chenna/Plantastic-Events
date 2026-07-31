<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$error = '';
$success = '';
$activeTab = 'customer';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_pass'])) {
  csrf_verify();
  $role = post_string('userType', 20) ?: 'customer';
  $activeTab = $role === 'organizer' ? 'organizer' : 'customer';

  if ($activeTab === 'customer') {
    $email = post_string('cemail', 150);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Enter a valid customer email.';
    } else {
      $stmt = $conn->prepare('SELECT cust_id, user_name FROM tbl_customer WHERE email = ? LIMIT 1');
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $row = $stmt->get_result()->fetch_assoc();
      $stmt->close();

      if (!$row) {
        // Do not reveal whether email exists
        $success = 'If that email exists, a temporary password has been sent.';
      } else {
        $temp = (string) random_int(100000, 999999);
        $hash = ems_hash_password($temp);
        $up = $conn->prepare('UPDATE tbl_customer SET password = ? WHERE cust_id = ?');
        $cid = (int) $row['cust_id'];
        $up->bind_param('si', $hash, $cid);
        $up->execute();
        $up->close();

        $body = '<h3>Password reset</h3><p>Hi ' . e($row['user_name']) . ',</p>'
          . '<p>Your temporary password is: <strong>' . e($temp) . '</strong></p>'
          . '<p>Login and change it immediately from your profile.</p>';
        [$sent, $msg] = ems_send_mail($email, 'Customer Password Reset', $body);
        $success = $sent
          ? 'If that email exists, a temporary password has been sent.'
          : 'Password was reset, but email failed (' . $msg . '). Temporary password: ' . $temp . ' (dev only — configure SMTP for production).';
      }
    }
  } else {
    $email = post_string('oemail', 150);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Enter a valid organizer email.';
    } else {
      $stmt = $conn->prepare('SELECT org_id, user_name FROM tbl_organizer WHERE email = ? LIMIT 1');
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $row = $stmt->get_result()->fetch_assoc();
      $stmt->close();

      if (!$row) {
        $success = 'If that email exists, a temporary password has been sent.';
      } else {
        $temp = (string) random_int(100000, 999999);
        $hash = ems_hash_password($temp);
        $up = $conn->prepare('UPDATE tbl_organizer SET password = ? WHERE org_id = ?');
        $oid = (int) $row['org_id'];
        $up->bind_param('si', $hash, $oid);
        $up->execute();
        $up->close();

        $body = '<h3>Password reset</h3><p>Hi ' . e($row['user_name']) . ',</p>'
          . '<p>Your temporary password is: <strong>' . e($temp) . '</strong></p>'
          . '<p>Login and change it from your profile.</p>';
        [$sent, $msg] = ems_send_mail($email, 'Organizer Password Reset', $body);
        $success = $sent
          ? 'If that email exists, a temporary password has been sent.'
          : 'Password was reset, but email failed (' . $msg . '). Temporary password: ' . $temp . ' (dev only).';
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
  <title>Forgot Password | Plantastic Events</title>
  <link rel="stylesheet" href="../assets/auth.css">
  <link rel="stylesheet" href="assets/css/customer-auth-gold.css">
</head>

<body class="auth-page">
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="auth-brand">
        <div class="logo-mark">PE</div>
        <h1>Reset password</h1>
        <p>We will email a temporary password</p>
      </div>

      <?php if ($error): ?>
        <div class="auth-alert error"><?= e($error) ?></div><?php endif; ?>
      <?php if ($success): ?>
        <div class="auth-alert success"><?= e($success) ?></div><?php endif; ?>

      <form method="POST" action="">
        <?= csrf_field() ?>
        <div class="auth-tabs">
          <input type="radio" name="userType" id="tabCustomer" value="customer" <?= $activeTab === 'customer' ? 'checked' : '' ?> onchange="toggleRole()">
          <label for="tabCustomer">Customer</label>
          <input type="radio" name="userType" id="tabOrganizer" value="organizer" <?= $activeTab === 'organizer' ? 'checked' : '' ?> onchange="toggleRole()">
          <label for="tabOrganizer">Organizer</label>
        </div>

        <div id="cBox">
          <div class="form-group">
            <label>Customer email</label>
            <input type="email" name="cemail" id="cemail">
          </div>
        </div>
        <div id="oBox" style="display:none">
          <div class="form-group">
            <label>Organizer email</label>
            <input type="email" name="oemail" id="oemail">
          </div>
        </div>
        <button type="submit" name="forgot_pass" value="1" class="btn-auth">Send reset</button>
      </form>
      <div class="auth-links">
        <a href="login.php">Back to login</a>
      </div>
    </div>
  </div>
  <script>
    function toggleRole() {
      var org = document.getElementById('tabOrganizer').checked;
      document.getElementById('cBox').style.display = org ? 'none' : 'block';
      document.getElementById('oBox').style.display = org ? 'block' : 'none';
    }
    toggleRole();
  </script>
</body>

</html>