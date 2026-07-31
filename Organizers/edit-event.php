<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_organizer();
$org_id = (int)$_SESSION['organizer'];

$evn_id = get_int('evn_id');
if ($evn_id <= 0) {
    redirect('events.php');
}

$stmt = $conn->prepare('SELECT * FROM tbl_event WHERE evn_id = ? AND org_id = ? LIMIT 1');
$stmt->bind_param('ii', $evn_id, $org_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$event) {
    echo "<script>alert('Event not found'); window.location.href='events.php';</script>";
    exit;
}

$error = '';
$flash = '';
$types = ['Anniversery','Ceremony','Couple_Exclusive','Birthday','Parties','Corporate','Others'];

function ems_read_image_upload_one(array $file): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
    $tmp = $file['tmp_name'];
    $size = (int)($file['size'] ?? 0);
    if ($size <= 0 || $size > 2 * 1024 * 1024) return null;
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp) ?: '';
    if (!in_array($mime, ['image/jpeg','image/png','image/webp','image/gif'], true)) return null;
    $data = file_get_contents($tmp);
    return $data === false ? null : $data;
}

if (isset($_POST['edit'])) {
    $eve_name = post_string('eve_name', 50);
    $event_type = post_string('event_type', 50);
    $hall_name = post_string('hall_name', 50);
    $hall_add = post_string('hall_add', 250);
    $city = post_string('city', 50);
    $pincode = post_string('pincode', 10);
    $state = post_string('state', 50);
    $county = post_string('country', 50);
    $hall_capacity = post_string('hall_capacity', 20);
    $start_date = post_string('start_date', 20);
    $end_date = post_string('end_date', 20);

    if ($eve_name === '' || $event_type === '') {
        $error = 'Event name and type are required.';
    } else {
        $image1 = $event['image1'];
        $image2 = $event['image2'];
        $image3 = $event['image3'];
        $image4 = $event['image4'];

        // optional replacements if user uploads new images[]
        if (!empty($_FILES['images']['name'][0])) {
            $i1 = ems_read_image_upload_one([
                'error' => $_FILES['images']['error'][0] ?? UPLOAD_ERR_NO_FILE,
                'tmp_name' => $_FILES['images']['tmp_name'][0] ?? '',
                'size' => $_FILES['images']['size'][0] ?? 0,
            ]);
            $i2 = ems_read_image_upload_one([
                'error' => $_FILES['images']['error'][1] ?? UPLOAD_ERR_NO_FILE,
                'tmp_name' => $_FILES['images']['tmp_name'][1] ?? '',
                'size' => $_FILES['images']['size'][1] ?? 0,
            ]);
            $i3 = ems_read_image_upload_one([
                'error' => $_FILES['images']['error'][2] ?? UPLOAD_ERR_NO_FILE,
                'tmp_name' => $_FILES['images']['tmp_name'][2] ?? '',
                'size' => $_FILES['images']['size'][2] ?? 0,
            ]);
            $i4 = ems_read_image_upload_one([
                'error' => $_FILES['images']['error'][3] ?? UPLOAD_ERR_NO_FILE,
                'tmp_name' => $_FILES['images']['tmp_name'][3] ?? '',
                'size' => $_FILES['images']['size'][3] ?? 0,
            ]);
            if ($i1) $image1 = $i1;
            if ($i2) $image2 = $i2;
            if ($i3) $image3 = $i3;
            if ($i4) $image4 = $i4;
        }

        $sql = 'UPDATE tbl_event SET eve_name=?, event_type=?, hall_name=?, hall_add=?, city=?, pincode=?, state=?, county=?, hall_capacity=?, image1=?, image2=?, image3=?, image4=?, start_date=?, end_date=? WHERE evn_id=? AND org_id=?';
        $up = $conn->prepare($sql);
        $typesBind = str_repeat('s', 15) . 'ii';
        $up->bind_param(
            $typesBind,
            $eve_name, $event_type, $hall_name, $hall_add, $city, $pincode, $state, $county, $hall_capacity,
            $image1, $image2, $image3, $image4, $start_date, $end_date, $evn_id, $org_id
        );
        if ($up->execute()) {
            $up->close();
            echo "<script>alert('Event updated'); window.location.href='events.php';</script>";
            exit;
        }
        $error = 'Update failed.';
        $up->close();
    }
}

$org_page = 'events';
$org_title = 'Edit Event';
$org_subtitle = 'Update package details for #' . $evn_id;
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>

<div class="card">
  <div class="card-header">
    <h2><?= e($event['eve_name']) ?></h2>
    <a class="btn btn-sm" href="events.php">Back to events</a>
  </div>
  <div class="card-body">
    <form method="post" enctype="multipart/form-data" class="form-grid">
      <div class="form-row">
        <label class="field">Event name<input type="text" name="eve_name" value="<?= e($event['eve_name']) ?>" required></label>
        <label class="field">Event type
          <select name="event_type" required>
            <?php foreach ($types as $t): ?>
              <option value="<?= e($t) ?>" <?= $event['event_type'] === $t ? 'selected' : '' ?>><?= e($t) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
      </div>
      <div class="form-row">
        <label class="field">Hall name<input type="text" name="hall_name" value="<?= e($event['hall_name']) ?>"></label>
        <label class="field">Capacity<input type="text" name="hall_capacity" value="<?= e($event['hall_capacity']) ?>"></label>
      </div>
      <label class="field">Hall address<textarea name="hall_add"><?= e($event['hall_add']) ?></textarea></label>
      <div class="form-row">
        <label class="field">City<input type="text" name="city" value="<?= e($event['city']) ?>"></label>
        <label class="field">State<input type="text" name="state" value="<?= e($event['state']) ?>"></label>
      </div>
      <div class="form-row">
        <label class="field">Country<input type="text" name="country" value="<?= e($event['county']) ?>"></label>
        <label class="field">Pincode<input type="text" name="pincode" value="<?= e($event['pincode']) ?>"></label>
      </div>
      <div class="form-row">
        <label class="field">Start date<input type="date" name="start_date" value="<?= e((string)$event['start_date']) ?>"></label>
        <label class="field">End date<input type="date" name="end_date" value="<?= e((string)$event['end_date']) ?>"></label>
      </div>
      <label class="field">Replace images (optional, up to 4, max 2MB each)
        <input type="file" name="images[]" accept="image/*" multiple>
      </label>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <button class="btn btn-primary" type="submit" name="edit" value="1">Save changes</button>
        <a class="btn" href="events.php">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
