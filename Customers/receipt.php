<?php

require_once __DIR__ . '/../includes/bootstrap.php';


/* =========================================================
   LOGIN CHECK
   ========================================================= */

if (empty($_SESSION['customer'])) {

  echo "<script>
        alert('Login now');
        window.location.href='login.php';
    </script>";

  exit;
}


$cid = (int) $_SESSION['customer'];


/* =========================================================
   BOOKING ID
   ========================================================= */

$b_id = get_int('b_ids');


if ($b_id <= 0) {

  redirect('profile.php');

}


/* =========================================================
   LOAD CONFIRMED BOOKING
   ========================================================= */

$stmt = $conn->prepare(

  "SELECT
        c.user_name AS customer_name,
        c.email AS customer_email,
        c.contact AS customer_contact,

        b.*,

        o.user_name AS org_name,
        o.company_name,
        o.email AS org_email,
        o.mobile_no

     FROM tbl_booking b

     INNER JOIN tbl_customer c
        ON c.cust_id = b.cust_id

     INNER JOIN tbl_organizer o
        ON o.org_id = b.org_id

     WHERE
        b.b_id = ?
        AND b.cust_id = ?
        AND b.status = 'confirm'

     LIMIT 1"

);


$stmt->bind_param(
  'ii',
  $b_id,
  $cid
);


$stmt->execute();


$booking =
  $stmt->get_result()->fetch_assoc();


$stmt->close();


/* =========================================================
   INVALID / UNCONFIRMED BOOKING
   ========================================================= */

if (!$booking) {

  echo "<script>
        alert('Receipt available only for confirmed bookings');
        window.location.href='profile.php';
    </script>";

  exit;

}


/* =========================================================
   DISPLAY VALUES
   ========================================================= */

$companyName =
  trim(
    (string) (
      $booking['company_name']
      ?? ''
    )
  );


$organizerName =
  trim(
    (string) (
      $booking['org_name']
      ?? ''
    )
  );


$organizerDisplay =
  $companyName !== ''
  ? $companyName
  : $organizerName;


/* ---------------------------------------------------------
   Event Type
   --------------------------------------------------------- */

$eventType =
  (string) (
    $booking['eve_type']
    ?? ''
  );


$eventTypeMap = [

  'Anniversery' =>
    'Anniversary',

  'Couple_Exclusive' =>
    'Couple Exclusive'

];


$eventTypeDisplay =
  $eventTypeMap[$eventType]
  ?? str_replace(
    '_',
    ' ',
    $eventType
  );


/* ---------------------------------------------------------
   Booking Date
   --------------------------------------------------------- */

$bookingDateDisplay = '—';


if (!empty($booking['booking_date'])) {

  $bookingTimestamp =
    strtotime(
      (string) $booking['booking_date']
    );


  if ($bookingTimestamp !== false) {

    $bookingDateDisplay =
      date(
        'd M Y',
        $bookingTimestamp
      );

  }

}


/* ---------------------------------------------------------
   Start Date
   --------------------------------------------------------- */

$startDateDisplay = '—';


if (!empty($booking['strt_date'])) {

  $timestamp =
    strtotime(
      (string) $booking['strt_date']
    );


  if ($timestamp !== false) {

    $startDateDisplay =
      date(
        'd M Y',
        $timestamp
      );

  }

}


/* ---------------------------------------------------------
   End Date
   --------------------------------------------------------- */

$endDateDisplay = '—';


if (!empty($booking['end_date'])) {

  $timestamp =
    strtotime(
      (string) $booking['end_date']
    );


  if ($timestamp !== false) {

    $endDateDisplay =
      date(
        'd M Y',
        $timestamp
      );

  }

}


/* ---------------------------------------------------------
   Start Time
   --------------------------------------------------------- */

$startTimeDisplay = '—';


if (!empty($booking['start_time'])) {

  $timeStamp =
    strtotime(
      (string) $booking['start_time']
    );


  if ($timeStamp !== false) {

    $startTimeDisplay =
      date(
        'h:i A',
        $timeStamp
      );

  }

}


/* ---------------------------------------------------------
   Venue
   --------------------------------------------------------- */

$venueParts = [];


if (
  !empty(
  trim(
    (string) (
      $booking['venue_name']
      ?? ''
    )
  )
)
) {

  $venueParts[] =
    trim(
      (string) $booking['venue_name']
    );

}


if (
  !empty(
  trim(
    (string) (
      $booking['venue_addr']
      ?? ''
    )
  )
)
) {

  $venueParts[] =
    trim(
      (string) $booking['venue_addr']
    );

}


$venueDisplay =
  !empty($venueParts)
  ? implode(', ', $venueParts)
  : '—';


/* ---------------------------------------------------------
   Receipt Number
   --------------------------------------------------------- */

$receiptNumber =
  'PE-'
  . str_pad(
    (string) $booking['b_id'],
    6,
    '0',
    STR_PAD_LEFT
  );

?>

<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <title>
    Booking Receipt <?php echo e($receiptNumber); ?>
    | Plantastic Events
  </title>


  <meta name="viewport" content="width=device-width, initial-scale=1">


  <meta http-equiv="X-UA-Compatible" content="IE=edge">


  <!-- =====================================================
         PLANTASTIC THEME
         ===================================================== -->

  <link rel="stylesheet" href="css/style.css">

  <link rel="stylesheet" href="css/responsive.css">


  <!-- =====================================================
         NAVBAR
         ===================================================== -->

  <link rel="stylesheet" href="css/customer-navbar-logo.css">

  <link rel="stylesheet" href="css/customer-navbar-dropdown-final.css">


  <!-- =====================================================
         FAVICON
         ===================================================== -->

  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">

  <link rel="icon" type="image/png" href="images/favicon/favicon-32x32.png" sizes="32x32">

  <link rel="icon" type="image/png" href="images/favicon/favicon-16x16.png" sizes="16x16">


  <style>
    /* =====================================================
           BASE
           ===================================================== */

    body {

      margin: 0;

      background: #f7f4ed;

    }


    .receipt-page {

      min-height: 100vh;

      padding: 65px 15px 90px;

      background:

        radial-gradient(circle at 10% 10%,
          rgba(212, 175, 55, .08),
          transparent 25%),

        linear-gradient(180deg,
          #f4efe5,
          #faf9f6);

    }


    .receipt-container {

      width: 100%;

      max-width: 940px;

      margin: 0 auto;

    }


    /* =====================================================
           PAGE TOP
           ===================================================== */

    .receipt-page-top {

      display: flex;

      align-items: center;

      justify-content: space-between;

      gap: 20px;

      margin-bottom: 22px;

    }


    .receipt-page-title span {

      display: block;

      margin-bottom: 5px;

      color: #a77d17;

      font-size: 11px;

      font-weight: 900;

      letter-spacing: 1.5px;

      text-transform: uppercase;

    }


    .receipt-page-title h1 {

      margin: 0;

      color: #1c1710;

      font-size: 28px;

      font-weight: 800;

    }


    .receipt-actions {

      display: flex;

      gap: 9px;

    }


    .receipt-btn {

      display: inline-flex;

      align-items: center;

      justify-content: center;

      min-height: 42px;

      padding: 10px 17px;

      border-radius: 9px;

      cursor: pointer;

      font-family: inherit;

      font-size: 12px;

      font-weight: 900;

      text-decoration: none !important;

      transition:
        transform .2s ease,
        box-shadow .2s ease;

    }


    .receipt-btn:hover {

      transform: translateY(-1px);

    }


    .receipt-btn-secondary {

      color: #393126 !important;

      background: #ffffff;

      border: 1px solid #ddd4c6;

    }


    .receipt-btn-gold {

      color: #17130d !important;

      background:
        linear-gradient(135deg,
          #efd575,
          #c89a21);

      border: 0;

      box-shadow:
        0 7px 18px rgba(185, 137, 24, .18);

    }


    /* =====================================================
           RECEIPT
           ===================================================== */

    .receipt {

      overflow: hidden;

      background: #ffffff;

      border: 1px solid #e5ddd0;

      border-radius: 22px;

      box-shadow:
        0 22px 60px rgba(43, 34, 20, .10);

    }


    /* =====================================================
           RECEIPT HEADER
           ===================================================== */

    .receipt-header {

      position: relative;

      overflow: hidden;

      display: flex;

      align-items: flex-start;

      justify-content: space-between;

      gap: 30px;

      padding: 35px;

      background:

        radial-gradient(circle at 85% 15%,
          rgba(212, 175, 55, .17),
          transparent 35%),

        linear-gradient(135deg,
          #0d0d0e,
          #18150f 65%,
          #241c0d);

    }


    .receipt-header::after {

      content: "";

      position: absolute;

      width: 230px;
      height: 230px;

      right: -130px;
      bottom: -150px;

      border:
        1px solid rgba(212, 175, 55, .15);

      border-radius: 50%;

    }


    .receipt-brand {

      position: relative;

      z-index: 2;

    }


    .receipt-brand-label {

      display: inline-block;

      margin-bottom: 8px;

      color: #d4af37;

      font-size: 10px;

      font-weight: 900;

      letter-spacing: 1.7px;

      text-transform: uppercase;

    }


    .receipt-brand h2 {

      margin: 0;

      color: #ffffff;

      font-size: 29px;

      font-weight: 800;

    }


    .receipt-brand p {

      margin: 7px 0 0;

      color: #aaa296;

      font-size: 12px;

    }


    .receipt-number {

      position: relative;

      z-index: 2;

      text-align: right;

    }


    .receipt-number-label {

      display: block;

      margin-bottom: 5px;

      color: #aaa296;

      font-size: 9px;

      font-weight: 900;

      letter-spacing: 1px;

      text-transform: uppercase;

    }


    .receipt-number strong {

      color: #e7c95d;

      font-size: 18px;

      font-weight: 900;

      letter-spacing: .7px;

    }


    /* =====================================================
           STATUS
           ===================================================== */

    .receipt-summary {

      display: flex;

      align-items: flex-start;

      justify-content: space-between;

      gap: 25px;

      padding: 30px 35px;

      border-bottom: 1px solid #eee8de;

    }


    .receipt-status {

      display: inline-flex;

      align-items: center;

      gap: 7px;

      padding: 6px 11px;

      color: #275c32;

      background: #eaf6ec;

      border: 1px solid #cbe7d0;

      border-radius: 999px;

      font-size: 10px;

      font-weight: 900;

      letter-spacing: .6px;

      text-transform: uppercase;

    }


    .receipt-status::before {

      content: "✓";

      display: flex;

      align-items: center;

      justify-content: center;

      width: 17px;
      height: 17px;

      color: #ffffff;

      background: #3b8b4d;

      border-radius: 50%;

      font-size: 9px;

    }


    .receipt-event h3 {

      margin: 11px 0 5px;

      color: #211a12;

      font-size: 25px;

      font-weight: 800;

    }


    .receipt-event p {

      margin: 0;

      color: #8a8174;

      font-size: 13px;

    }


    .receipt-booked-date {

      flex: 0 0 auto;

      text-align: right;

    }


    .receipt-booked-date span {

      display: block;

      margin-bottom: 5px;

      color: #9b9183;

      font-size: 9px;

      font-weight: 900;

      letter-spacing: .8px;

      text-transform: uppercase;

    }


    .receipt-booked-date strong {

      color: #31291f;

      font-size: 13px;

    }


    /* =====================================================
           CUSTOMER / ORGANIZER
           ===================================================== */

    .receipt-parties {

      display: grid;

      grid-template-columns:
        repeat(2, minmax(0, 1fr));

      border-bottom: 1px solid #eee8de;

    }


    .receipt-party {

      padding: 28px 35px;

    }


    .receipt-party:first-child {

      border-right: 1px solid #eee8de;

    }


    .receipt-party-label {

      display: block;

      margin-bottom: 13px;

      color: #b38a21;

      font-size: 10px;

      font-weight: 900;

      letter-spacing: 1.2px;

      text-transform: uppercase;

    }


    .receipt-party-name {

      margin-bottom: 6px;

      color: #292117;

      font-size: 16px;

      font-weight: 800;

    }


    .receipt-party-company {

      margin-bottom: 8px;

      color: #766c5e;

      font-size: 12px;

    }


    .receipt-party-contact {

      color: #8c8275;

      font-size: 12px;

      line-height: 1.7;

      overflow-wrap: anywhere;

    }


    /* =====================================================
           EVENT DETAILS
           ===================================================== */

    .receipt-details {

      padding: 32px 35px;

    }


    .receipt-section-title {

      margin-bottom: 20px;

    }


    .receipt-section-title span {

      display: block;

      margin-bottom: 5px;

      color: #a87d17;

      font-size: 9px;

      font-weight: 900;

      letter-spacing: 1.2px;

      text-transform: uppercase;

    }


    .receipt-section-title h3 {

      margin: 0;

      color: #231c13;

      font-size: 19px;

      font-weight: 800;

    }


    .receipt-details-grid {

      display: grid;

      grid-template-columns:
        repeat(2, minmax(0, 1fr));

      border:
        1px solid #e9e2d7;

      border-radius: 14px;

      overflow: hidden;

    }


    .receipt-detail {

      display: grid;

      grid-template-columns:
        115px minmax(0, 1fr);

      gap: 12px;

      padding: 15px 17px;

      border-bottom:
        1px solid #eee8de;

    }


    .receipt-detail:nth-child(odd) {

      border-right:
        1px solid #eee8de;

    }


    .receipt-detail:nth-last-child(-n+2) {

      border-bottom: 0;

    }


    .receipt-detail.full {

      grid-column: 1 / -1;

      border-right: 0;

    }


    .receipt-detail.full:last-child {

      border-bottom: 0;

    }


    .receipt-detail-label {

      color: #998e7f;

      font-size: 10px;

      font-weight: 900;

      letter-spacing: .5px;

      text-transform: uppercase;

    }


    .receipt-detail-value {

      min-width: 0;

      color: #382f24;

      font-size: 12px;

      font-weight: 600;

      line-height: 1.55;

      overflow-wrap: anywhere;

    }


    .receipt-detail-value.budget {

      color: #9b7210;

      font-size: 14px;

      font-weight: 900;

    }


    /* =====================================================
           NOTICE
           ===================================================== */

    .receipt-notice {

      display: flex;

      align-items: flex-start;

      gap: 12px;

      margin:
        0 35px 32px;

      padding: 15px 17px;

      color: #6b5b31;

      background: #fbf5df;

      border: 1px solid #eadba7;

      border-radius: 11px;

      font-size: 12px;

      line-height: 1.6;

    }


    .receipt-notice-icon {

      display: flex;

      align-items: center;

      justify-content: center;

      width: 23px;
      height: 23px;

      flex: 0 0 23px;

      color: #201a10;

      background: #e5c85e;

      border-radius: 50%;

      font-size: 11px;

      font-weight: 900;

    }


    /* =====================================================
           RECEIPT FOOTER
           ===================================================== */

    .receipt-footer {

      display: flex;

      align-items: center;

      justify-content: space-between;

      gap: 20px;

      padding: 20px 35px;

      background: #f7f4ee;

      border-top: 1px solid #eae3d8;

    }


    .receipt-footer p {

      margin: 0;

      color: #8d8375;

      font-size: 10px;

      line-height: 1.6;

    }


    .receipt-footer strong {

      color: #51483d;

    }


    .receipt-footer-brand {

      flex: 0 0 auto;

      color: #a47b17;

      font-size: 11px;

      font-weight: 900;

      letter-spacing: 1px;

      text-transform: uppercase;

    }


    /* =====================================================
           TABLET
           ===================================================== */

    @media (max-width: 767px) {

      .receipt-page {

        padding:
          40px 12px 65px;

      }


      .receipt-page-top {

        align-items: flex-start;

        flex-direction: column;

      }


      .receipt-actions {

        width: 100%;

      }


      .receipt-btn {

        flex: 1;

      }


      .receipt {

        border-radius: 17px;

      }


      .receipt-header {

        padding: 27px 22px;

      }


      .receipt-summary {

        padding: 25px 22px;

      }


      .receipt-parties {

        grid-template-columns: 1fr;

      }


      .receipt-party {

        padding: 23px 22px;

      }


      .receipt-party:first-child {

        border-right: 0;

        border-bottom:
          1px solid #eee8de;

      }


      .receipt-details {

        padding: 27px 22px;

      }


      .receipt-details-grid {

        grid-template-columns: 1fr;

      }


      .receipt-detail {

        grid-template-columns:
          105px minmax(0, 1fr);

        border-right: 0 !important;

        border-bottom:
          1px solid #eee8de !important;

      }


      .receipt-detail:last-child {

        border-bottom: 0 !important;

      }


      .receipt-notice {

        margin:
          0 22px 25px;

      }


      .receipt-footer {

        padding: 18px 22px;

      }

    }


    /* =====================================================
           SMALL MOBILE
           ===================================================== */

    @media (max-width: 480px) {

      .receipt-page-title h1 {

        font-size: 23px;

      }


      .receipt-actions {

        flex-direction: column;

      }


      .receipt-header {

        flex-direction: column;

        gap: 20px;

      }


      .receipt-number {

        text-align: left;

      }


      .receipt-summary {

        flex-direction: column;

      }


      .receipt-booked-date {

        text-align: left;

      }


      .receipt-detail {

        grid-template-columns: 1fr;

        gap: 5px;

      }


      .receipt-footer {

        align-items: flex-start;

        flex-direction: column;

      }

    }


    /* =====================================================
           PRINT
           ===================================================== */

    @media print {


      @page {

        size: A4;

        margin: 12mm;

      }


      html,
      body {

        background: #ffffff !important;

      }


      body {

        -webkit-print-color-adjust: exact;

        print-color-adjust: exact;

      }


      .main-header,
      .receipt-page-top,
      .footer-contact-info-area,
      .footer-bottom-area,
      .scroll-to-top {

        display: none !important;

      }


      .receipt-page {

        min-height: 0;

        padding: 0;

        background: #ffffff !important;

      }


      .receipt-container {

        max-width: none;

      }


      .receipt {

        border:
          1px solid #d9d9d9;

        border-radius: 0;

        box-shadow: none;

      }


      .receipt-header {

        background: #15130f !important;

      }


      .receipt-detail {

        break-inside: avoid;

      }


      .receipt-parties {

        break-inside: avoid;

      }


      .receipt-notice {

        break-inside: avoid;

      }


      a {

        text-decoration: none !important;

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


        <!-- Logo -->

        <div class="logo-box-style2 float-left">


          <a href="index-3.php">


            <img src="images/plantastic-logo-modern.png" alt="Plantastic Events">


          </a>


        </div>


        <!-- Navigation -->

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


              <ul class="navigation clearfix">


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


                <li>


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


                <li class="current">


                  <a href="profile.php">
                    Profile
                  </a>


                </li>


              </ul>


            </div>


          </nav>


        </div>


      </div>


    </header>


    <!-- =====================================================
         RECEIPT PAGE
         ===================================================== -->

    <main class="receipt-page">


      <div class="receipt-container">


        <!-- =============================================
                 TOP ACTIONS
                 ============================================= -->

        <div class="receipt-page-top">


          <div class="receipt-page-title">


            <span>
              Confirmed Booking
            </span>


            <h1>
              Booking Receipt
            </h1>


          </div>


          <div class="receipt-actions">


            <a class="receipt-btn receipt-btn-secondary" href="profile.php">

              ← Back to Profile

            </a>


            <button class="receipt-btn receipt-btn-gold" type="button" onclick="window.print()">

              Print Receipt

            </button>


          </div>


        </div>


        <!-- =============================================
                 RECEIPT
                 ============================================= -->

        <article class="receipt">


          <!-- =========================================
                     HEADER
                     ========================================= -->

          <header class="receipt-header">


            <div class="receipt-brand">


              <span class="receipt-brand-label">

                Official Booking Receipt

              </span>


              <h2>

                Plantastic Events

              </h2>


              <p>

                Your event. Your vision.
                Perfectly planned.

              </p>


            </div>


            <div class="receipt-number">


              <span class="receipt-number-label">

                Receipt Number

              </span>


              <strong>

                <?php
                echo e(
                  $receiptNumber
                );
                ?>

              </strong>


            </div>


          </header>


          <!-- =========================================
                     EVENT SUMMARY
                     ========================================= -->

          <section class="receipt-summary">


            <div class="receipt-event">


              <span class="receipt-status">

                Confirmed

              </span>


              <h3>

                <?php
                echo e(
                  $booking['eve_name']
                );
                ?>

              </h3>


              <p>

                <?php
                echo e(
                  $eventTypeDisplay
                );
                ?>

                · Booking #

                <?php
                echo (int) $booking['b_id'];
                ?>

              </p>


            </div>


            <div class="receipt-booked-date">


              <span>
                Booked On
              </span>


              <strong>

                <?php
                echo e(
                  $bookingDateDisplay
                );
                ?>

              </strong>


            </div>


          </section>


          <!-- =========================================
                     CUSTOMER + ORGANIZER
                     ========================================= -->

          <section class="receipt-parties">


            <!-- Customer -->

            <div class="receipt-party">


              <span class="receipt-party-label">

                Customer

              </span>


              <div class="receipt-party-name">

                <?php
                echo e(
                  $booking['customer_name']
                );
                ?>

              </div>


              <div class="receipt-party-contact">

                <?php
                echo e(
                  $booking['customer_email']
                );
                ?>

                <br>

                <?php
                echo e(
                  $booking['customer_contact']
                );
                ?>

              </div>


            </div>


            <!-- Organizer -->

            <div class="receipt-party">


              <span class="receipt-party-label">

                Organizer

              </span>


              <div class="receipt-party-name">

                <?php
                echo e(
                  $organizerDisplay
                );
                ?>

              </div>


              <?php if (
                $companyName !== '' &&
                $organizerName !== ''
              ): ?>


                <div class="receipt-party-company">

                  Managed by

                  <?php
                  echo e(
                    $organizerName
                  );
                  ?>

                </div>


              <?php endif; ?>


              <div class="receipt-party-contact">

                <?php
                echo e(
                  $booking['org_email']
                );
                ?>

                <br>

                <?php
                echo e(
                  $booking['mobile_no']
                );
                ?>

              </div>


            </div>


          </section>


          <!-- =========================================
                     EVENT DETAILS
                     ========================================= -->

          <section class="receipt-details">


            <div class="receipt-section-title">


              <span>
                Booking Information
              </span>


              <h3>
                Event Details
              </h3>


            </div>


            <div class="receipt-details-grid">


              <!-- Start -->

              <div class="receipt-detail">


                <div class="receipt-detail-label">
                  Start
                </div>


                <div class="receipt-detail-value">

                  <?php
                  echo e(
                    $startDateDisplay
                  );
                  ?>

                  <?php if (
                    $startTimeDisplay !== '—'
                  ): ?>

                    ·

                    <?php
                    echo e(
                      $startTimeDisplay
                    );
                    ?>

                  <?php endif; ?>


                </div>


              </div>


              <!-- End -->

              <div class="receipt-detail">


                <div class="receipt-detail-label">
                  End
                </div>


                <div class="receipt-detail-value">

                  <?php
                  echo e(
                    $endDateDisplay
                  );
                  ?>

                </div>


              </div>


              <!-- Venue -->

              <div class="receipt-detail full">


                <div class="receipt-detail-label">
                  Venue
                </div>


                <div class="receipt-detail-value">

                  <?php
                  echo e(
                    $venueDisplay
                  );
                  ?>

                </div>


              </div>


              <!-- Guests -->

              <div class="receipt-detail">


                <div class="receipt-detail-label">
                  Guests
                </div>


                <div class="receipt-detail-value">

                  <?php
                  echo e(
                    $booking['expect_guests']
                    ?: '—'
                  );
                  ?>

                </div>


              </div>


              <!-- Theme -->

              <div class="receipt-detail">


                <div class="receipt-detail-label">
                  Theme
                </div>


                <div class="receipt-detail-value">

                  <?php
                  echo e(
                    $booking['theme']
                    ?: '—'
                  );
                  ?>

                </div>


              </div>


              <!-- Catering -->

              <div class="receipt-detail">


                <div class="receipt-detail-label">
                  Catering
                </div>


                <div class="receipt-detail-value">

                  <?php
                  echo e(
                    $booking['catering']
                    ?: '—'
                  );
                  ?>

                </div>


              </div>


              <!-- Photography -->

              <div class="receipt-detail">


                <div class="receipt-detail-label">
                  Photography
                </div>


                <div class="receipt-detail-value">

                  <?php
                  echo e(
                    $booking['photography']
                    ?: '—'
                  );
                  ?>

                </div>


              </div>


              <!-- Budget -->

              <div class="receipt-detail">


                <div class="receipt-detail-label">
                  Budget
                </div>


                <div class="receipt-detail-value budget">

                  <?php
                  echo e(
                    $booking['event_budget']
                    ?: '—'
                  );
                  ?>

                </div>


              </div>


              <!-- Payment -->

              <div class="receipt-detail">


                <div class="receipt-detail-label">
                  Payment
                </div>


                <div class="receipt-detail-value">

                  <?php
                  echo e(
                    $booking['payment_method']
                    ?: '—'
                  );
                  ?>

                </div>


              </div>


              <!-- Notes -->

              <div class="receipt-detail full">


                <div class="receipt-detail-label">
                  Notes
                </div>


                <div class="receipt-detail-value">

                  <?php
                  echo e(
                    $booking['eve_desc']
                    ?: '—'
                  );
                  ?>

                </div>


              </div>


            </div>


          </section>


          <!-- =========================================
                     NOTICE
                     ========================================= -->

          <div class="receipt-notice">


            <div class="receipt-notice-icon">

              i

            </div>


            <div>

              Keep this receipt for your records.
              If you need to update event details,
              contact the organizer through your
              Plantastic Events profile.

            </div>


          </div>


          <!-- =========================================
                     FOOTER
                     ========================================= -->

          <footer class="receipt-footer">


            <p>

              <strong>
                Plantastic Events
              </strong>

              <br>

              This receipt confirms that the
              organizer has accepted the booking
              request shown above.

            </p>


            <div class="receipt-footer-brand">

              Plantastic

            </div>


          </footer>


        </article>


      </div>


    </main>


    <!-- =====================================================
         FOOTER CONTACT
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
         FOOTER BOTTOM
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

  <script src="js/customer-navbar-dropdown-final.js"></script>


</body>

</html>