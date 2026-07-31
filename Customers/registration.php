<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (!empty($_SESSION['customer'])) {
  redirect('index-3.php');
}

$error = '';
$success = '';
$activeTab = 'customer';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  csrf_verify();
  $reg = post_string('userType', 20) ?: 'customer';
  $activeTab = $reg === 'organizer' ? 'organizer' : 'customer';

  if ($reg === 'customer') {
    $name = post_string('cuname', 80);
    $phone = post_string('cmobile', 15);
    $email = post_string('cemail', 150);
    $gender = post_string('cgender', 20);
    $address = post_string('caddress', 250);
    $city = post_string('ccity', 50);
    $state = post_string('cstate', 50);
    $pincode = post_string('cpincode', 10);
    $password = (string) ($_POST['ccpassword'] ?? '');
    $confirm = (string) ($_POST['cpassword2'] ?? '');

    if ($name === '' || $email === '' || $phone === '' || $password === '') {
      $error = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Enter a valid email.';
    } elseif (strlen($password) < 6) {
      $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
      $error = 'Passwords do not match.';
    } else {
      $check = $conn->prepare('SELECT cust_id FROM tbl_customer WHERE email = ? LIMIT 1');
      $check->bind_param('s', $email);
      $check->execute();
      $exists = $check->get_result()->num_rows > 0;
      $check->close();

      if ($exists) {
        $error = 'A customer with this email already exists.';
      } else {
        $hash = ems_hash_password($password);
        $code = random_token(16);
        $current_date = date('Y-m-d');
        $status = '0';
        $empty = '';

        $sql = 'INSERT INTO tbl_customer
                    (user_name, contact, email, gender, address2, city, state, pincode, password, created_at, verification_code, status, profile_pic, insta_profile, twitter_profile, facebook_profile, linkedin_profile)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?, NULL,?,?,?,?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
          'ssssssssssssssss',
          $name,
          $phone,
          $email,
          $gender,
          $address,
          $city,
          $state,
          $pincode,
          $hash,
          $current_date,
          $code,
          $status,
          $empty,
          $empty,
          $empty,
          $empty
        );

        if ($stmt->execute()) {
          $verifyUrl = app_url('verify.php?code=' . urlencode($code));
          $body = '<h2>Verify your email</h2><p>Hi ' . e($name) . ',</p>'
            . '<p><a href="' . e($verifyUrl) . '">Click here to verify your account</a></p>';
          [$sent, $msg] = ems_send_mail($email, 'Customer Email Verification', $body);
          ems_admin_notify('New customer registered', '<p>' . e($name) . ' (' . e($email) . ')</p>');
          if ($sent) {
            $success = 'Registration successful. Please check your email to verify your account.';
          } else {
            $success = 'Account created. Email not sent (' . $msg . '). Verification link: ' . $verifyUrl;
          }
        } else {
          $error = 'Registration failed. Please try again.';
        }
        $stmt->close();
      }
    }
  } else {
    // Organizer registration (same as Organizers/register.php)
    $name = post_string('uname', 80);
    $phone = post_string('mobile', 15);
    $email = post_string('email', 150);
    $gender = post_string('gender', 20);
    $address = post_string('address', 250);
    $city = post_string('city', 50);
    $state = post_string('state', 50);
    $pincode = post_string('pincode', 10);
    $country = post_string('country', 50);
    $cname = post_string('cname', 100);
    $establish = post_string('establish', 50);
    $experience = post_string('experience', 50);
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['cpassword'] ?? '');

    if ($name === '' || $email === '' || $phone === '' || $password === '') {
      $error = 'Please fill all required organizer fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Enter a valid email.';
    } elseif (strlen($password) < 6) {
      $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
      $error = 'Passwords do not match.';
    } else {
      $check = $conn->prepare('SELECT org_id FROM tbl_organizer WHERE email = ? LIMIT 1');
      $check->bind_param('s', $email);
      $check->execute();
      $exists = $check->get_result()->num_rows > 0;
      $check->close();

      if ($exists) {
        $error = 'An organizer with this email already exists.';
      } else {
        $hash = ems_hash_password($password);
        $code = random_token(16);
        $current_date = date('Y-m-d');
        $status = $approve = $block = $empty = '';
        $v_status = '0';

        $sql = 'INSERT INTO tbl_organizer
                    (user_name, mobile_no, email, gender, company_name, city, pincode, state, country,
                     since_establish, experience, address, password, created_at, status, approve, block,
                     verification_code, v_status, profile_pic, insta_profile, twitter_profile, facebook_profile, linkedin_profile)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, NULL,?,?,?,?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
          'sssssssssssssssssssssss',
          $name,
          $phone,
          $email,
          $gender,
          $cname,
          $city,
          $pincode,
          $state,
          $country,
          $establish,
          $experience,
          $address,
          $hash,
          $current_date,
          $status,
          $approve,
          $block,
          $code,
          $v_status,
          $empty,
          $empty,
          $empty,
          $empty
        );
        if ($stmt->execute()) {
          $verifyUrl = app_url('org_verify.php?code=' . urlencode($code));
          [$sent, $msg] = ems_send_mail(
            $email,
            'Organizer Email Verification',
            '<p>Verify email: <a href="' . e($verifyUrl) . '">' . e($verifyUrl) . '</a></p><p>Admin approval is required after verification.</p>'
          );
          $success = $sent
            ? 'Organizer registered. Verify email, then wait for admin approval.'
            : 'Organizer saved. Email not sent (' . $msg . '). Link: ' . $verifyUrl;
        } else {
          $error = 'Organizer registration failed.';
        }
        $stmt->close();
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
  <title>Register | Plantastic Events</title>
  <link rel="stylesheet" href="../assets/auth.css">
  <link rel="stylesheet" href="assets/css/customer-auth-gold.css">
</head>

<body class="auth-page">
  <div class="auth-wrap" style="max-width:480px">
    <div class="auth-card">
      <div class="auth-brand">
        <div class="logo-mark">PE</div>
        <h1>Create account</h1>
        <p>Join Plantastic Events</p>
      </div>

      <?php if ($error): ?>
        <div class="auth-alert error"><?= e($error) ?></div><?php endif; ?>
      <?php if ($success): ?>
        <div class="auth-alert success"><?= e($success) ?></div><?php endif; ?>

      <?php if (!$success): ?>
        <form method="POST" action="" id="regForm">
          <?= csrf_field() ?>
          <div class="auth-tabs">
            <input type="radio" name="userType" id="tabCustomer" value="customer" <?= $activeTab === 'customer' ? 'checked' : '' ?> onchange="toggleRole()">
            <label for="tabCustomer">Customer</label>
            <input type="radio" name="userType" id="tabOrganizer" value="organizer" <?= $activeTab === 'organizer' ? 'checked' : '' ?> onchange="toggleRole()">
            <label for="tabOrganizer">Organizer</label>
          </div>

          <div id="customerBox">
            <div class="form-group"><label>Full name *</label><input type="text" name="cuname"></div>
            <div class="auth-grid-2">
              <div class="form-group"><label>Email *</label><input type="email" name="cemail"></div>
              <div class="form-group"><label>Mobile *</label><input type="tel" name="cmobile"></div>
            </div>
            <div class="form-group">
              <label>Gender</label>
              <select name="cgender">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="form-group"><label>Address</label><input type="text" name="caddress"></div>
            <div class="auth-grid-2">
              <div class="form-group"><label>City</label><input type="text" name="ccity"></div>
              <div class="form-group"><label>State</label><input type="text" name="cstate"></div>
            </div>
            <div class="form-group"><label>Pincode</label><input type="text" name="cpincode"></div>
            <div class="auth-grid-2">
              <div class="form-group"><label>Password *</label><input type="password" name="ccpassword" minlength="6">
              </div>
              <div class="form-group"><label>Confirm *</label><input type="password" name="cpassword2" minlength="6">
              </div>
            </div>
          </div>

          <div id="organizerBox" style="display:none">
            <div class="form-group"><label>Full name *</label><input type="text" name="uname"></div>
            <div class="auth-grid-2">
              <div class="form-group"><label>Email *</label><input type="email" name="email"></div>
              <div class="form-group"><label>Mobile *</label><input type="tel" name="mobile"></div>
            </div>
            <div class="auth-grid-2">
              <div class="form-group">
                <label>Gender</label>
                <select name="gender">
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="form-group"><label>Experience</label><input type="text" name="experience"></div>
            </div>
            <div class="form-group"><label>Company</label><input type="text" name="cname"></div>
            <div class="form-group"><label>Since established</label><input type="text" name="establish"></div>
            <div class="form-group"><label>Address</label><input type="text" name="address"></div>
            <div class="auth-grid-2">
              <div class="form-group"><label>City</label><input type="text" name="city"></div>
              <div class="form-group"><label>State</label><input type="text" name="state"></div>
            </div>
            <div class="auth-grid-2">
              <div class="form-group"><label>Pincode</label><input type="text" name="pincode"></div>
              <div class="form-group"><label>Country</label><input type="text" name="country" value="India"></div>
            </div>
            <div class="auth-grid-2">
              <div class="form-group"><label>Password *</label><input type="password" name="password" minlength="6"></div>
              <div class="form-group"><label>Confirm *</label><input type="password" name="cpassword" minlength="6"></div>
            </div>
          </div>

          <button type="submit" name="submit" value="1" class="btn-auth">Register</button>
        </form>
      <?php endif; ?>

      <div class="auth-links">
        Already have an account? <a href="login.php">Login</a>
      </div>
    </div>
  </div>
  <script>
    function toggleRole() {
      var org = document.getElementById('tabOrganizer').checked;
      document.getElementById('customerBox').style.display = org ? 'none' : 'block';
      document.getElementById('organizerBox').style.display = org ? 'block' : 'none';
    }
    toggleRole();
  </script>
</body>

</html>