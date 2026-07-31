<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_organizer();
$org_id = (int)$_SESSION['organizer'];

$flash = '';
$error = '';

$stmt = $conn->prepare('SELECT * FROM tbl_organizer WHERE org_id = ? LIMIT 1');
$stmt->bind_param('i', $org_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$user) {
    redirect('logout.php');
}
$_SESSION['organizer_name'] = $user['user_name'];

$base64Image = '';
if (!empty($user['profile_pic'])) {
    $base64Image = 'data:image/jpeg;base64,' . base64_encode($user['profile_pic']);
}

if (isset($_POST['edit_profile'])) {
    $uname = post_string('uname', 80);
    $mobile = post_string('mobile', 15);
    $email = post_string('email', 150);
    $gender = post_string('gender', 20);
    $cname = post_string('cname', 100);
    $experience = post_string('experience', 50);
    $address = post_string('address', 250);
    $city = post_string('city', 50);
    $state = post_string('state', 50);
    $pincode = post_string('pincode', 10);
    $country = post_string('country', 50);
    $instagram = post_string('instagram', 250);
    $twitter = post_string('twitter', 250);
    $facebook = post_string('facebook', 250);
    $linkedin = post_string('linkedin', 250);

    if ($uname === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Valid name and email are required.';
    } else {
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['profile_pic']['tmp_name'];
            $size = (int)$_FILES['profile_pic']['size'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($tmp) ?: '';
            $okMime = in_array($mime, ['image/jpeg','image/png','image/webp','image/gif'], true);
            if ($okMime && $size > 0 && $size <= 2 * 1024 * 1024) {
                $blob = file_get_contents($tmp);
                $up = $conn->prepare('UPDATE tbl_organizer SET profile_pic = ? WHERE org_id = ?');
                $up->bind_param('si', $blob, $org_id);
                $up->execute(); $up->close();
            }
        }

        $up = $conn->prepare('UPDATE tbl_organizer SET user_name=?, mobile_no=?, email=?, gender=?, company_name=?, experience=?, address=?, city=?, state=?, pincode=?, country=?, insta_profile=?, twitter_profile=?, facebook_profile=?, linkedin_profile=? WHERE org_id=?');
        $up->bind_param('sssssssssssssssi', $uname, $mobile, $email, $gender, $cname, $experience, $address, $city, $state, $pincode, $country, $instagram, $twitter, $facebook, $linkedin, $org_id);
        if ($up->execute()) {
            $flash = 'Profile updated successfully.';
            $_SESSION['organizer_name'] = $uname;
            // refresh
            $stmt = $conn->prepare('SELECT * FROM tbl_organizer WHERE org_id = ? LIMIT 1');
            $stmt->bind_param('i', $org_id);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!empty($user['profile_pic'])) {
                $base64Image = 'data:image/jpeg;base64,' . base64_encode($user['profile_pic']);
            }
        } else {
            $error = 'Failed to update profile.';
        }
        $up->close();
    }
}

if (isset($_POST['change_pass'])) {
    $password = (string)($_POST['password'] ?? '');
    $newpassword = (string)($_POST['newpassword'] ?? '');
    $renewpassword = (string)($_POST['renewpassword'] ?? '');
    if ($newpassword === '' || strlen($newpassword) < 6) {
        $error = 'New password must be at least 6 characters.';
    } elseif ($newpassword !== $renewpassword) {
        $error = 'New password and confirm password do not match.';
    } else {
        $stmt = $conn->prepare('SELECT password FROM tbl_organizer WHERE org_id = ? LIMIT 1');
        $stmt->bind_param('i', $org_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($row && ems_verify_password($password, (string)$row['password'])) {
            $hash = ems_hash_password($newpassword);
            $up = $conn->prepare('UPDATE tbl_organizer SET password = ? WHERE org_id = ?');
            $up->bind_param('si', $hash, $org_id);
            $up->execute(); $up->close();
            $flash = 'Password changed successfully.';
        } else {
            $error = 'Current password is incorrect.';
        }
    }
}

$org_page = 'profile';
$org_title = 'Profile';
$org_subtitle = 'Update your public organizer details and security';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>

<div class="grid-2">
  <div class="card">
    <div class="card-body" style="text-align:center">
      <div style="width:120px;height:120px;border-radius:28px;margin:0 auto 12px;overflow:hidden;border:1px solid var(--border);background:var(--bg-muted);display:grid;place-items:center">
        <?php if ($base64Image): ?>
          <img src="<?= e($base64Image) ?>" alt="Profile" style="width:100%;height:100%;object-fit:cover">
        <?php else: ?>
          <span style="font-size:2rem;font-weight:900;color:var(--brand)">PE</span>
        <?php endif; ?>
      </div>
      <h2 style="margin:0"><?= e($user['user_name']) ?></h2>
      <div style="color:var(--text-muted)"><?= e($user['company_name'] ?: 'Organizer') ?></div>
      <div style="margin-top:10px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap">
        <span class="pill green"><?= e($user['approve'] ?: 'Pending') ?></span>
        <?php if (!empty($user['block'])): ?><span class="pill red">Blocked</span><?php endif; ?>
        <span class="pill"><?= ((string)$user['v_status'] === '1') ? 'Email verified' : 'Email pending' ?></span>
      </div>
      <div class="kv" style="text-align:left;margin-top:16px">
        <div class="k">Email</div><div><?= e($user['email']) ?></div>
        <div class="k">Mobile</div><div><?= e($user['mobile_no']) ?></div>
        <div class="k">City</div><div><?= e($user['city']) ?></div>
        <div class="k">Experience</div><div><?= e($user['experience'] ?: '—') ?></div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h2>Edit profile</h2></div>
    <div class="card-body">
      <form method="post" enctype="multipart/form-data" class="form-grid">
        <div class="form-row">
          <label class="field">Full name<input type="text" name="uname" value="<?= e($user['user_name']) ?>" required></label>
          <label class="field">Company<input type="text" name="cname" value="<?= e($user['company_name']) ?>"></label>
        </div>
        <div class="form-row">
          <label class="field">Email<input type="email" name="email" value="<?= e($user['email']) ?>" required></label>
          <label class="field">Mobile<input type="text" name="mobile" value="<?= e($user['mobile_no']) ?>"></label>
        </div>
        <div class="form-row">
          <label class="field">Gender
            <select name="gender">
              <?php foreach (['male','female','other'] as $g): ?>
                <option value="<?= $g ?>" <?= strtolower((string)$user['gender']) === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label class="field">Experience<input type="text" name="experience" value="<?= e($user['experience']) ?>"></label>
        </div>
        <label class="field">Address<input type="text" name="address" value="<?= e($user['address']) ?>"></label>
        <div class="form-row">
          <label class="field">City<input type="text" name="city" value="<?= e($user['city']) ?>"></label>
          <label class="field">State<input type="text" name="state" value="<?= e($user['state']) ?>"></label>
        </div>
        <div class="form-row">
          <label class="field">Pincode<input type="text" name="pincode" value="<?= e($user['pincode']) ?>"></label>
          <label class="field">Country<input type="text" name="country" value="<?= e($user['country']) ?>"></label>
        </div>
        <div class="form-row">
          <label class="field">Instagram<input type="text" name="instagram" value="<?= e($user['insta_profile']) ?>"></label>
          <label class="field">Twitter<input type="text" name="twitter" value="<?= e($user['twitter_profile']) ?>"></label>
        </div>
        <div class="form-row">
          <label class="field">Facebook<input type="text" name="facebook" value="<?= e($user['facebook_profile']) ?>"></label>
          <label class="field">LinkedIn<input type="text" name="linkedin" value="<?= e($user['linkedin_profile']) ?>"></label>
        </div>
        <label class="field">Profile photo (max 2MB)
          <input type="file" name="profile_pic" accept="image/*">
        </label>
        <button class="btn btn-primary" type="submit" name="edit_profile" value="1">Save profile</button>
      </form>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2>Change password</h2></div>
  <div class="card-body">
    <form method="post" class="form-grid" style="max-width:560px">
      <label class="field">Current password<input type="password" name="password" required></label>
      <div class="form-row">
        <label class="field">New password<input type="password" name="newpassword" minlength="6" required></label>
        <label class="field">Confirm new password<input type="password" name="renewpassword" minlength="6" required></label>
      </div>
      <button class="btn btn-accent" type="submit" name="change_pass" value="1">Update password</button>
    </form>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
