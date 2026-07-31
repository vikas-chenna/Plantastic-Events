<?php

require_once __DIR__ . '/../includes/bootstrap.php';


/* =========================================================
   ORGANIZER ID
   ========================================================= */

$org_id = 0;

if (isset($_GET['org_ids'])) {

  $org_id = (int) $_GET['org_ids'];

} elseif (isset($_GET['display_org'])) {

  $org_id = (int) $_GET['display_org'];

}


if ($org_id <= 0) {

  flash_set('error', 'Organizer not found.');

  redirect('booking.php');

}


/* =========================================================
   LOGIN REQUIRED
   ========================================================= */

if (empty($_SESSION['customer'])) {

  echo "<script>
        alert('Login now to book this organizer');
        window.location.href='login.php?org_id1=" . (int) $org_id . "';
    </script>";

  exit;

}


$cid = (int) $_SESSION['customer'];

$flash = '';
$error = '';


/* =========================================================
   ORGANIZER
   ========================================================= */

$stmt = $conn->prepare(
  'SELECT *
     FROM tbl_organizer
     WHERE org_id = ?
     LIMIT 1'
);

$stmt->bind_param('i', $org_id);

$stmt->execute();

$org = $stmt->get_result()->fetch_assoc();

$stmt->close();


if (
  !$org ||
  strcasecmp((string) $org['approve'], 'Approve') !== 0 ||
  strcasecmp((string) $org['block'], 'block') === 0
) {

  flash_set(
    'error',
    'This organizer is not available.'
  );

  redirect('booking.php');

}


/* =========================================================
   ORGANIZER EVENTS
   ========================================================= */

$evq = $conn->prepare(
  'SELECT *
     FROM tbl_event
     WHERE org_id = ?
     ORDER BY evn_id DESC'
);

$evq->bind_param('i', $org_id);

$evq->execute();

$events = $evq->get_result();


/* =========================================================
   RATINGS
   ========================================================= */

$rq = $conn->prepare(
  "SELECT
        r.rating,
        r.description,
        r.rating_date,
        c.user_name

     FROM tbl_cust_rating r

     INNER JOIN tbl_customer c
        ON c.cust_id = r.cust_id

     WHERE r.org_id = ?

     ORDER BY r.rating_id DESC

     LIMIT 20"
);

$rq->bind_param('i', $org_id);

$rq->execute();

$ratings = $rq->get_result();


$avg = 0.0;

$rcount = 0;


$ar = $conn->prepare(
  "SELECT
        AVG(CAST(rating AS DECIMAL(10,2))) AS average_rating,
        COUNT(*) AS total_rating

     FROM tbl_cust_rating

     WHERE org_id = ?"
);

$ar->bind_param('i', $org_id);

$ar->execute();

$averageResult = $ar->get_result();

if ($averageRow = $averageResult->fetch_assoc()) {

  $avg =
    $averageRow['average_rating'] !== null
    ? round((float) $averageRow['average_rating'], 1)
    : 0.0;

  $rcount =
    (int) $averageRow['total_rating'];

}

$ar->close();


/* =========================================================
   DYNAMIC EVENT TYPES
   ========================================================= */

$event_types = [];

$typeQuery = $conn->query(
  "SELECT DISTINCT event_type
     FROM tbl_event
     WHERE event_type IS NOT NULL
       AND TRIM(event_type) <> ''
     ORDER BY event_type ASC"
);

if ($typeQuery) {

  while ($typeRow = $typeQuery->fetch_assoc()) {

    $event_types[] =
      $typeRow['event_type'];

  }

}


/*
 * Fallback only if no event types exist.
 */

if (empty($event_types)) {

  $event_types = [
    'Birthday',
    'Anniversery',
    'Ceremony',
    'Parties'
  ];

}


/* =========================================================
   DISPLAY EVENT TYPE
   ========================================================= */

function display_event_type(string $type): string
{

  $map = [

    'Anniversery' =>
      'Anniversary',

    'Couple_Exclusive' =>
      'Couple Exclusive'

  ];

  return $map[$type]
    ?? str_replace('_', ' ', $type);

}


/* =========================================================
   BOOKING FORM DEFAULTS
   ========================================================= */

$bf = [

  'event_name' =>
    '',

  'event_type' =>
    $event_types[0] ?? 'Birthday',

  'event_description' =>
    '',

  'start_date' =>
    '',

  'start_time' =>
    '',

  'end_date' =>
    '',

  'venue_name' =>
    '',

  'venue_address' =>
    '',

  'tot_guest' =>
    '',

  'decoration_theme' =>
    '',

  'catering' =>
    'Yes - Veg',

  'photography' =>
    'Yes',

  'budget' =>
    '',

  'payment_method' =>
    'Cash',

];


/* =========================================================
   BOOK EVENT
   ========================================================= */

if (isset($_POST['eve_book'])) {


  foreach ($bf as $key => $_) {

    if (isset($_POST[$key])) {

      $limit =
        $key === 'event_description' ||
        $key === 'venue_address'
        ? 1000
        : 100;

      $bf[$key] =
        post_string(
          $key,
          $limit
        );

    }

  }


  $eve_name =
    $bf['event_name'];

  $eve_type =
    $bf['event_type'];

  $eve_desc =
    $bf['event_description'];

  $strt_date =
    $bf['start_date'];

  $start_time =
    $bf['start_time'];

  $end_date =
    $bf['end_date'];

  $venue_name =
    $bf['venue_name'];

  $venue_addr =
    $bf['venue_address'];

  $expect_guests =
    $bf['tot_guest'];

  $theme =
    $bf['decoration_theme'];

  $catering =
    $bf['catering'];

  $photography =
    $bf['photography'];

  $event_budget =
    $bf['budget'];

  $payment_method =
    $bf['payment_method'];

  $booking_status = '';


  if (
    $eve_name === '' ||
    $strt_date === ''
  ) {

    $error =
      'Event name and start date are required. Your other details were kept.';

  } else {


    $sql =
      'INSERT INTO tbl_booking (

                cust_id,
                org_id,
                eve_name,
                eve_type,
                eve_desc,

                strt_date,
                start_time,
                end_date,

                venue_name,
                venue_addr,

                expect_guests,
                theme,

                catering,
                photography,

                event_budget,
                payment_method,
                status

            ) VALUES (
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
            )';


    $ins =
      $conn->prepare($sql);


    $ins->bind_param(

      'iisssssssssssssss',

      $cid,
      $org_id,

      $eve_name,
      $eve_type,
      $eve_desc,

      $strt_date,
      $start_time,
      $end_date,

      $venue_name,
      $venue_addr,

      $expect_guests,
      $theme,

      $catering,
      $photography,

      $event_budget,
      $payment_method,

      $booking_status

    );


    if ($ins->execute()) {


      $ins->close();


      /* Notify organizer */

      $note =
        'New booking request: '
        . $eve_name
        . ' on '
        . $strt_date;


      $empty = '';


      $mq =
        $conn->prepare(
          'INSERT INTO tbl_org_cust_msg (
                        cust_id,
                        org_id,
                        org_msg,
                        cust_msg
                    )
                    VALUES (?, ?, ?, ?)'
        );


      $mq->bind_param(
        'iiss',
        $cid,
        $org_id,
        $empty,
        $note
      );


      $mq->execute();

      $mq->close();


      echo "<script>

                alert(
                    'Event booked successfully! Organizer will confirm soon.'
                );

                window.location.href='profile.php';

            </script>";


      exit;

    }


    $error =
      'Booking failed. Please try again.';


    $ins->close();

  }

}


/* =========================================================
   RATING
   ========================================================= */

if (isset($_POST['feedback'])) {


  $rating =
    post_string(
      'rating',
      10
    );


  $desc =
    post_string(
      'desc',
      1000
    );


  if ($rating !== '') {


    $ins =
      $conn->prepare(
        'INSERT INTO tbl_cust_rating (
                    org_id,
                    cust_id,
                    rating,
                    description
                )
                VALUES (?,?,?,?)'
      );


    $ins->bind_param(
      'iiss',
      $org_id,
      $cid,
      $rating,
      $desc
    );


    if ($ins->execute()) {

      $flash =
        'Thanks! Your rating was submitted.';

    } else {

      $error =
        'Could not save rating.';

    }


    $ins->close();

  }

}


/* =========================================================
   SEND MESSAGE
   ========================================================= */

if (isset($_POST['submit_message'])) {


  $messageText =
    trim(
      (string) (
        $_POST['messageText']
        ?? ''
      )
    );


  if ($messageText !== '') {


    $empty = '';


    $stmt =
      $conn->prepare(
        'INSERT INTO tbl_org_cust_msg (
                    cust_id,
                    org_id,
                    org_msg,
                    cust_msg
                )
                VALUES (?, ?, ?, ?)'
      );


    $stmt->bind_param(
      'iiss',
      $cid,
      $org_id,
      $empty,
      $messageText
    );


    if ($stmt->execute()) {

      $flash =
        'Message sent to organizer.';

    } else {

      $error =
        'Failed to send message.';

    }


    $stmt->close();


  } else {


    $error =
      'Please type a message.';

  }

}


/* =========================================================
   MESSAGE THREAD
   ========================================================= */

$msg_thread =
  $conn->prepare(
    'SELECT
            m.*,
            o.user_name

         FROM tbl_org_cust_msg m

         INNER JOIN tbl_organizer o
            ON o.org_id = m.org_id

         WHERE
            m.cust_id = ?
            AND m.org_id = ?

         ORDER BY
            m.cust_msg_id ASC'
  );


$msg_thread->bind_param(
  'ii',
  $cid,
  $org_id
);


$msg_thread->execute();


$msg_rows =
  $msg_thread->get_result();


/* =========================================================
   ORGANIZER PROFILE IMAGE
   ========================================================= */

$org_pic = '';


if (!empty($org['profile_pic'])) {

  $org_pic =
    'data:image/jpeg;base64,'
    . base64_encode(
      $org['profile_pic']
    );

}


/* =========================================================
   DISPLAY VALUES
   ========================================================= */

$organizerDisplayName =
  trim(
    (string) (
      $org['company_name']
      ?? ''
    )
  );


if ($organizerDisplayName === '') {

  $organizerDisplayName =
    $org['user_name'];

}


$organizerLocation =
  trim(
    ($org['city'] ?? '')
    . ', '
    . ($org['state'] ?? ''),
    ' ,'
  );


if ($organizerLocation === '') {

  $organizerLocation =
    'India';

}


$organizerAddress =
  trim(
    ($org['address'] ?? '')
    . ', '
    . ($org['city'] ?? '')
    . ', '
    . ($org['state'] ?? ''),
    ' ,'
  );

?>

<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <title>
    <?php echo e($organizerDisplayName); ?>
    | Plantastic Events
  </title>


  <meta name="viewport" content="width=device-width, initial-scale=1">


  <meta http-equiv="X-UA-Compatible" content="IE=edge">


  <!-- =====================================================
         PLANTASTIC THEME
         ===================================================== -->

  <link rel="stylesheet" href="css/style.css">

  <link rel="stylesheet" href="css/responsive.css">


  <!-- Navbar -->

  <link rel="stylesheet" href="css/customer-navbar-logo.css">

  <link rel="stylesheet" href="css/customer-navbar-dropdown-final.css">


  <!-- =====================================================
         FAVICON
         ===================================================== -->

  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">

  <link rel="icon" type="image/png" href="images/favicon/favicon-32x32.png" sizes="32x32">

  <link rel="icon" type="image/png" href="images/favicon/favicon-16x16.png" sizes="16x16">


  <!-- =====================================================
         PROJECT SINGLE — GOLDEN UI
         ===================================================== -->

  <style>
    /* =====================================================
           BASE
           ===================================================== */

    body {
      background: #f8f6f1;
    }

    .organizer-profile-page {
      background:
        linear-gradient(180deg,
          #f6f1e7 0,
          #ffffff 580px);
    }


    .pe-container {
      width: 100%;
      max-width: 1200px;

      margin: 0 auto;

      padding-left: 15px;
      padding-right: 15px;
    }


    .pe-section {
      padding: 75px 0;
    }


    .pe-section-sm {
      padding: 55px 0;
    }


    /* =====================================================
           ALERTS
           ===================================================== */

    .pe-alert {
      margin-bottom: 20px;

      padding: 15px 18px;

      border-radius: 12px;

      font-size: 14px;

      font-weight: 600;
    }


    .pe-alert-success {
      color: #315f31;
      background: #edf7e9;
      border: 1px solid #cfe8c9;
    }


    .pe-alert-error {
      color: #8b3131;
      background: #fff0f0;
      border: 1px solid #f0cccc;
    }


    /* =====================================================
           HERO
           ===================================================== */

    .organizer-profile-hero {

      position: relative;

      overflow: hidden;

      padding:
        85px 0 90px;

      background:
        radial-gradient(circle at 13% 15%,
          rgba(212, 175, 55, .20),
          transparent 30%),

        radial-gradient(circle at 88% 85%,
          rgba(212, 175, 55, .10),
          transparent 30%),

        linear-gradient(135deg,
          #0c0c0d,
          #17140e 60%,
          #211a0c);

    }


    .organizer-profile-hero::before {

      content: "";

      position: absolute;

      top: -180px;
      right: -110px;

      width: 390px;
      height: 390px;

      border:
        1px solid rgba(212, 175, 55, .13);

      border-radius: 50%;

    }


    .organizer-profile-hero::after {

      content: "";

      position: absolute;

      left: -170px;
      bottom: -190px;

      width: 350px;
      height: 350px;

      border:
        1px solid rgba(212, 175, 55, .10);

      border-radius: 50%;

    }


    .organizer-profile-hero .pe-container {

      position: relative;
      z-index: 2;

    }


    .organizer-hero-grid {

      display: grid;

      grid-template-columns:
        minmax(0, 1fr) auto;

      align-items: center;

      gap: 40px;

    }


    .organizer-identity {

      display: flex;

      align-items: center;

      gap: 25px;

      min-width: 0;

    }


    .organizer-avatar {

      width: 125px;
      height: 125px;

      flex: 0 0 125px;

      overflow: hidden;

      display: flex;

      align-items: center;

      justify-content: center;

      border:
        2px solid rgba(228, 196, 91, .6);

      border-radius: 30px;

      background:
        linear-gradient(135deg,
          #292114,
          #17130d);

      box-shadow:
        0 18px 45px rgba(0, 0, 0, .3);

    }


    .organizer-avatar img {

      width: 100%;
      height: 100%;

      object-fit: cover;

    }


    .organizer-avatar-placeholder {

      color: #d4af37;

      font-size: 43px;

      font-weight: 900;

    }


    .organizer-eyebrow {

      display: flex;

      align-items: center;

      gap: 9px;

      margin-bottom: 9px;

      color: #d4af37;

      font-size: 12px;

      font-weight: 900;

      letter-spacing: 1.8px;

      text-transform: uppercase;

    }


    .organizer-eyebrow::before {

      content: "";

      width: 28px;
      height: 2px;

      background: #d4af37;

    }


    .organizer-identity h1 {

      margin: 0;

      color: #ffffff;

      font-size:
        clamp(34px, 5vw, 58px);

      font-weight: 800;

      line-height: 1.08;

      letter-spacing: -1px;

    }


    .organizer-host {

      margin: 11px 0 0;

      color: #c6bfb4;

      font-size: 15px;

    }


    .organizer-host strong {

      color: #ffffff;

    }


    /* =====================================================
           BADGES
           ===================================================== */

    .organizer-badges {

      display: flex;

      flex-wrap: wrap;

      gap: 8px;

      margin-top: 18px;

    }


    .organizer-badge {

      display: inline-flex;

      align-items: center;

      min-height: 32px;

      padding: 7px 12px;

      color: #d5cec2;

      background:
        rgba(255, 255, 255, .055);

      border:
        1px solid rgba(255, 255, 255, .09);

      border-radius: 999px;

      font-size: 11px;

      font-weight: 800;

      letter-spacing: .3px;

    }


    .organizer-badge.approved {

      color: #1c180e;

      background:
        linear-gradient(135deg,
          #f0d77c,
          #c99c24);

      border: 0;

    }


    .organizer-badge.rating {

      color: #efd26a;

      border-color:
        rgba(212, 175, 55, .25);

    }


    /* =====================================================
           HERO ACTIONS
           ===================================================== */

    .organizer-hero-actions {

      display: flex;

      flex-direction: column;

      gap: 10px;

      min-width: 170px;

    }


    .pe-btn {

      display: inline-flex;

      align-items: center;

      justify-content: center;

      gap: 8px;

      min-height: 45px;

      padding: 11px 18px;

      border: 0;

      border-radius: 10px;

      cursor: pointer;

      font-family: inherit;

      font-size: 13px;

      font-weight: 900;

      text-decoration: none !important;

      transition:
        transform .2s ease,
        box-shadow .2s ease,
        background .2s ease;

    }


    .pe-btn:hover {
      transform: translateY(-2px);
    }


    .pe-btn-gold {

      color: #18140d !important;

      background:
        linear-gradient(135deg,
          #efd471,
          #c99c24);

      box-shadow:
        0 9px 22px rgba(193, 145, 28, .20);

    }


    .pe-btn-outline {

      color: #f2ede5 !important;

      background:
        rgba(255, 255, 255, .05);

      border:
        1px solid rgba(255, 255, 255, .14);

    }


    .pe-btn-outline:hover {

      background:
        rgba(255, 255, 255, .09);

    }


    .pe-btn-light {

      color: #282116 !important;

      background: #f4f0e8;

      border: 1px solid #e3dac9;

    }


    .pe-btn-block {
      width: 100%;
    }


    /* =====================================================
           BOOKING STEPS
           ===================================================== */

    .booking-process-wrap {

      position: relative;

      z-index: 5;

      margin-top: -35px;

    }


    .booking-process {

      display: grid;

      grid-template-columns:
        repeat(3, minmax(0, 1fr));

      overflow: hidden;

      background: #ffffff;

      border:
        1px solid #ebe3d5;

      border-radius: 18px;

      box-shadow:
        0 16px 45px rgba(38, 29, 15, .10);

    }


    .booking-process-item {

      position: relative;

      display: flex;

      gap: 14px;

      padding: 23px;

    }


    .booking-process-item:not(:last-child) {

      border-right:
        1px solid #ece5d9;

    }


    .booking-process-number {

      display: flex;

      align-items: center;

      justify-content: center;

      width: 40px;
      height: 40px;

      flex: 0 0 40px;

      color: #1a160e;

      background:
        linear-gradient(135deg,
          #efd675,
          #c99b23);

      border-radius: 12px;

      font-size: 13px;

      font-weight: 900;

    }


    .booking-process-item strong {

      display: block;

      margin-bottom: 4px;

      color: #221c14;

      font-size: 14px;

    }


    .booking-process-item p {

      margin: 0;

      color: #81786b;

      font-size: 12px;

      line-height: 1.55;

    }


    /* =====================================================
           GENERAL CARD
           ===================================================== */

    .pe-card {

      background: #ffffff;

      border:
        1px solid #ebe4d8;

      border-radius: 18px;

      box-shadow:
        0 8px 28px rgba(40, 31, 18, .055);

    }


    .pe-card-header {

      display: flex;

      align-items: center;

      justify-content: space-between;

      gap: 15px;

      padding: 21px 23px;

      border-bottom:
        1px solid #eee8dd;

    }


    .pe-card-header h2 {

      margin: 0;

      color: #211b13;

      font-size: 20px;

      font-weight: 800;

    }


    .pe-card-header p {

      margin: 5px 0 0;

      color: #8c8376;

      font-size: 12px;

    }


    .pe-card-body {

      padding: 23px;

    }


    /* =====================================================
           ABOUT + BOOKING GRID
           ===================================================== */

    .profile-booking-grid {

      display: grid;

      grid-template-columns:
        minmax(0, .82fr) minmax(0, 1.18fr);

      align-items: start;

      gap: 24px;

    }


    /* =====================================================
           ABOUT ORGANIZER
           ===================================================== */

    .about-list {

      display: grid;

      gap: 0;

    }


    .about-row {

      display: grid;

      grid-template-columns:
        120px minmax(0, 1fr);

      gap: 15px;

      padding: 14px 0;

      border-bottom:
        1px solid #eee8dd;

    }


    .about-row:first-child {
      padding-top: 0;
    }


    .about-row:last-child {

      padding-bottom: 0;

      border-bottom: 0;

    }


    .about-label {

      color: #9a8f7e;

      font-size: 11px;

      font-weight: 900;

      letter-spacing: .6px;

      text-transform: uppercase;

    }


    .about-value {

      min-width: 0;

      overflow-wrap: anywhere;

      color: #332b21;

      font-size: 14px;

      line-height: 1.55;

    }


    /* =====================================================
           FORMS
           ===================================================== */

    .pe-form {

      display: grid;

      gap: 17px;

    }


    .pe-form-row {

      display: grid;

      grid-template-columns:
        repeat(2, minmax(0, 1fr));

      gap: 15px;

    }


    .pe-field {

      display: grid;

      gap: 7px;

      margin: 0;

      color: #4c4338;

      font-size: 12px;

      font-weight: 800;

    }


    .pe-field input,
    .pe-field select,
    .pe-field textarea {

      width: 100%;

      min-height: 46px;

      padding: 11px 13px;

      color: #2d261d;

      background: #fbfaf7;

      border:
        1px solid #ded6c8;

      border-radius: 9px;

      outline: none;

      font-family: inherit;

      font-size: 13px;

      font-weight: 500;

      transition:
        border-color .2s ease,
        box-shadow .2s ease,
        background .2s ease;

    }


    .pe-field textarea {

      min-height: 105px;

      resize: vertical;

    }


    .pe-field input:focus,
    .pe-field select:focus,
    .pe-field textarea:focus {

      background: #ffffff;

      border-color: #cda52f;

      box-shadow:
        0 0 0 3px rgba(205, 165, 47, .11);

    }


    /* =====================================================
           GALLERY SECTION
           ===================================================== */

    .packages-section {

      padding-top: 0;

    }


    .section-title {

      margin-bottom: 25px;

    }


    .section-eyebrow {

      margin-bottom: 7px;

      color: #b18a20;

      font-size: 11px;

      font-weight: 900;

      letter-spacing: 1.5px;

      text-transform: uppercase;

    }


    .section-title h2 {

      margin: 0;

      color: #211b13;

      font-size:
        clamp(28px, 4vw, 38px);

      font-weight: 800;

    }


    .section-title p {

      max-width: 650px;

      margin: 8px 0 0;

      color: #857b6d;

      font-size: 14px;

      line-height: 1.65;

    }


    .packages-grid {

      display: grid;

      grid-template-columns:
        repeat(2, minmax(0, 1fr));

      gap: 23px;

    }


    .package-card {

      overflow: hidden;

      background: #ffffff;

      border:
        1px solid #e9e1d4;

      border-radius: 18px;

      box-shadow:
        0 8px 28px rgba(40, 31, 18, .055);

    }


    .package-top {

      padding: 20px 21px 15px;

    }


    .package-type {

      display: inline-flex;

      margin-bottom: 7px;

      padding: 5px 9px;

      color: #6f5310;

      background: #f8efd0;

      border-radius: 999px;

      font-size: 10px;

      font-weight: 900;

      letter-spacing: .5px;

      text-transform: uppercase;

    }


    .package-top h3 {

      margin: 0;

      color: #201a12;

      font-size: 20px;

      font-weight: 800;

    }


    /* =====================================================
           PACKAGE CAROUSEL
           ===================================================== */

    .package-carousel {

      position: relative;

      overflow: hidden;

      background: #18140e;

    }


    .package-carousel .carousel-item {

      height: 270px;

    }


    .package-carousel .carousel-item img {

      width: 100%;
      height: 100%;

      object-fit: cover;

    }


    .package-no-image {

      display: flex;

      align-items: center;

      justify-content: center;

      height: 270px;

      color: #d4af37;

      background:
        linear-gradient(135deg,
          #18140e,
          #292114);

      font-size: 15px;

      font-weight: 800;

    }



    /* =====================================================
           PACKAGE GALLERY — STANDALONE SLIDER
           ===================================================== */

    .pe-gallery {
      position: relative;
      height: 270px;
      overflow: hidden;
      background: #18140e;
    }

    .pe-gallery-slide {
      display: none;
      width: 100%;
      height: 100%;
    }

    .pe-gallery-slide.is-active {
      display: block;
    }

    .pe-gallery-slide img {
      width: 100%;
      height: 100%;
      display: block;
      object-fit: cover;
    }

    .pe-gallery-nav {
      position: absolute;
      top: 50%;
      z-index: 4;
      width: 42px;
      height: 42px;
      padding: 0;
      transform: translateY(-50%);
      border: 1px solid rgba(255, 255, 255, .25);
      border-radius: 50%;
      color: #fff;
      background: rgba(18, 15, 10, .72);
      cursor: pointer;
      font-size: 25px;
      line-height: 1;
      transition: .2s ease;
    }

    .pe-gallery-nav:hover {
      color: #18140d;
      background: #c99c24;
    }

    .pe-gallery-prev { left: 13px; }
    .pe-gallery-next { right: 13px; }

    .pe-gallery-count {
      position: absolute;
      right: 13px;
      bottom: 12px;
      z-index: 4;
      padding: 5px 9px;
      border-radius: 999px;
      color: #fff;
      background: rgba(18, 15, 10, .72);
      font-size: 11px;
      font-weight: 800;
    }

    /* =====================================================
           PACKAGE INFO
           ===================================================== */

    .package-info {

      display: grid;

      grid-template-columns:
        repeat(3, minmax(0, 1fr));

      border-top:
        1px solid #eee7dc;

    }


    .package-info-item {

      padding: 15px;

      text-align: center;

    }


    .package-info-item:not(:last-child) {

      border-right:
        1px solid #eee7dc;

    }


    .package-info-label {

      display: block;

      margin-bottom: 5px;

      color: #9a8e7d;

      font-size: 9px;

      font-weight: 900;

      letter-spacing: .7px;

      text-transform: uppercase;

    }


    .package-info-value {

      color: #30281e;

      font-size: 12px;

      font-weight: 700;

      overflow-wrap: anywhere;

    }


    /* =====================================================
           EMPTY
           ===================================================== */

    .pe-empty {

      padding: 40px 20px;

      color: #877d6e;

      background: #fbfaf7;

      border:
        1px dashed #d9cfbf;

      border-radius: 13px;

      text-align: center;

      font-size: 13px;

    }


    .pe-empty strong {

      display: block;

      margin-bottom: 5px;

      color: #30281d;

      font-size: 15px;

    }


    /* =====================================================
           CHAT + RATINGS
           ===================================================== */

    .community-grid {

      display: grid;

      grid-template-columns:
        repeat(2, minmax(0, 1fr));

      align-items: start;

      gap: 24px;

    }


    .message-thread,
    .reviews-list {

      display: grid;

      gap: 10px;

      max-height: 330px;

      margin-top: 20px;

      padding-right: 4px;

      overflow-y: auto;

    }


    .message-bubble {

      max-width: 86%;

      padding: 11px 13px;

      border-radius: 12px;

      font-size: 12px;

      line-height: 1.55;

    }


    .message-bubble.customer {

      justify-self: end;

      color: #241d12;

      background:
        #f4e6b7;

      border-bottom-right-radius: 4px;

    }


    .message-bubble.organizer {

      justify-self: start;

      color: #40382e;

      background: #f3f1ec;

      border:
        1px solid #e8e2d8;

      border-bottom-left-radius: 4px;

    }


    .message-author {

      display: block;

      margin-bottom: 3px;

      font-size: 10px;

      font-weight: 900;

      text-transform: uppercase;

    }


    /* =====================================================
           REVIEWS
           ===================================================== */

    .rating-summary {

      display: flex;

      align-items: center;

      gap: 16px;

      margin-bottom: 20px;

      padding: 15px;

      background: #fbf7e9;

      border:
        1px solid #eee2bb;

      border-radius: 13px;

    }


    .rating-number {

      color: #b48716;

      font-size: 32px;

      font-weight: 900;

      line-height: 1;

    }


    .rating-stars {

      color: #c79b22;

      font-size: 14px;

      letter-spacing: 2px;

    }


    .rating-count {

      margin-top: 3px;

      color: #8b8173;

      font-size: 11px;

    }


    .review-item {

      padding: 14px;

      background: #fbfaf7;

      border:
        1px solid #eae3d8;

      border-radius: 12px;

    }


    .review-head {

      display: flex;

      align-items: center;

      justify-content: space-between;

      gap: 10px;

      margin-bottom: 7px;

    }


    .review-name {

      color: #30281e;

      font-size: 12px;

      font-weight: 900;

    }


    .review-stars {

      color: #b98d1c;

      font-size: 11px;

      font-weight: 900;

    }


    .review-text {

      margin: 0;

      color: #675e52;

      font-size: 12px;

      line-height: 1.6;

    }


    .review-date {

      margin-top: 7px;

      color: #a29788;

      font-size: 10px;

    }


    /* =====================================================
           TABLET
           ===================================================== */

    @media (max-width: 991px) {

      .organizer-profile-hero {

        padding:
          70px 0 75px;

      }


      .organizer-hero-grid {

        grid-template-columns: 1fr;

      }


      .organizer-hero-actions {

        flex-direction: row;

        flex-wrap: wrap;

      }


      .profile-booking-grid {

        grid-template-columns: 1fr;

      }


      .packages-grid {

        grid-template-columns: 1fr;

      }

    }


    /* =====================================================
           MOBILE
           ===================================================== */

    @media (max-width: 767px) {

      .pe-section {
        padding: 55px 0;
      }


      .organizer-profile-hero {

        padding:
          55px 0 65px;

      }


      .organizer-identity {

        align-items: flex-start;

        flex-direction: column;

        gap: 18px;

      }


      .organizer-avatar {

        width: 100px;
        height: 100px;

        flex-basis: 100px;

        border-radius: 24px;

      }


      .organizer-identity h1 {

        font-size:
          clamp(31px, 10vw, 44px);

      }


      .organizer-hero-actions {

        display: grid;

        grid-template-columns:
          repeat(2, minmax(0, 1fr));

        width: 100%;

      }


      .organizer-hero-actions .pe-btn:last-child {

        grid-column: 1 / -1;

      }


      .booking-process-wrap {

        margin-top: -25px;

      }


      .booking-process {

        grid-template-columns: 1fr;

      }


      .booking-process-item:not(:last-child) {

        border-right: 0;

        border-bottom:
          1px solid #ece5d9;

      }


      .pe-card-header,
      .pe-card-body {

        padding-left: 18px;
        padding-right: 18px;

      }


      .pe-form-row {

        grid-template-columns: 1fr;

      }


      .about-row {

        grid-template-columns: 1fr;

        gap: 5px;

      }


      .community-grid {

        grid-template-columns: 1fr;

      }


      .package-carousel .carousel-item,
      .package-no-image,
      .pe-gallery {

        height: 220px;

      }

    }


    /* =====================================================
           SMALL MOBILE
           ===================================================== */

    @media (max-width: 430px) {

      .organizer-hero-actions {

        grid-template-columns: 1fr;

      }


      .organizer-hero-actions .pe-btn:last-child {

        grid-column: auto;

      }


      .package-info {

        grid-template-columns: 1fr;

      }


      .package-info-item:not(:last-child) {

        border-right: 0;

        border-bottom:
          1px solid #eee7dc;

      }

    }
  </style>

</head>


<body>


  <div class="boxed_wrapper">


    <!-- =====================================================
         NAVBAR
         ===================================================== -->

    <header class="main-header header-style2 stricky">


      <div class="inner-container clearfix">


        <div class="logo-box-style2 float-left">


          <a href="index-3.php">


            <img src="images/plantastic-logo-modern.png" alt="Plantastic Events">


          </a>


        </div>


        <div class="main-menu-box float-right">


          <nav class="main-menu style2 clearfix">


            <div class="navbar-header clearfix">


              <button type="button" class="navbar-toggle" aria-label="Toggle navigation">


                <span class="icon-bar"></span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>


              </button>


            </div>


            <div class="navbar-collapse collapse clearfix">


              <ul class="navigation clearfix" id="navButtons">


                <li>


                  <a href="index-3.php">
                    Home
                  </a>


                </li>


                <li class="dropdown">


                  <a href="#">
                    Events
                  </a>


                  <ul>


                    <li>
                      <a href="Anniversary.php">
                        Anniverseries/Jubilees
                      </a>
                    </li>


                    <li>
                      <a href="Ceremony.php">
                        Ceremony
                      </a>
                    </li>


                    <li>
                      <a href="Couples.php">
                        Couple Exclusive
                      </a>
                    </li>


                    <li>
                      <a href="Birthday.php">
                        Birthdays
                      </a>
                    </li>


                    <li>
                      <a href="Party.php">
                        Parties
                      </a>
                    </li>


                    <li>
                      <a href="Corporate.php">
                        Corporate
                      </a>
                    </li>


                    <li>
                      <a href="Others.php">
                        Others
                      </a>
                    </li>


                  </ul>


                </li>


                <li class="current">


                  <a href="booking.php">
                    Organizers
                  </a>


                </li>


                <li>


                  <a href="contact.php">
                    Contact Us
                  </a>


                </li>


                <li class="dropdown">


                  <a href="about.php">
                    About Us
                  </a>


                  <ul>


                    <li>
                      <a href="about.php">
                        About Company
                      </a>
                    </li>


                    <li>
                      <a href="faq.php">
                        FAQ’s
                      </a>
                    </li>


                  </ul>


                </li>


                <li>


                  <a href="profile.php" id="profileButton">

                    Profile

                  </a>


                </li>


              </ul>


            </div>


          </nav>


          <div class="mainmenu-right style2">


            <div class="outer-search-box">


              <div class="seach-toggle">


                <i class="fa fa-search"></i>


              </div>


              <ul class="search-box">


                <li>


                  <form method="get" action="booking.php">


                    <div class="form-group">


                      <input type="search" name="search" placeholder="Search Organizers">


                      <button type="submit">


                        <i class="fa fa-search"></i>


                      </button>


                    </div>


                  </form>


                </li>


              </ul>


            </div>


          </div>


        </div>


      </div>


    </header>


    <!-- =====================================================
         PAGE
         ===================================================== -->

    <main class="organizer-profile-page">


      <!-- =================================================
             HERO
             ================================================= -->

      <section class="organizer-profile-hero">


        <div class="pe-container">


          <div class="organizer-hero-grid">


            <div class="organizer-identity">


              <div class="organizer-avatar">


                <?php if ($org_pic): ?>


                  <img src="<?php echo e($org_pic); ?>" alt="<?php echo e($organizerDisplayName); ?>">


                <?php else: ?>


                  <div class="organizer-avatar-placeholder">

                    PE

                  </div>


                <?php endif; ?>


              </div>


              <div>


                <div class="organizer-eyebrow">

                  Approved Organizer

                </div>


                <h1>

                  <?php
                  echo e(
                    $organizerDisplayName
                  );
                  ?>

                </h1>


                <p class="organizer-host">

                  Hosted by

                  <strong>

                    <?php
                    echo e(
                      $org['user_name']
                    );
                    ?>

                  </strong>

                  ·

                  <?php
                  echo e(
                    $organizerLocation
                  );
                  ?>

                </p>


                <div class="organizer-badges">


                  <span class="organizer-badge approved">

                    ✓ Approved Organizer

                  </span>


                  <span class="organizer-badge rating">

                    <?php if ($avg > 0): ?>

                      <?php echo e((string) $avg); ?>
                      ★

                    <?php else: ?>

                      New Organizer

                    <?php endif; ?>

                    &nbsp;·&nbsp;

                    <?php echo (int) $rcount; ?>
                    reviews

                  </span>


                  <?php if (!empty($org['experience'])): ?>


                    <span class="organizer-badge">

                      <?php
                      echo e(
                        $org['experience']
                      );
                      ?>

                    </span>


                  <?php endif; ?>


                </div>


              </div>


            </div>


            <div class="organizer-hero-actions">


              <a class="pe-btn pe-btn-gold" href="#book">

                Book Now

              </a>


              <a class="pe-btn pe-btn-outline" href="#chat">

                Message

              </a>


              <a class="pe-btn pe-btn-outline" href="booking.php">

                All Organizers

              </a>


            </div>


          </div>


        </div>


      </section>


      <!-- =================================================
             BOOKING PROCESS
             ================================================= -->

      <div class="booking-process-wrap">


        <div class="pe-container">


          <div class="booking-process">


            <div class="booking-process-item">


              <div class="booking-process-number">
                01
              </div>


              <div>


                <strong>
                  Explore Packages
                </strong>


                <p>

                  Browse this organizer's
                  event work and gallery.

                </p>


              </div>


            </div>


            <div class="booking-process-item">


              <div class="booking-process-number">
                02
              </div>


              <div>


                <strong>
                  Send Booking
                </strong>


                <p>

                  Share date, venue,
                  guests and your budget.

                </p>


              </div>


            </div>


            <div class="booking-process-item">


              <div class="booking-process-number">
                03
              </div>


              <div>


                <strong>
                  Get Confirmation
                </strong>


                <p>

                  The organizer reviews
                  and confirms your request.

                </p>


              </div>


            </div>


          </div>


        </div>


      </div>


      <!-- =================================================
             ABOUT + BOOKING
             ================================================= -->

      <section class="pe-section">


        <div class="pe-container">


          <?php if ($flash): ?>


            <div class="pe-alert pe-alert-success">

              <?php echo e($flash); ?>

            </div>


          <?php endif; ?>


          <?php if ($error): ?>


            <div class="pe-alert pe-alert-error">

              <?php echo e($error); ?>

            </div>


          <?php endif; ?>


          <div class="profile-booking-grid">


            <!-- =====================================
                         ABOUT ORGANIZER
                         ===================================== -->

            <div class="pe-card">


              <div class="pe-card-header">


                <div>


                  <h2>
                    About Organizer
                  </h2>


                  <p>

                    Organizer details and
                    contact information.

                  </p>


                </div>


              </div>


              <div class="pe-card-body">


                <div class="about-list">


                  <div class="about-row">


                    <div class="about-label">
                      Organizer
                    </div>


                    <div class="about-value">

                      <?php
                      echo e(
                        $org['user_name']
                      );
                      ?>

                    </div>


                  </div>


                  <div class="about-row">


                    <div class="about-label">
                      Company
                    </div>


                    <div class="about-value">

                      <?php
                      echo e(
                        $org['company_name']
                        ?: '—'
                      );
                      ?>

                    </div>


                  </div>


                  <div class="about-row">


                    <div class="about-label">
                      Contact
                    </div>


                    <div class="about-value">

                      <?php
                      echo e(
                        $org['mobile_no']
                        ?: '—'
                      );
                      ?>

                    </div>


                  </div>


                  <div class="about-row">


                    <div class="about-label">
                      Email
                    </div>


                    <div class="about-value">

                      <?php
                      echo e(
                        $org['email']
                        ?: '—'
                      );
                      ?>

                    </div>


                  </div>


                  <div class="about-row">


                    <div class="about-label">
                      Experience
                    </div>


                    <div class="about-value">

                      <?php
                      echo e(
                        $org['experience']
                        ?: '—'
                      );
                      ?>

                    </div>


                  </div>


                  <div class="about-row">


                    <div class="about-label">
                      Established
                    </div>


                    <div class="about-value">

                      <?php
                      echo e(
                        $org['since_establish']
                        ?: '—'
                      );
                      ?>

                    </div>


                  </div>


                  <div class="about-row">


                    <div class="about-label">
                      Address
                    </div>


                    <div class="about-value">

                      <?php
                      echo e(
                        $organizerAddress
                        ?: '—'
                      );
                      ?>

                    </div>


                  </div>


                </div>


              </div>


            </div>


            <!-- =====================================
                         BOOKING FORM
                         ===================================== -->

            <div class="pe-card" id="book">


              <div class="pe-card-header">


                <div>


                  <h2>
                    Book This Organizer
                  </h2>


                  <p>

                    Tell the organizer what
                    you're planning.

                  </p>


                </div>


              </div>


              <div class="pe-card-body">


                <form method="post" class="pe-form">


                  <div class="pe-form-row">


                    <label class="pe-field">

                      Event Name *

                      <input type="text" name="event_name" value="<?php echo e($bf['event_name']); ?>" required
                        placeholder="e.g. Riya's Birthday Bash">

                    </label>


                    <label class="pe-field">

                      Event Type

                      <select name="event_type">


                        <?php foreach ($event_types as $type): ?>


                          <option value="<?php echo e($type); ?>" <?php echo $bf['event_type'] === $type ? 'selected' : ''; ?>>

                            <?php
                            echo e(
                              display_event_type(
                                $type
                              )
                            );
                            ?>

                          </option>


                        <?php endforeach; ?>


                      </select>


                    </label>


                  </div>


                  <label class="pe-field">

                    Event Description

                    <textarea name="event_description"
                      placeholder="Tell the organizer about your event, style and requirements..."><?php echo e($bf['event_description']); ?></textarea>

                  </label>


                  <div class="pe-form-row">


                    <label class="pe-field">

                      Start Date *

                      <input type="date" name="start_date" value="<?php echo e($bf['start_date']); ?>" required>

                    </label>


                    <label class="pe-field">

                      Start Time

                      <input type="time" name="start_time" value="<?php echo e($bf['start_time']); ?>">

                    </label>


                  </div>


                  <div class="pe-form-row">


                    <label class="pe-field">

                      End Date

                      <input type="date" name="end_date" value="<?php echo e($bf['end_date']); ?>">

                    </label>


                    <label class="pe-field">

                      Expected Guests

                      <input type="number" min="1" name="tot_guest" value="<?php echo e($bf['tot_guest']); ?>"
                        placeholder="e.g. 150">

                    </label>


                  </div>


                  <div class="pe-form-row">


                    <label class="pe-field">

                      Venue Name

                      <input type="text" name="venue_name" value="<?php echo e($bf['venue_name']); ?>"
                        placeholder="Venue / Hall">

                    </label>


                    <label class="pe-field">

                      Decoration Theme

                      <input type="text" name="decoration_theme" value="<?php echo e($bf['decoration_theme']); ?>"
                        placeholder="e.g. Royal Gold">

                    </label>


                  </div>


                  <label class="pe-field">

                    Venue Address

                    <textarea name="venue_address"
                      placeholder="Complete event venue address"><?php echo e($bf['venue_address']); ?></textarea>

                  </label>


                  <div class="pe-form-row">


                    <label class="pe-field">

                      Catering

                      <select name="catering">


                        <?php

                        foreach (
                          [
                            'Yes - Veg',
                            'Yes - Non Veg',
                            'Yes - Both',
                            'No'
                          ]
                          as $option
                        ):

                          ?>


                          <option value="<?php echo e($option); ?>" <?php echo $bf['catering'] === $option ? 'selected' : ''; ?>>

                            <?php
                            echo e(
                              $option
                            );
                            ?>

                          </option>


                        <?php endforeach; ?>


                      </select>


                    </label>


                    <label class="pe-field">

                      Photography

                      <select name="photography">


                        <?php

                        foreach (
                          [
                            'Yes',
                            'No',
                            'Optional'
                          ]
                          as $option
                        ):

                          ?>


                          <option value="<?php echo e($option); ?>" <?php echo $bf['photography'] === $option ? 'selected' : ''; ?>>

                            <?php
                            echo e(
                              $option
                            );
                            ?>

                          </option>


                        <?php endforeach; ?>


                      </select>


                    </label>


                  </div>


                  <div class="pe-form-row">


                    <label class="pe-field">

                      Budget

                      <input type="text" name="budget" value="<?php echo e($bf['budget']); ?>" placeholder="e.g. 25000">

                    </label>


                    <label class="pe-field">

                      Payment Method

                      <select name="payment_method">


                        <?php

                        foreach (
                          [
                            'Cash',
                            'UPI',
                            'Card',
                            'Bank Transfer'
                          ]
                          as $option
                        ):

                          ?>


                          <option value="<?php echo e($option); ?>" <?php echo $bf['payment_method'] === $option ? 'selected' : ''; ?>>

                            <?php
                            echo e(
                              $option
                            );
                            ?>

                          </option>


                        <?php endforeach; ?>


                      </select>


                    </label>


                  </div>


                  <button class="pe-btn pe-btn-gold pe-btn-block" type="submit" name="eve_book" value="1">

                    Submit Booking Request

                  </button>


                </form>


              </div>


            </div>


          </div>


        </div>


      </section>


      <!-- =================================================
             EVENT PACKAGES + GALLERY
             ================================================= -->

      <section class="pe-section packages-section">


        <div class="pe-container">


          <div class="section-title">


            <div class="section-eyebrow">

              Organizer Portfolio

            </div>


            <h2>

              Event Packages & Gallery

            </h2>


            <p>

              Explore this organizer's event
              packages, venue details and previous
              event images before sending your
              booking request.

            </p>


          </div>


          <?php if (
            $events &&
            $events->num_rows > 0
          ): ?>


            <?php $events->data_seek(0); ?>


            <div class="packages-grid">


              <?php

              while (
                $event =
                $events->fetch_assoc()
              ):

                $carouselId =
                  'event-carousel-'
                  . (int) $event['evn_id'];

                ?>


                <article class="package-card">


                  <div class="package-top">


                    <span class="package-type">

                      <?php
                      echo e(
                        display_event_type(
                          $event['event_type']
                        )
                      );
                      ?>

                    </span>


                    <h3>

                      <?php
                      echo e(
                        $event['eve_name']
                      );
                      ?>

                    </h3>


                  </div>


                  <!-- Gallery -->

                  <?php
                  $eventImages = [];

                  foreach (['image1', 'image2', 'image3', 'image4'] as $imageKey) {

                    if (!empty($event[$imageKey])) {

                      $eventImages[] = $event[$imageKey];

                    }

                  }

                  $imageCount = count($eventImages);
                  ?>


                  <div class="package-carousel">


                    <?php if ($imageCount > 0): ?>


                      <div class="pe-gallery" data-pe-gallery>


                        <?php foreach ($eventImages as $imageIndex => $imageData): ?>


                          <div class="pe-gallery-slide<?php echo $imageIndex === 0 ? ' is-active' : ''; ?>">


                            <img
                              src="data:image/jpeg;base64,<?php echo base64_encode($imageData); ?>"
                              alt="<?php echo e($event['eve_name']); ?> image <?php echo $imageIndex + 1; ?>"
                              loading="lazy">


                          </div>


                        <?php endforeach; ?>


                        <?php if ($imageCount > 1): ?>


                          <button class="pe-gallery-nav pe-gallery-prev" type="button" aria-label="Previous image">

                            &#8249;

                          </button>


                          <button class="pe-gallery-nav pe-gallery-next" type="button" aria-label="Next image">

                            &#8250;

                          </button>


                          <div class="pe-gallery-count">

                            <span data-pe-current>1</span>
                            /
                            <?php echo $imageCount; ?>

                          </div>


                        <?php endif; ?>


                      </div>


                    <?php else: ?>


                      <div class="package-no-image">

                        Plantastic Events

                      </div>


                    <?php endif; ?>


                  </div>


                  <!-- Information -->

                  <div class="package-info">


                    <div class="package-info-item">


                      <span class="package-info-label">
                        Hall
                      </span>


                      <span class="package-info-value">

                        <?php
                        echo e(
                          $event['hall_name']
                          ?: '—'
                        );
                        ?>

                      </span>


                    </div>


                    <div class="package-info-item">


                      <span class="package-info-label">
                        Capacity
                      </span>


                      <span class="package-info-value">

                        <?php
                        echo e(
                          $event['hall_capacity']
                          ?: '—'
                        );
                        ?>

                      </span>


                    </div>


                    <div class="package-info-item">


                      <span class="package-info-label">
                        Dates
                      </span>


                      <span class="package-info-value">

                        <?php
                        echo e(
                          (string) $event['start_date']
                        );
                        ?>

                        →

                        <?php
                        echo e(
                          (string) $event['end_date']
                        );
                        ?>

                      </span>


                    </div>


                  </div>


                </article>


              <?php endwhile; ?>


            </div>


          <?php else: ?>


            <div class="pe-empty">


              <strong>
                No packages listed yet.
              </strong>


              You can still send this organizer
              a custom booking request.


            </div>


          <?php endif; ?>


        </div>


      </section>


      <!-- =================================================
             MESSAGE + RATINGS
             ================================================= -->

      <section class="pe-section-sm" id="chat">


        <div class="pe-container">


          <div class="community-grid">


            <!-- =====================================
                         MESSAGE ORGANIZER
                         ===================================== -->

            <div class="pe-card">


              <div class="pe-card-header">


                <div>


                  <h2>
                    Message Organizer
                  </h2>


                  <p>

                    Ask about pricing,
                    availability or custom ideas.

                  </p>


                </div>


              </div>


              <div class="pe-card-body">


                <form method="post" class="pe-form">


                  <label class="pe-field">

                    Your Message

                    <textarea name="messageText" required
                      placeholder="Ask about availability, themes, pricing..."></textarea>

                  </label>


                  <button class="pe-btn pe-btn-gold" type="submit" name="submit_message" value="1">

                    Send Message

                  </button>


                </form>


                <div class="message-thread">


                  <?php if (
                    $msg_rows &&
                    $msg_rows->num_rows > 0
                  ): ?>


                    <?php

                    $msg_rows->data_seek(0);

                    while (
                      $message =
                      $msg_rows->fetch_assoc()
                    ):

                      ?>


                      <?php if (
                        !empty(
                        $message['org_msg']
                      )
                      ): ?>


                        <div class="message-bubble organizer">


                          <span class="message-author">

                            Organizer

                          </span>


                          <?php
                          echo e(
                            $message['org_msg']
                          );
                          ?>


                        </div>


                      <?php endif; ?>


                      <?php if (
                        !empty(
                        $message['cust_msg']
                      )
                      ): ?>


                        <div class="message-bubble customer">


                          <span class="message-author">

                            You

                          </span>


                          <?php
                          echo e(
                            $message['cust_msg']
                          );
                          ?>


                        </div>


                      <?php endif; ?>


                    <?php endwhile; ?>


                  <?php else: ?>


                    <div class="pe-empty">

                      No conversation yet.
                      Send the organizer a message.

                    </div>


                  <?php endif; ?>


                </div>


              </div>


            </div>


            <!-- =====================================
                         RATINGS
                         ===================================== -->

            <div class="pe-card">


              <div class="pe-card-header">


                <div>


                  <h2>
                    Ratings & Reviews
                  </h2>


                  <p>

                    See customer feedback
                    or share your experience.

                  </p>


                </div>


              </div>


              <div class="pe-card-body">


                <div class="rating-summary">


                  <div class="rating-number">

                    <?php
                    echo $avg > 0
                      ? e((string) $avg)
                      : '—';
                    ?>

                  </div>


                  <div>


                    <div class="rating-stars">

                      ★★★★★

                    </div>


                    <div class="rating-count">

                      Based on
                      <?php echo (int) $rcount; ?>
                      review<?php echo $rcount === 1 ? '' : 's'; ?>

                    </div>


                  </div>


                </div>


                <!-- Rating form -->

                <form method="post" class="pe-form">


                  <label class="pe-field">

                    Rating

                    <select name="rating" required>


                      <option value="">

                        Select Rating

                      </option>


                      <?php

                      for (
                        $ratingValue = 5;
                        $ratingValue >= 1;
                        $ratingValue--
                      ):

                        ?>


                        <option value="<?php echo $ratingValue; ?>">

                          <?php echo $ratingValue; ?>
                          ★

                        </option>


                      <?php endfor; ?>


                    </select>


                  </label>


                  <label class="pe-field">

                    Feedback

                    <textarea name="desc" placeholder="How was your experience?"></textarea>

                  </label>


                  <button class="pe-btn pe-btn-light" type="submit" name="feedback" value="1">

                    Submit Rating

                  </button>


                </form>


                <!-- Reviews -->

                <div class="reviews-list">


                  <?php if (
                    $ratings &&
                    $ratings->num_rows > 0
                  ): ?>


                    <?php

                    $ratings->data_seek(0);

                    while (
                      $review =
                      $ratings->fetch_assoc()
                    ):

                      ?>


                      <article class="review-item">


                        <div class="review-head">


                          <span class="review-name">

                            <?php
                            echo e(
                              $review['user_name']
                            );
                            ?>

                          </span>


                          <span class="review-stars">

                            <?php
                            echo e(
                              $review['rating']
                            );
                            ?>

                            ★

                          </span>


                        </div>


                        <?php if (
                          trim(
                            (string) $review['description']
                          ) !== ''
                        ): ?>


                          <p class="review-text">

                            <?php
                            echo e(
                              $review['description']
                            );
                            ?>

                          </p>


                        <?php endif; ?>


                        <div class="review-date">

                          <?php
                          echo e(
                            (string) $review['rating_date']
                          );
                          ?>

                        </div>


                      </article>


                    <?php endwhile; ?>


                  <?php else: ?>


                    <div class="pe-empty">

                      <strong>
                        No ratings yet.
                      </strong>

                      Be the first customer
                      to review this organizer.

                    </div>


                  <?php endif; ?>


                </div>


              </div>


            </div>


          </div>


        </div>


      </section>


    </main>


    <!-- =====================================================
         FOOTER CONTACT INFO
         ===================================================== -->

    <section class="footer-contact-info-area">


      <div class="container">


        <div class="row">


          <div class="col-xl-12">


            <ul class="footer-contact-info clearfix">


              <li>


                <div class="single-footer-contact-info">


                  <div class="inner">


                    <div class="icon">

                      <span class="icon-global"></span>

                    </div>


                    <div class="text">


                      <p>

                        Sr. No. 149,
                        VIP Road, Vesu,

                        <br>

                        Bharthana, Surat,
                        Gujarat 395007

                      </p>


                    </div>


                  </div>


                </div>


              </li>


              <li>


                <div class="single-footer-contact-info">


                  <div class="inner">


                    <div class="icon">

                      <span class="icon-support1"></span>

                    </div>


                    <div class="text">


                      <p>

                        +91 998 877 6655

                        <br>

                        <span>
                          Mon - Friday:
                        </span>

                        9.00am to 6.00pm

                      </p>


                    </div>


                  </div>


                </div>


              </li>


              <li>


                <div class="single-footer-contact-info">


                  <div class="inner">


                    <div class="icon">

                      <span class="icon-shipping-and-delivery"></span>

                    </div>


                    <div class="text">


                      <p>

                        support@plantasticevents.com

                        <br>

                        plantasticevents@gmail.com

                      </p>


                    </div>


                  </div>


                </div>


              </li>


            </ul>


          </div>


        </div>


      </div>


    </section>


    <!-- =====================================================
         FOOTER
         ===================================================== -->

    <section class="footer-bottom-area style3">


      <div class="container">


        <div class="row">


          <div class="col-xl-12">


            <div class="copyright-text text-center">


              <p>


                <a href="index-3.php">

                  Plantastic Events

                </a>


              </p>


            </div>


          </div>


        </div>


      </div>


    </section>


  </div>


  <!-- =========================================================
     SCROLL TOP
     ========================================================= -->

  <div class="scroll-to-top scroll-to-target" data-target="html">

    <span class="fa fa-angle-up"></span>

  </div>


  <!-- =========================================================
     JAVASCRIPT
     ========================================================= -->

  <script src="js/jquery.js"></script>

  <script src="js/appear.js"></script>

  <script src="js/bootstrap.bundle.min.js"></script>

  <script src="js/bootstrap-select.min.js"></script>

  <script src="js/isotope.js"></script>

  <script src="js/jquery.bootstrap-touchspin.js"></script>

  <script src="js/jquery.countTo.js"></script>

  <script src="js/jquery.easing.min.js"></script>

  <script src="js/jquery.enllax.min.js"></script>

  <script src="js/jquery.fancybox.js"></script>

  <script src="js/jquery.mixitup.min.js"></script>

  <script src="js/jquery.paroller.min.js"></script>

  <script src="js/owl.js"></script>

  <script src="js/validation.js"></script>

  <script src="js/custom.js"></script>



  <!-- =========================================================
       PACKAGE GALLERY SLIDER
       ========================================================= -->

  <script>
    document.addEventListener('DOMContentLoaded', function () {

      document.querySelectorAll('[data-pe-gallery]').forEach(function (gallery) {

        var slides = Array.prototype.slice.call(
          gallery.querySelectorAll('.pe-gallery-slide')
        );

        var prevButton = gallery.querySelector('.pe-gallery-prev');
        var nextButton = gallery.querySelector('.pe-gallery-next');
        var currentLabel = gallery.querySelector('[data-pe-current]');

        var currentIndex = 0;


        if (slides.length <= 1) {
          return;
        }


        function showSlide(index) {

          currentIndex =
            (index + slides.length) % slides.length;


          slides.forEach(function (slide, slideIndex) {

            slide.classList.toggle(
              'is-active',
              slideIndex === currentIndex
            );

          });


          if (currentLabel) {

            currentLabel.textContent =
              currentIndex + 1;

          }

        }


        if (prevButton) {

          prevButton.addEventListener(
            'click',
            function () {

              showSlide(currentIndex - 1);

            }
          );

        }


        if (nextButton) {

          nextButton.addEventListener(
            'click',
            function () {

              showSlide(currentIndex + 1);

            }
          );

        }

      });

    });
  </script>


  <!-- Current Navbar Mobile Logic -->

  <script src="js/customer-navbar-dropdown-final.js"></script>


</body>

</html>