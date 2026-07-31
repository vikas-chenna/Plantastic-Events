<?php

session_start();
include('../conn.php');

// Login required karna ho to uncomment:
//
// if (!isset($_SESSION['customer'])) {
//     header('location:login.php');
//     exit;
// }

$user = $_SESSION['customer'] ?? null;
$status = $user ? 'true' : 'false';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>About Us || Plantastic Events</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">


    <!-- =====================================================
         MAIN CSS
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
         NAVBAR CSS
         ===================================================== -->

    <link rel="stylesheet" href="css/customer-navbar-logo.css?v=20260728-sticky2">

    <link rel="stylesheet" href="css/customer-navbar-dropdown-final.css">


    <!-- =====================================================
         ABOUT PAGE FIX
         ===================================================== -->

    <style>
        /*
         * Prevent old animation/theme rules from hiding
         * counters or location cards.
         */

        .company-overview-area .single-fact-counter,
        .brand-area.style2 .single-brand-item {
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
            animation: none !important;
        }


        /* Brand/location section must grow naturally */

        .brand-area.style2 {
            position: relative !important;
            display: block !important;
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
        }


        .brand-area.style2 .container,
        .brand-area.style2 .row,
        .brand-area.style2 .col-xl-12 {
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
        }


        /* =====================================================
           TABLET + MOBILE
           ===================================================== */

        @media only screen and (max-width: 991px) {

            .company-overview-area,
            .team-area,
            .brand-area.style2 {
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
            }


            .company-overview-area .container,
            .company-overview-area .row,
            .team-area .container,
            .team-area .row,
            .brand-area.style2 .container,
            .brand-area.style2 .row {
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
            }

        }


        /* =====================================================
           MOBILE
           ===================================================== */

        @media only screen and (max-width: 767px) {

            /*
             * Fact counters
             */

            .company-overview-area .fact-counter {
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
            }


            .company-overview-area .single-fact-counter {
                display: block !important;
                position: relative !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
            }


            /*
             * Team
             */

            .team-area {
                display: block !important;
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
            }


            .team-area .single-team-member {
                display: block !important;
                position: relative !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
            }


            /*
             * Locations
             */

            .brand-area.style2 {
                display: block !important;
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
            }


            .brand-area.style2 ul {
                display: block !important;
                width: 100% !important;
                height: auto !important;
                min-height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible !important;
            }


            .brand-area.style2 .single-brand-item {
                display: block !important;
                position: relative !important;

                float: none !important;

                width: 100% !important;
                max-width: 100% !important;

                height: auto !important;
                min-height: 0 !important;

                margin: 0 0 25px 0 !important;

                opacity: 1 !important;
                visibility: visible !important;

                transform: none !important;
                animation: none !important;

                overflow: visible !important;
            }


            .brand-area.style2 .single-brand-item:last-child {
                margin-bottom: 0 !important;
            }


            .brand-area.style2 .single-brand-item a {
                display: block !important;
                width: 100% !important;
            }


            .brand-area.style2 .single-brand-item img {
                display: block !important;
                max-width: 100% !important;
                height: auto !important;
                margin: 0 auto !important;
            }


            .brand-area.style2 .overlay-content {
                visibility: visible;
            }

        }
    </style>

</head>


<body>


    <div class="boxed_wrapper">


        <!-- =====================================================
         MAIN HEADER
         ===================================================== -->

        <header class="main-header header-style2 stricky">


            <div class="inner-container clearfix">


                <!-- Logo -->

                <div class="logo-box-style2 float-left">


                    <a href="index-3.php">

                        <img src="images/plantastic-logo-modern.png" alt="Plantastic Events">

                    </a>


                </div>


                <!-- Menu -->

                <div class="main-menu-box float-right">


                    <nav class="main-menu style2 clearfix">


                        <!-- Mobile Hamburger -->

                        <div class="navbar-header clearfix">


                            <button type="button" class="navbar-toggle" aria-label="Toggle navigation">

                                <span class="icon-bar"></span>

                                <span class="icon-bar"></span>

                                <span class="icon-bar"></span>

                            </button>


                        </div>


                        <!-- Navigation -->

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
                                                Anniversaries/Jubilees
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

                                <!-- Organizers -->
                                <li>
                                    <a href="booking.php">Organizers</a>
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


                                    <form method="post" action="#">


                                        <div class="form-group">


                                            <input type="search" name="search" placeholder="Search Here" required>


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
         BREADCRUMB
         ===================================================== -->

        <section class="breadcrumb-area" style="background-image: url(images/resources/breadcrumb-bg.jpg);">


            <div class="container">


                <div class="row">


                    <div class="col-xl-12">


                        <div class="inner-content clearfix">


                            <div class="title">


                                <h1>

                                    Plan + Fantastic Events =

                                    <br>

                                    Plantastic Events

                                </h1>


                            </div>


                            <div class="breadcrumb-menu float-right">


                                <ul class="clearfix">


                                    <li>

                                        <a href="index-3.php">
                                            Home
                                        </a>

                                    </li>


                                    <li class="active">

                                        About Us

                                    </li>


                                </ul>


                            </div>


                        </div>


                    </div>


                </div>


            </div>


        </section>


        <!-- =====================================================
         COMPANY OVERVIEW
         ===================================================== -->

        <section class="company-overview-area">


            <div class="container">


                <!-- Introduction -->

                <div class="row">


                    <div class="col-xl-12">


                        <div class="intro-box clearfix">


                            <div class="sec-title">


                                <p>
                                    Plantastic Events
                                </p>


                                <div class="title">

                                    Modern & Memorable

                                    <br>

                                    <span>
                                        Event Management Experts in India
                                    </span>

                                </div>


                            </div>


                            <div class="text">


                                <p>

                                    We believe that event planning is one of
                                    the most important investments you’ll ever
                                    make. Whether it’s a wedding, corporate
                                    event, or personal celebration, our
                                    dedication ensures your event is seamlessly
                                    planned and beautifully executed.

                                </p>


                            </div>


                        </div>


                    </div>


                </div>


                <!-- =================================================
                 HISTORY
                 ================================================= -->

                <div class="row">


                    <div class="col-xl-12">


                        <div class="history-content-box clearfix">


                            <div class="single-history-content">


                                <div class="img-box">


                                    <div class="inner">


                                        <img src="images/resources/history-1.jpg" alt="Plantastic Events History">


                                    </div>


                                </div>


                                <div class="text-box">


                                    <div class="inner">


                                        <div class="date">

                                            <h3>
                                                2024
                                            </h3>

                                        </div>


                                        <div class="title">


                                            <h3>

                                                Plantastic Events was founded

                                                <br>

                                                as a third-year BCA project by
                                                a passionate team of four.

                                            </h3>


                                        </div>


                                        <div class="text">


                                            <p>

                                                We believe in creating events
                                                that leave lasting impressions.
                                                Guided by dedication and creativity,
                                                we transform every celebration into
                                                a unique experience, overcoming
                                                challenges with unwavering
                                                commitment to excellence.

                                            </p>


                                        </div>


                                    </div>


                                </div>


                            </div>


                        </div>


                    </div>


                </div>


                <!-- =================================================
                 FACT COUNTERS
                 ================================================= -->

                <div class="row fact-counter">


                    <!-- Experience -->

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">


                        <div class="single-fact-counter">


                            <div class="count-box">


                                <h1>


                                    <span class="timer" data-from="1" data-to="36" data-speed="5000"
                                        data-refresh-interval="50">

                                        36

                                    </span>


                                </h1>


                            </div>


                            <div class="title">


                                <h3>

                                    Days of

                                    <br>

                                    Experience

                                </h3>


                            </div>


                        </div>


                    </div>


                    <!-- Projects -->

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">


                        <div class="single-fact-counter">


                            <div class="count-box">


                                <h1>


                                    <span class="timer" data-from="1" data-to="2" data-speed="5000"
                                        data-refresh-interval="50">

                                        2

                                    </span>


                                    <img src="images/icon/k.png" alt="">


                                </h1>


                            </div>


                            <div class="title">


                                <h3>

                                    Projects

                                    <br>

                                    Completed

                                </h3>


                            </div>


                        </div>


                    </div>


                    <!-- Branches -->

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">


                        <div class="single-fact-counter">


                            <div class="count-box">


                                <h1>


                                    <span class="timer" data-from="1" data-to="24" data-speed="5000"
                                        data-refresh-interval="50">

                                        24

                                    </span>


                                </h1>


                            </div>


                            <div class="title">


                                <h3>

                                    Branches

                                    <br>

                                    Nationwide

                                </h3>


                            </div>


                        </div>


                    </div>


                    <!-- Awards -->

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">


                        <div class="single-fact-counter">


                            <div class="count-box">


                                <h1>


                                    <span class="timer" data-from="1" data-to="3" data-speed="5000"
                                        data-refresh-interval="50">

                                        3

                                    </span>


                                </h1>


                            </div>


                            <div class="title">


                                <h3>

                                    Awards

                                    <br>

                                    Holds in Hand

                                </h3>


                            </div>


                        </div>


                    </div>


                </div>


            </div>


        </section>


        <!-- =====================================================
         TEAM AREA
         ===================================================== -->

        <section class="team-area">


            <div class="container">


                <!-- Heading -->

                <div class="row">


                    <div class="col-xl-12">


                        <div class="sec-title float-left">


                            <p>
                                Behind Our Project
                            </p>


                            <div class="title">

                                Dedicated & Committed

                                <span>
                                    Team
                                </span>

                            </div>


                        </div>


                        <div class="view-all-member style2 float-right">


                            <a class="btn-one" href="#">

                                All Members

                                <span class="flaticon-next"></span>

                            </a>


                        </div>


                    </div>


                </div>


                <!-- Team Cards -->

                <div class="row">


                    <!-- =================================================
                     VIKAS
                     ================================================= -->

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">


                        <div class="single-team-member">


                            <div class="img-holder">


                                <img src="images/team/v2-1.jpg" alt="Vikas Chenna">


                                <ul class="sociallinks">


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-facebook" aria-hidden="true"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-twitter" aria-hidden="true"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-skype" aria-hidden="true"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-linkedin" aria-hidden="true"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-vimeo" aria-hidden="true"></i>

                                        </a>

                                    </li>


                                </ul>


                                <div class="overlay">


                                    <div class="box">


                                        <div class="link">


                                            <a class="btn-two" href="#">

                                                View Profile

                                                <span class="flaticon-next"></span>

                                            </a>


                                        </div>


                                    </div>


                                </div>


                            </div>


                            <div class="name text-center">


                                <p>

                                    <span>
                                        Team Leader / Front-End Developer
                                    </span>

                                </p>


                                <h3>
                                    Vikas Chenna
                                </h3>


                            </div>


                        </div>


                    </div>


                    <!-- =================================================
                     DRASHTI
                     ================================================= -->

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">


                        <div class="single-team-member">


                            <div class="img-holder">


                                <img src="images/team/v2-2.jpg" alt="Drashti Balar">


                                <ul class="sociallinks">


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-facebook"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-twitter"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-skype"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-linkedin"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-vimeo"></i>

                                        </a>

                                    </li>


                                </ul>


                                <div class="overlay">


                                    <div class="box">


                                        <div class="link">


                                            <a class="btn-two" href="#">

                                                View Profile

                                                <span class="flaticon-next"></span>

                                            </a>


                                        </div>


                                    </div>


                                </div>


                            </div>


                            <div class="name text-center">


                                <p>

                                    <span>
                                        Front-End Developer /
                                        Documentation Specialist
                                    </span>

                                </p>


                                <h3>
                                    Drashti Balar
                                </h3>


                            </div>


                        </div>


                    </div>


                    <!-- =================================================
                     YOGESH
                     ================================================= -->

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">


                        <div class="single-team-member">


                            <div class="img-holder">


                                <img src="images/team/v2-3.jpg" alt="Yogesh Deore">


                                <ul class="sociallinks">


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-facebook"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-twitter"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-skype"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-linkedin"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-vimeo"></i>

                                        </a>

                                    </li>


                                </ul>


                                <div class="overlay">


                                    <div class="box">


                                        <div class="link">


                                            <a class="btn-two" href="#">

                                                View Profile

                                                <span class="flaticon-next"></span>

                                            </a>


                                        </div>


                                    </div>


                                </div>


                            </div>


                            <div class="name text-center">


                                <p>

                                    <span>
                                        Back-End Developer
                                    </span>

                                </p>


                                <h3>
                                    Yogesh Deore
                                </h3>


                            </div>


                        </div>


                    </div>


                    <!-- =================================================
                     JENISHA
                     ================================================= -->

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">


                        <div class="single-team-member">


                            <div class="img-holder">


                                <img src="images/team/v2-4.jpg" alt="Jenisha Patel">


                                <ul class="sociallinks">


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-facebook"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-twitter"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-skype"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-linkedin"></i>

                                        </a>

                                    </li>


                                    <li>

                                        <a href="#">

                                            <i class="fa fa-vimeo"></i>

                                        </a>

                                    </li>


                                </ul>


                                <div class="overlay">


                                    <div class="box">


                                        <div class="link">


                                            <a class="btn-two" href="#">

                                                View Profile

                                                <span class="flaticon-next"></span>

                                            </a>


                                        </div>


                                    </div>


                                </div>


                            </div>


                            <div class="name text-center">


                                <p>

                                    <span>
                                        Documentation Specialist
                                    </span>

                                </p>


                                <h3>
                                    Jenisha Patel
                                </h3>


                            </div>


                        </div>


                    </div>


                </div>


            </div>


        </section>


        <!-- =====================================================
         LOCATIONS / BRAND AREA
         WOW REMOVED
         ===================================================== -->

        <section class="brand-area style2">


            <div class="container">


                <div class="row">


                    <div class="col-xl-12">


                        <ul>


                            <!-- Surat -->

                            <li class="single-brand-item style2">


                                <a href="#">

                                    <img src="images/brand/1.png" alt="Surat">

                                </a>


                                <div class="overlay-content">

                                    <p>
                                        Surat
                                    </p>

                                </div>


                            </li>


                            <!-- Mumbai -->

                            <li class="single-brand-item style2">


                                <a href="#">

                                    <img src="images/brand/2.png" alt="Mumbai">

                                </a>


                                <div class="overlay-content">

                                    <p>
                                        Mumbai
                                    </p>

                                </div>


                            </li>


                            <!-- Delhi -->

                            <li class="single-brand-item style2">


                                <a href="#">

                                    <img src="images/brand/3.png" alt="Delhi">

                                </a>


                                <div class="overlay-content">

                                    <p>
                                        Delhi
                                    </p>

                                </div>


                            </li>


                            <!-- Chennai -->

                            <li class="single-brand-item style2">


                                <a href="#">

                                    <img src="images/brand/4.png" alt="Chennai">

                                </a>


                                <div class="overlay-content">

                                    <p>
                                        Chennai
                                    </p>

                                </div>


                            </li>


                            <!-- Hyderabad -->

                            <li class="single-brand-item style2">


                                <a href="#">

                                    <img src="images/brand/5.png" alt="Hyderabad">

                                </a>


                                <div class="overlay-content">

                                    <p>
                                        Hyderabad
                                    </p>

                                </div>


                            </li>


                            <!-- Amritsar -->

                            <li class="single-brand-item style2">


                                <a href="#">

                                    <img src="images/brand/6.png" alt="Amritsar">

                                </a>


                                <div class="overlay-content">

                                    <p>
                                        Amritsar
                                    </p>

                                </div>


                            </li>


                        </ul>


                    </div>


                </div>


            </div>


        </section>


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

                                                Bharthana, Surat, Gujarat 395007

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


                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">


                        <div class="copyright-text text-center">


                            <p>


                                <a href="#" target="_blank">

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


    <!-- wow.js intentionally NOT loaded -->


    <script src="js/map-helper.js"></script>

    <script src="assets/language-switcher/jquery.polyglot.language.switcher.js"></script>

    <script src="assets/timepicker/timePicker.js"></script>

    <script src="assets/html5lightbox/html5lightbox.js"></script>


    <!-- Theme Custom Script -->

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


    <!-- Navbar JS -->

    <script src="js/customer-navbar-dropdown-final.js?v=20260728-sticky2"></script>


</body>

</html>