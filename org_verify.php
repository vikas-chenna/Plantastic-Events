<?php
require_once __DIR__ . '/includes/bootstrap.php';

$message = '';
$type = 'error';

if (isset($_GET['code'])) {
    $code = trim((string)$_GET['code']);
    if ($code === '' || strlen($code) > 64) {
        $message = 'Invalid verification link.';
    } else {
        $stmt = $conn->prepare("SELECT org_id, user_name, email FROM tbl_organizer WHERE verification_code = ? AND v_status = '0' LIMIT 1");
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row) {
            $up = $conn->prepare("UPDATE tbl_organizer SET v_status = '1' WHERE org_id = ?");
            $oid = (int)$row['org_id'];
            $up->bind_param('i', $oid);
            $up->execute();
            $up->close();

            ems_admin_notify(
                'Organizer approval needed',
                '<p>Organizer verified email and awaits approval:</p>'
                . '<p>' . e($row['user_name']) . ' (' . e($row['email']) . ')</p>'
                . '<p><a href="' . e(app_url('Admin/project-Organizers.php')) . '">Open admin organizers</a></p>'
            );

            $message = 'Email verified. Please wait for admin approval before logging in.';
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
  <title>Organizer Email Verification</title>
  <link rel="stylesheet" href="assets/auth.css">
</head>
<body class="auth-page">
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="auth-brand">
        <div class="logo-mark">PE</div>
        <h1>Organizer verification</h1>
      </div>
      <div class="auth-alert <?= $type === 'success' ? 'success' : 'error' ?>"><?= e($message) ?></div>
      <div class="auth-links"><a href="Organizers/login.php">Go to organizer login</a></div>
    </div>
  </div>
</body>
</html>
