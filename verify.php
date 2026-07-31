<?php
require_once __DIR__ . '/includes/bootstrap.php';

$message = '';
$type = 'error';

if (isset($_GET['code'])) {
    $code = trim((string)$_GET['code']);
    if ($code === '' || strlen($code) > 64) {
        $message = 'Invalid verification link.';
    } else {
        $stmt = $conn->prepare("SELECT cust_id, user_name, email FROM tbl_customer WHERE verification_code = ? AND status = '0' LIMIT 1");
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row) {
            $up = $conn->prepare("UPDATE tbl_customer SET status = '1' WHERE cust_id = ?");
            $cid = (int)$row['cust_id'];
            $up->bind_param('i', $cid);
            $up->execute();
            $up->close();

            ems_admin_notify('New customer verified', '<p>Customer verified: ' . e($row['user_name']) . ' (' . e($row['email']) . ')</p>');

            $message = 'Email verified successfully. You can now login.';
            $type = 'success';
        } else {
            $message = 'Invalid or already-used verification link.';
        }
    }
} else {
    $message = 'Missing verification code.';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Verification</title>
  <link rel="stylesheet" href="assets/auth.css">
</head>
<body class="auth-page">
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="auth-brand">
        <div class="logo-mark">PE</div>
        <h1>Email verification</h1>
      </div>
      <div class="auth-alert <?= $type === 'success' ? 'success' : 'error' ?>"><?= e($message) ?></div>
      <div class="auth-links"><a href="Customers/login.php">Go to login</a></div>
    </div>
  </div>
</body>
</html>
