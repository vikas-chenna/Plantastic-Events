<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_organizer();
$org_id = (int)$_SESSION['organizer'];

function ems_read_image_upload(array $files, int $index): ?string
{
    if (!isset($files['tmp_name'][$index]) || (int)$files['error'][$index] !== UPLOAD_ERR_OK) {
        return null;
    }
    $tmp = $files['tmp_name'][$index];
    $size = (int)($files['size'][$index] ?? 0);
    if ($size <= 0 || $size > 2 * 1024 * 1024) {
        return null;
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp) ?: '';
    $allowed = ['image/jpeg' => true, 'image/png' => true, 'image/webp' => true, 'image/gif' => true];
    if (!isset($allowed[$mime])) {
        return null;
    }
    $data = file_get_contents($tmp);
    return $data === false ? null : $data;
}

$form_error = '';
$show_add_tab = false;
$form = [
    'eve_name' => '', 'event_type' => '', 'hall_name' => '', 'hall_add' => '',
    'city' => '', 'pincode' => '', 'state' => '', 'country' => '',
    'hall_capacity' => '', 'start_date' => '', 'end_date' => '',
];

if (isset($_POST['delete_event'])) {
    $evn_id = post_int('evn_id');
    if ($evn_id > 0) {
        $del = $conn->prepare('DELETE FROM tbl_event WHERE evn_id = ? AND org_id = ?');
        $del->bind_param('ii', $evn_id, $org_id);
        $del->execute();
        $del->close();
        $_SESSION['org_flash'] = 'Event deleted';
        header('Location: events.php');
        exit;
    }
}

if (isset($_POST['event_add'])) {
    $show_add_tab = true;
    foreach ($form as $k => $_) {
        $form[$k] = post_string($k === 'country' ? 'country' : $k, 250);
    }
    // normalize keys from form names
    $form['eve_name'] = post_string('eve_name', 50);
    $form['event_type'] = post_string('event_type', 50);
    $form['hall_name'] = post_string('hall_name', 50);
    $form['hall_add'] = post_string('hall_add', 250);
    $form['city'] = post_string('city', 50);
    $form['pincode'] = post_string('pincode', 10);
    $form['state'] = post_string('state', 50);
    $form['country'] = post_string('country', 50);
    $form['hall_capacity'] = post_string('hall_capacity', 20);
    $form['start_date'] = post_string('start_date', 20);
    $form['end_date'] = post_string('end_date', 20);

    $eve_name = $form['eve_name'];
    $event_type = $form['event_type'];
    $hall_name = $form['hall_name'];
    $hall_add = $form['hall_add'];
    $city = $form['city'];
    $pincode = $form['pincode'];
    $state = $form['state'];
    $county = $form['country'];
    $hall_capacity = $form['hall_capacity'];
    $start_date = $form['start_date'];
    $end_date = $form['end_date'];
    $time = date('H:i:s');
    $add_evn_date = date('Y-m-d');
    $status = '';

    $files = $_FILES['images'] ?? [];
    $upload_count = is_array($files['name'] ?? null) ? count(array_filter($files['name'])) : 0;
    $image1 = ems_read_image_upload($files, 0);
    $image2 = ems_read_image_upload($files, 1);
    $image3 = ems_read_image_upload($files, 2);
    $image4 = ems_read_image_upload($files, 3);

    if ($eve_name === '' || $event_type === '') {
        $form_error = 'Please fill event name and type. Other details were kept.';
    } elseif ($upload_count < 4 || !$image1 || !$image2 || !$image3 || !$image4) {
        $form_error = 'Upload exactly 4 valid images (JPG/PNG/WEBP/GIF), each max 2MB. Text fields were kept — only re-select images.';
    } else {
        $sql = 'INSERT INTO tbl_event
            (org_id, eve_name, event_type, hall_name, hall_add, city, pincode, state, county, hall_capacity,
             image1, image2, image3, image4, time, start_date, end_date, add_evn_date, status)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $conn->prepare($sql);
        $types = 'i' . str_repeat('s', 18);
        $stmt->bind_param(
            $types,
            $org_id, $eve_name, $event_type, $hall_name, $hall_add, $city, $pincode, $state, $county, $hall_capacity,
            $image1, $image2, $image3, $image4, $time, $start_date, $end_date, $add_evn_date, $status
        );
        if ($stmt->execute()) {
            $stmt->close();
            $_SESSION['org_flash'] = 'Event added successfully';
            header('Location: events.php');
            exit;
        }
        $form_error = 'Failed to save event. Please try again.';
        $stmt->close();
    }
}

$stmt = $conn->prepare('SELECT * FROM tbl_event WHERE org_id = ? ORDER BY evn_id DESC');
$stmt->bind_param('i', $org_id);
$stmt->execute();
$res = $stmt->get_result();

$types = ['Anniversery','Ceremony','Couple_Exclusive','Birthday','Parties','Corporate','Others'];

$org_page = 'events';
$org_title = 'My Events';
$org_subtitle = 'Create packages customers can discover and book';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($form_error): ?><div class="alert err"><?= e($form_error) ?></div><?php endif; ?>

<div class="toolbar">
  <div class="tabs">
    <button class="tab-btn <?= $show_add_tab ? '' : 'active' ?>" type="button" data-tab-group="ev" data-tab="view">View events <span class="badge soft"><?= $res ? (int)$res->num_rows : 0 ?></span></button>
    <button class="tab-btn <?= $show_add_tab ? 'active' : '' ?>" type="button" data-tab-group="ev" data-tab="add">Add event</button>
  </div>
  <div class="search">
    <span class="sico">⌕</span>
    <input type="search" placeholder="Search events..." data-search-input="events">
  </div>
</div>

<div class="tab-panel <?= $show_add_tab ? '' : 'active' ?>" data-tab-panel-group="ev" data-tab-panel="view">
  <div class="grid-auto">
    <?php if ($res && $res->num_rows > 0): while ($row = $res->fetch_assoc()):
      $cid = 'eventCarousel' . (int)$row['evn_id'];
    ?>
      <div class="card entity-card" data-search-item="events">
        <div class="card-body">
          <div class="title">
            <div>
              <h3><?= e($row['eve_name']) ?></h3>
              <div style="color:var(--text-muted);font-size:.85rem;margin-top:2px"><?= e($row['event_type']) ?> · <?= e($row['city'] ?: '—') ?></div>
            </div>
            <span class="pill"><?= e($row['status'] ?: 'Listed') ?></span>
          </div>

          <div style="margin-top:12px;border-radius:14px;overflow:hidden;border:1px solid var(--border);background:#000">
            <div id="<?= $cid ?>" class="carousel slide" data-bs-ride="false">
              <div class="carousel-inner">
                <?php
                  $first = true;
                  foreach (['image1','image2','image3','image4'] as $k) {
                      if (empty($row[$k])) continue;
                      $active = $first ? ' active' : '';
                      $first = false;
                      echo '<div class="carousel-item' . $active . '"><img src="data:image/jpeg;base64,' . base64_encode($row[$k]) . '" class="d-block w-100" style="height:210px;object-fit:cover" alt="event"></div>';
                  }
                  if ($first) {
                      echo '<div class="carousel-item active"><div style="height:210px;display:grid;place-items:center;color:#fff;opacity:.7">No image</div></div>';
                  }
                ?>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#<?= $cid ?>" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
              <button class="carousel-control-next" type="button" data-bs-target="#<?= $cid ?>" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>
          </div>

          <div class="kv">
            <div class="k">Hall</div><div><?= e($row['hall_name'] ?: '—') ?></div>
            <div class="k">Capacity</div><div><?= e($row['hall_capacity'] ?: '—') ?></div>
            <div class="k">Dates</div><div><?= e((string)$row['start_date']) ?> → <?= e((string)$row['end_date']) ?></div>
          </div>
        </div>
        <div class="card-footer">
          <a class="btn btn-sm" href="edit-event.php?evn_id=<?= (int)$row['evn_id'] ?>">Edit</a>
          <form method="post" onsubmit="return confirm('Delete this event?');" style="display:inline">
            <input type="hidden" name="evn_id" value="<?= (int)$row['evn_id'] ?>">
            <button class="btn btn-sm btn-danger" name="delete_event" value="1">Delete</button>
          </form>
        </div>
      </div>
    <?php endwhile; else: ?>
      <div class="card"><div class="empty"><strong>No events yet</strong>Add your first service package for customers.</div></div>
    <?php endif; ?>
  </div>
</div>

<div class="tab-panel <?= $show_add_tab ? 'active' : '' ?>" data-tab-panel-group="ev" data-tab-panel="add">
  <div class="card">
    <div class="card-header"><h2>Add new event package</h2></div>
    <div class="card-body">
      <form method="post" enctype="multipart/form-data" class="form-grid">
        <div class="form-row">
          <label class="field">Event name *
            <input type="text" name="eve_name" value="<?= e($form['eve_name']) ?>" required>
          </label>
          <label class="field">Event type *
            <select name="event_type" required>
              <option value="">Select type</option>
              <?php foreach ($types as $t): ?>
                <option value="<?= e($t) ?>" <?= $form['event_type'] === $t ? 'selected' : '' ?>><?= e($t) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        </div>
        <div class="form-row">
          <label class="field">Hall name<input type="text" name="hall_name" value="<?= e($form['hall_name']) ?>"></label>
          <label class="field">Capacity<input type="number" name="hall_capacity" value="<?= e($form['hall_capacity']) ?>"></label>
        </div>
        <label class="field">Hall address<textarea name="hall_add"><?= e($form['hall_add']) ?></textarea></label>
        <div class="form-row">
          <label class="field">City<input type="text" name="city" value="<?= e($form['city']) ?>"></label>
          <label class="field">State<input type="text" name="state" value="<?= e($form['state']) ?>"></label>
        </div>
        <div class="form-row">
          <label class="field">Country<input type="text" name="country" value="<?= e($form['country']) ?>"></label>
          <label class="field">Pincode<input type="text" name="pincode" value="<?= e($form['pincode']) ?>"></label>
        </div>
        <div class="form-row">
          <label class="field">Start date<input type="date" name="start_date" value="<?= e($form['start_date']) ?>"></label>
          <label class="field">End date<input type="date" name="end_date" value="<?= e($form['end_date']) ?>"></label>
        </div>
        <label class="field">4 event images * (max 2MB each)
          <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple required>
          <span style="font-weight:500;color:var(--text-muted);font-size:.8rem">If upload fails, text fields stay filled — only re-select images.</span>
        </label>
        <button class="btn btn-primary" type="submit" name="event_add" value="1">Save event</button>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap carousel JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
