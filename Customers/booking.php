<?php

require_once __DIR__ . '/../includes/bootstrap.php';

/*
|--------------------------------------------------------------------------
| CUSTOMER SESSION
|--------------------------------------------------------------------------
*/

$user = $_SESSION['customer'] ?? null;
$status = $user ? 'true' : 'false';


/*
|--------------------------------------------------------------------------
| LOAD AVAILABLE EVENT CATEGORIES
|--------------------------------------------------------------------------
|
| Database ke actual event_type values use honge.
| Abhi:
| Anniversery
| Birthday
| Ceremony
| Parties
|
| Future me Corporate/Others add honge to automatically show honge.
|
*/

$categories = [];

$categoryQuery = $conn->query(
    "SELECT DISTINCT e.event_type
     FROM tbl_event e
     INNER JOIN tbl_organizer o ON o.org_id = e.org_id
     WHERE
        o.approve = 'Approve'
        AND (o.block = '' OR o.block IS NULL)
        AND e.event_type IS NOT NULL
        AND TRIM(e.event_type) <> ''
     ORDER BY e.event_type ASC"
);

if ($categoryQuery) {

    while ($categoryRow = $categoryQuery->fetch_assoc()) {

        $categories[] = $categoryRow['event_type'];

    }

}


/*
|--------------------------------------------------------------------------
| CATEGORY DISPLAY NAME
|--------------------------------------------------------------------------
*/

function category_display_name(string $type): string
{
    $names = [

        'Anniversery' => 'Anniversary',
        'Birthday' => 'Birthday',
        'Ceremony' => 'Ceremony',
        'Parties' => 'Parties',
        'Couple_Exclusive' => 'Couple Exclusive',
        'Corporate' => 'Corporate',
        'Others' => 'Others',

    ];

    return $names[$type] ?? str_replace('_', ' ', $type);
}


/*
|--------------------------------------------------------------------------
| CATEGORY ID
|--------------------------------------------------------------------------
*/

function category_id(string $type): string
{
    return strtolower(
        preg_replace(
            '/[^a-zA-Z0-9]+/',
            '-',
            trim($type)
        )
    );
}


/*
|--------------------------------------------------------------------------
| GET EVENT PACKAGES
|--------------------------------------------------------------------------
*/

function ems_event_cards(mysqli $conn, string $type)
{
    $stmt = $conn->prepare(
        "SELECT
            e.evn_id,
            e.image1,
            e.eve_name,
            e.event_type,

            o.org_id,
            o.user_name,
            o.company_name,
            o.city

         FROM tbl_event e

         INNER JOIN tbl_organizer o
            ON o.org_id = e.org_id

         WHERE
            e.event_type = ?
            AND o.approve = 'Approve'
            AND (o.block = '' OR o.block IS NULL)

         ORDER BY e.evn_id DESC"
    );

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('s', $type);

    $stmt->execute();

    return $stmt->get_result();
}


/*
|--------------------------------------------------------------------------
| COUNT ALL AVAILABLE PACKAGES
|--------------------------------------------------------------------------
*/

$totalPackages = 0;

$countResult = $conn->query(
    "SELECT COUNT(*) AS total

     FROM tbl_event e

     INNER JOIN tbl_organizer o
        ON o.org_id = e.org_id

     WHERE
        o.approve = 'Approve'
        AND (o.block = '' OR o.block IS NULL)"
);

if ($countResult) {

    $countRow = $countResult->fetch_assoc();

    $totalPackages = (int) ($countRow['total'] ?? 0);

}


/*
|--------------------------------------------------------------------------
| COUNT ORGANIZERS
|--------------------------------------------------------------------------
*/

$totalOrganizers = 0;

$organizerCount = $conn->query(
    "SELECT COUNT(DISTINCT o.org_id) AS total

     FROM tbl_organizer o

     INNER JOIN tbl_event e
        ON e.org_id = o.org_id

     WHERE
        o.approve = 'Approve'
        AND (o.block = '' OR o.block IS NULL)"
);

if ($organizerCount) {

    $organizerRow = $organizerCount->fetch_assoc();

    $totalOrganizers = (int) ($organizerRow['total'] ?? 0);

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Organizers | Plantastic Events</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">


    <!-- =====================================================
         MAIN THEME
         ===================================================== -->

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/responsive.css">


    <!-- =====================================================
         FAVICON
         ===================================================== -->

    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">

    <link rel="icon" type="image/png" href="images/favicon/favicon-32x32.png" sizes="32x32">

    <link rel="icon" type="image/png" href="images/favicon/favicon-16x16.png" sizes="16x16">


    <!-- =====================================================
         CURRENT NAVBAR
         ===================================================== -->

    <link rel="stylesheet" href="css/customer-navbar-logo.css">

    <link rel="stylesheet" href="css/customer-navbar-dropdown-final.css">


    <!-- =====================================================
         ORGANIZERS PAGE
         ===================================================== -->

    <style>
        /* =====================================================
           PAGE
           ===================================================== */

        body {
            background: #f8f6f1;
        }

        .organizers-page {
            background:
                linear-gradient(180deg,
                    #f7f3eb 0%,
                    #ffffff 450px);
        }


        /* =====================================================
           HERO
           ===================================================== */

        .organizers-hero {
            position: relative;

            padding: 95px 20px 80px;

            overflow: hidden;

            background:
                radial-gradient(circle at 15% 20%,
                    rgba(212, 175, 55, .18),
                    transparent 32%),
                radial-gradient(circle at 85% 80%,
                    rgba(212, 175, 55, .09),
                    transparent 30%),
                linear-gradient(135deg,
                    #0b0b0c 0%,
                    #17140e 55%,
                    #211a0d 100%);
        }


        .organizers-hero::before {

            content: "";

            position: absolute;

            width: 360px;
            height: 360px;

            top: -220px;
            right: -100px;

            border: 1px solid rgba(212, 175, 55, .15);

            border-radius: 50%;

        }


        .organizers-hero::after {

            content: "";

            position: absolute;

            width: 260px;
            height: 260px;

            left: -150px;
            bottom: -160px;

            border: 1px solid rgba(212, 175, 55, .12);

            border-radius: 50%;

        }


        .organizers-hero .container {
            position: relative;
            z-index: 2;
        }


        .organizers-eyebrow {

            display: inline-flex;

            align-items: center;

            gap: 10px;

            margin-bottom: 18px;

            color: #d4af37;

            font-size: 13px;

            font-weight: 800;

            letter-spacing: 2px;

            text-transform: uppercase;

        }


        .organizers-eyebrow::before {

            content: "";

            width: 32px;

            height: 2px;

            background: #d4af37;

        }


        .organizers-hero h1 {

            max-width: 760px;

            margin: 0;

            color: #ffffff;

            font-size: clamp(38px, 5vw, 66px);

            font-weight: 800;

            line-height: 1.05;

            letter-spacing: -1.5px;

        }


        .organizers-hero h1 span {

            color: #d4af37;

        }


        .organizers-hero-description {

            max-width: 690px;

            margin: 22px 0 0;

            color: #cfc8bb;

            font-size: 17px;

            line-height: 1.75;

        }


        /* =====================================================
           HERO STATS
           ===================================================== */

        .organizer-stats {

            display: flex;

            flex-wrap: wrap;

            gap: 14px;

            margin-top: 34px;

        }


        .organizer-stat {

            min-width: 145px;

            padding: 14px 18px;

            border: 1px solid rgba(212, 175, 55, .18);

            border-radius: 14px;

            background: rgba(255, 255, 255, .045);

            backdrop-filter: blur(5px);

        }


        .organizer-stat strong {

            display: block;

            color: #d4af37;

            font-size: 23px;

            font-weight: 800;

            line-height: 1.1;

        }


        .organizer-stat span {

            display: block;

            margin-top: 5px;

            color: #aaa398;

            font-size: 12px;

            font-weight: 700;

            letter-spacing: .5px;

            text-transform: uppercase;

        }


        /* =====================================================
           CATEGORY NAVIGATION
           ===================================================== */

        .organizer-category-wrap {

            position: relative;

            z-index: 5;

            margin-top: -30px;

        }


        .organizer-category-nav {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 13px;

            overflow-x: auto;

            scrollbar-width: none;

            background: #ffffff;

            border: 1px solid #eee8dc;

            border-radius: 18px;

            box-shadow:
                0 14px 45px rgba(34, 28, 18, .10);

        }


        .organizer-category-nav::-webkit-scrollbar {
            display: none;
        }


        .organizer-category-nav a {

            flex: 0 0 auto;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-height: 43px;

            padding: 10px 19px;

            color: #4d463b;

            background: #f7f4ed;

            border: 1px solid transparent;

            border-radius: 11px;

            font-size: 13px;

            font-weight: 800;

            text-decoration: none;

            transition:
                background .2s ease,
                color .2s ease,
                border-color .2s ease,
                transform .2s ease;

        }


        .organizer-category-nav a:hover {

            color: #16130e;

            background: #f2e4b2;

            border-color: #dfc46c;

            transform: translateY(-1px);

        }


        /* =====================================================
           CATEGORIES
           ===================================================== */

        .organizer-catalog {

            padding: 70px 0 90px;

        }


        .organizer-category {

            scroll-margin-top: 85px;

            margin-bottom: 75px;

        }


        .organizer-category:last-child {
            margin-bottom: 0;
        }


        .organizer-section-head {

            display: flex;

            align-items: flex-end;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 25px;

            padding-bottom: 18px;

            border-bottom: 1px solid #e9e2d5;

        }


        .organizer-section-head-left {

            display: flex;

            align-items: center;

            gap: 15px;

        }


        .organizer-section-number {

            display: flex;

            align-items: center;

            justify-content: center;

            width: 48px;

            height: 48px;

            flex: 0 0 48px;

            border-radius: 14px;

            color: #18140d;

            background:
                linear-gradient(135deg,
                    #f0d77c,
                    #c99d24);

            font-size: 14px;

            font-weight: 900;

        }


        .organizer-section-head h2 {

            margin: 0;

            color: #17130e;

            font-size: 30px;

            font-weight: 800;

        }


        .organizer-section-head p {

            margin: 5px 0 0;

            color: #8b8275;

            font-size: 14px;

        }


        /* =====================================================
           CARDS GRID
           ===================================================== */

        .organizer-grid {

            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 24px;

        }


        .organizer-card {

            position: relative;

            min-width: 0;

            overflow: hidden;

            background: #ffffff;

            border: 1px solid #ebe4d8;

            border-radius: 18px;

            box-shadow:
                0 9px 30px rgba(38, 30, 18, .065);

            transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;

        }


        .organizer-card:hover {

            transform: translateY(-5px);

            border-color: #ddc675;

            box-shadow:
                0 18px 42px rgba(38, 30, 18, .12);

        }


        /* =====================================================
           CARD IMAGE
           ===================================================== */

        .organizer-card-image {

            position: relative;

            height: 215px;

            overflow: hidden;

            background:
                linear-gradient(135deg,
                    #181511,
                    #2a2112);

        }


        .organizer-card-image img {

            display: block;

            width: 100%;

            height: 100%;

            object-fit: cover;

            transition: transform .45s ease;

        }


        .organizer-card:hover .organizer-card-image img {

            transform: scale(1.045);

        }


        .organizer-image-placeholder {

            display: flex;

            align-items: center;

            justify-content: center;

            width: 100%;

            height: 100%;

            padding: 20px;

            color: rgba(212, 175, 55, .8);

            font-size: 17px;

            font-weight: 800;

            letter-spacing: 1px;

            text-align: center;

        }


        .organizer-category-badge {

            position: absolute;

            top: 15px;

            left: 15px;

            z-index: 2;

            padding: 7px 11px;

            color: #19150e;

            background: rgba(239, 211, 111, .95);

            border-radius: 999px;

            font-size: 11px;

            font-weight: 900;

            letter-spacing: .6px;

            text-transform: uppercase;

        }


        /* =====================================================
           CARD CONTENT
           ===================================================== */

        .organizer-card-content {

            padding: 21px;

        }


        .organizer-card h3 {

            margin: 0;

            color: #18140f;

            font-size: 20px;

            font-weight: 800;

            line-height: 1.3;

        }


        .organizer-company {

            margin-top: 8px;

            color: #8a6417;

            font-size: 14px;

            font-weight: 700;

        }


        .organizer-meta {

            display: flex;

            align-items: center;

            gap: 7px;

            min-height: 21px;

            margin-top: 8px;

            color: #81796d;

            font-size: 13px;

        }


        .organizer-meta-dot {

            width: 5px;

            height: 5px;

            flex: 0 0 5px;

            border-radius: 50%;

            background: #d4af37;

        }


        /* =====================================================
           CARD ACTION
           ===================================================== */

        .organizer-card-action {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 12px;

            margin-top: 20px;

            padding-top: 17px;

            border-top: 1px solid #eee9e0;

        }


        .organizer-approved {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            color: #6d6559;

            font-size: 11px;

            font-weight: 800;

            letter-spacing: .3px;

            text-transform: uppercase;

        }


        .organizer-approved::before {

            content: "✓";

            display: flex;

            align-items: center;

            justify-content: center;

            width: 20px;

            height: 20px;

            color: #1d180e;

            background: #ead170;

            border-radius: 50%;

            font-size: 11px;

        }


        .organizer-view-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            min-height: 40px;

            padding: 9px 15px;

            color: #17130d !important;

            background:
                linear-gradient(135deg,
                    #ecd271,
                    #c89c23);

            border-radius: 9px;

            font-size: 12px;

            font-weight: 900;

            text-decoration: none !important;

            transition:
                transform .2s ease,
                box-shadow .2s ease;

        }


        .organizer-view-btn:hover {

            color: #17130d !important;

            transform: translateY(-1px);

            box-shadow:
                0 7px 17px rgba(184, 138, 27, .22);

        }


        /* =====================================================
           EMPTY STATE
           ===================================================== */

        .organizer-empty {

            grid-column: 1 / -1;

            padding: 45px 25px;

            color: #857c6e;

            background: #ffffff;

            border: 1px dashed #dcd2c1;

            border-radius: 18px;

            text-align: center;

        }


        .organizer-empty strong {

            display: block;

            margin-bottom: 7px;

            color: #272117;

            font-size: 17px;

        }


        /* =====================================================
           NO ORGANIZERS AT ALL
           ===================================================== */

        .organizer-global-empty {

            padding: 80px 20px;

            text-align: center;

        }


        .organizer-global-empty h2 {

            margin-bottom: 10px;

            color: #201b13;

            font-size: 30px;

            font-weight: 800;

        }


        .organizer-global-empty p {

            max-width: 560px;

            margin: 0 auto;

            color: #82796b;

            line-height: 1.7;

        }


        /* =====================================================
           TABLET
           ===================================================== */

        @media (max-width: 991px) {

            .organizers-hero {

                padding:
                    75px 20px 65px;

            }


            .organizer-grid {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));

            }


            .organizer-catalog {

                padding-top: 60px;

            }

        }


        /* =====================================================
           MOBILE
           ===================================================== */

        @media (max-width: 767px) {

            .organizers-hero {

                padding:
                    62px 16px 60px;

            }


            .organizers-hero h1 {

                font-size:
                    clamp(34px, 11vw, 48px);

            }


            .organizers-hero-description {

                font-size: 15px;

                line-height: 1.65;

            }


            .organizer-stats {

                gap: 10px;

            }


            .organizer-stat {

                flex: 1 1 calc(50% - 10px);

                min-width: 130px;

                padding: 13px 15px;

            }


            .organizer-category-wrap {

                margin-top: -24px;

                padding-left: 10px;

                padding-right: 10px;

            }


            .organizer-category-nav {

                border-radius: 15px;

            }


            .organizer-catalog {

                padding:
                    52px 0 65px;

            }


            .organizer-category {

                margin-bottom: 55px;

            }


            .organizer-section-head {

                align-items: center;

                margin-bottom: 20px;

            }


            .organizer-section-number {

                width: 43px;

                height: 43px;

                flex-basis: 43px;

            }


            .organizer-section-head h2 {

                font-size: 24px;

            }


            .organizer-grid {

                grid-template-columns: 1fr;

                gap: 18px;

            }


            .organizer-card-image {

                height: 205px;

            }

        }


        /* =====================================================
           SMALL MOBILE
           ===================================================== */

        @media (max-width: 420px) {

            .organizer-card-content {

                padding: 18px;

            }


            .organizer-card-action {

                align-items: stretch;

                flex-direction: column;

            }


            .organizer-view-btn {

                width: 100%;

            }

        }
    </style>

</head>


<body>


    <div class="boxed_wrapper">


        <!-- =====================================================
         HEADER
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


                        <!-- Hamburger -->

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


                                <!-- Events -->

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


                                <!-- ORGANIZERS -->

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


                                <!-- About -->

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


                                <!-- Login -->

                                <li>


                                    <a href="login.php" id="joinButton">

                                        JOIN NOW / JOIN US

                                    </a>


                                </li>


                            </ul>


                        </div>


                    </nav>


                    <!-- Search -->

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
         ORGANIZERS PAGE
         ===================================================== -->

        <main class="organizers-page">


            <!-- =================================================
             HERO
             ================================================= -->

            <section class="organizers-hero">


                <div class="container">


                    <div class="organizers-eyebrow">

                        Plantastic Organizers

                    </div>


                    <h1>

                        Find the right team for your

                        <span>
                            perfect event.
                        </span>

                    </h1>


                    <p class="organizers-hero-description">

                        Browse event packages from admin-approved
                        organizers. Choose your event category,
                        explore their work and continue directly
                        to booking.

                    </p>


                    <div class="organizer-stats">


                        <div class="organizer-stat">


                            <strong>

                                <?php echo $totalOrganizers; ?>

                            </strong>


                            <span>

                                Approved Organizers

                            </span>


                        </div>


                        <div class="organizer-stat">


                            <strong>

                                <?php echo $totalPackages; ?>

                            </strong>


                            <span>

                                Event Packages

                            </span>


                        </div>


                        <div class="organizer-stat">


                            <strong>

                                <?php echo count($categories); ?>

                            </strong>


                            <span>

                                Categories

                            </span>


                        </div>


                    </div>


                </div>


            </section>


            <?php if (!empty($categories)): ?>


                <!-- =============================================
                 CATEGORY NAVIGATION
                 ============================================= -->

                <div class="organizer-category-wrap">


                    <div class="container">


                        <div class="organizer-category-nav">


                            <?php foreach ($categories as $type): ?>


                                <a href="#<?php echo htmlspecialchars(category_id($type)); ?>">

                                    <?php
                                    echo htmlspecialchars(
                                        category_display_name($type)
                                    );
                                    ?>

                                </a>


                            <?php endforeach; ?>


                        </div>


                    </div>


                </div>


                <!-- =============================================
                 CATEGORY SECTIONS
                 ============================================= -->

                <section class="organizer-catalog">


                    <div class="container">


                        <?php

                        $sectionNumber = 1;

                        foreach ($categories as $type):

                            $events = ems_event_cards(
                                $conn,
                                $type
                            );

                            $displayName =
                                category_display_name($type);

                            $sectionId =
                                category_id($type);

                            ?>


                            <div class="organizer-category" id="<?php echo htmlspecialchars($sectionId); ?>">


                                <!-- Heading -->

                                <div class="organizer-section-head">


                                    <div class="organizer-section-head-left">


                                        <div class="organizer-section-number">


                                            <?php

                                            echo str_pad(
                                                (string) $sectionNumber,
                                                2,
                                                '0',
                                                STR_PAD_LEFT
                                            );

                                            ?>


                                        </div>


                                        <div>


                                            <h2>

                                                <?php
                                                echo htmlspecialchars(
                                                    $displayName
                                                );
                                                ?>

                                            </h2>


                                            <p>

                                                Explore approved
                                                <?php
                                                echo htmlspecialchars(
                                                    strtolower($displayName)
                                                );
                                                ?>
                                                event packages.

                                            </p>


                                        </div>


                                    </div>


                                </div>


                                <!-- Cards -->

                                <div class="organizer-grid">


                                    <?php

                                    if (
                                        $events &&
                                        $events->num_rows > 0
                                    ):

                                        while (
                                            $row = $events->fetch_assoc()
                                        ):

                                            $eventName =
                                                trim(
                                                    (string) (
                                                        $row['eve_name']
                                                        ?? ''
                                                    )
                                                );

                                            if ($eventName === '') {
                                                $eventName = $displayName;
                                            }


                                            $company =
                                                trim(
                                                    (string) (
                                                        $row['company_name']
                                                        ?? ''
                                                    )
                                                );

                                            $organizerName =
                                                trim(
                                                    (string) (
                                                        $row['user_name']
                                                        ?? ''
                                                    )
                                                );

                                            $displayOrganizer =
                                                $company !== ''
                                                ? $company
                                                : $organizerName;


                                            $city =
                                                trim(
                                                    (string) (
                                                        $row['city']
                                                        ?? ''
                                                    )
                                                );


                                            $hasImage =
                                                isset($row['image1']) &&
                                                $row['image1'] !== null &&
                                                $row['image1'] !== '';

                                            ?>


                                            <article class="organizer-card">


                                                <!-- Image -->

                                                <div class="organizer-card-image">


                                                    <span class="organizer-category-badge">

                                                        <?php
                                                        echo htmlspecialchars(
                                                            $displayName
                                                        );
                                                        ?>

                                                    </span>


                                                    <?php if ($hasImage): ?>


                                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image1']); ?>"
                                                            alt="<?php echo htmlspecialchars($eventName); ?>" loading="lazy">


                                                    <?php else: ?>


                                                        <div class="organizer-image-placeholder">

                                                            Plantastic Events

                                                        </div>


                                                    <?php endif; ?>


                                                </div>


                                                <!-- Content -->

                                                <div class="organizer-card-content">


                                                    <h3>

                                                        <?php
                                                        echo htmlspecialchars(
                                                            $eventName
                                                        );
                                                        ?>

                                                    </h3>


                                                    <div class="organizer-company">

                                                        <?php
                                                        echo htmlspecialchars(
                                                            $displayOrganizer
                                                        );
                                                        ?>

                                                    </div>


                                                    <?php if ($city !== ''): ?>


                                                        <div class="organizer-meta">


                                                            <span class="organizer-meta-dot"></span>


                                                            <span>

                                                                <?php
                                                                echo htmlspecialchars(
                                                                    $city
                                                                );
                                                                ?>

                                                            </span>


                                                        </div>


                                                    <?php endif; ?>


                                                    <div class="organizer-card-action">


                                                        <span class="organizer-approved">

                                                            Approved

                                                        </span>


                                                        <a class="organizer-view-btn"
                                                            href="project-single.php?display_org=<?php echo (int) $row['org_id']; ?>">

                                                            View & Book

                                                            <span>
                                                                →
                                                            </span>

                                                        </a>


                                                    </div>


                                                </div>


                                            </article>


                                            <?php

                                        endwhile;

                                    else:

                                        ?>


                                        <div class="organizer-empty">


                                            <strong>

                                                No organizers available yet.

                                            </strong>


                                            Packages for this category
                                            will appear here once an
                                            approved organizer adds them.


                                        </div>


                                    <?php endif; ?>


                                </div>


                            </div>


                            <?php

                            $sectionNumber++;

                        endforeach;

                        ?>


                    </div>


                </section>


            <?php else: ?>


                <!-- =============================================
                 EMPTY DATABASE
                 ============================================= -->

                <section class="organizer-global-empty">


                    <div class="container">


                        <h2>

                            Organizers are coming soon.

                        </h2>


                        <p>

                            There are currently no approved event
                            packages available. Once organizers add
                            packages and receive approval, they will
                            automatically appear here.

                        </p>


                    </div>


                </section>


            <?php endif; ?>


        </main>


        <!-- =====================================================
         FOOTER CONTACT INFO
         ===================================================== -->

        <section class="footer-contact-info-area">


            <div class="container">


                <div class="row">


                    <div class="col-xl-12">


                        <ul class="footer-contact-info clearfix">


                            <!-- Address -->

                            <li>


                                <div class="single-footer-contact-info">


                                    <div class="inner">


                                        <div class="icon">


                                            <span class="icon-global"></span>


                                        </div>


                                        <div class="text">


                                            <p>

                                                Sr. No. 149, VIP Road, Vesu,

                                                <br>

                                                Bharthana, Surat,
                                                Gujarat 395007

                                            </p>


                                        </div>


                                    </div>


                                </div>


                            </li>


                            <!-- Phone -->

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


                            <!-- Email -->

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
     SCROLL TO TOP
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
     LOGIN / PROFILE NAVIGATION
     ========================================================= -->

    <script>

        const isLoggedIn = <?php echo $status; ?>;


        if (isLoggedIn) {


            const navButtons =
                document.getElementById("navButtons");


            const joinBtn =
                document.getElementById("joinButton");


            if (joinBtn) {

                joinBtn.remove();

            }


            if (navButtons) {


                const profileBtn =
                    document.createElement("li");


                profileBtn.className =
                    "nav-item";


                profileBtn.innerHTML =
                    '<a href="profile.php">Profile</a>';


                navButtons.appendChild(profileBtn);

            }

        }

    </script>


    <!-- Current navbar mobile/dropdown logic -->

    <script src="js/customer-navbar-dropdown-final.js"></script>


</body>

</html>