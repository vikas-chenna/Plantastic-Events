<?php

session_start();
include('../conn.php');

// Uncomment when login is required
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

    <title>Contact Us || Plantastic Events</title>

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
         CONTACT PAGE FIX
         ===================================================== -->

    <style>
        /*
         * IMPORTANT:
         * style.css contains an old height: 100vh rule.
         * Contact content must determine its own height.
         */

        .contact-form-area {
            position: relative !important;
            display: block !important;

            width: 100% !important;

            height: auto !important;
            min-height: 0 !important;

            padding: 80px 0 !important;

            overflow: visible !important;
        }


        .contact-form-area .container {
            height: auto !important;
            min-height: 0 !important;
        }


        .contact-form-area .row {
            height: auto !important;
            min-height: 0 !important;

            align-items: stretch;
        }


        /* =====================================================
           CONTACT INFO CARD
           ===================================================== */

        .contact-form-area .contact-info {

            position: relative;

            width: 100% !important;
            max-width: 500px;

            height: 100% !important;
            min-height: 0 !important;

            margin: 0 auto !important;

            padding: 35px !important;

            background: #f9f9f9;

            border-radius: 10px;

            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.08);

        }


        .contact-form-area .contact-info h2 {

            margin: 0 0 18px 0 !important;

        }


        .contact-form-area .contact-info>p {

            margin: 0 0 32px 0 !important;

            line-height: 1.7;

        }


        .contact-form-area .contact-info ul {

            margin: 0 !important;

            padding: 0 !important;

            list-style: none;

        }


        .contact-form-area .contact-info ul>li {

            margin: 0 0 28px 0 !important;

            padding: 0 !important;

        }


        .contact-form-area .contact-info ul>li:last-child {

            margin-bottom: 0 !important;

        }


        /* Contact icon + text */

        .contact-form-area .single-footer-contact-info {

            width: 100%;

        }


        .contact-form-area .single-footer-contact-info .inner {

            display: flex;

            align-items: flex-start;

            gap: 18px;

        }


        .contact-form-area .single-footer-contact-info .icon {

            flex: 0 0 auto;

        }


        .contact-form-area .single-footer-contact-info .text {

            flex: 1;

            min-width: 0;

        }


        .contact-form-area .single-footer-contact-info .text p {

            margin: 0 0 5px 0 !important;

            overflow-wrap: anywhere;

        }


        .contact-form-area .single-footer-contact-info .text p:last-child {

            margin-bottom: 0 !important;

        }


        /* =====================================================
           CONTACT FORM CARD
           ===================================================== */

        .contact-form-area .contact-form {

            position: relative;

            display: block !important;

            width: 100% !important;
            max-width: 500px;

            height: 100% !important;
            min-height: 0 !important;

            margin: 0 auto !important;

            padding: 35px !important;

            background: #f9f9f9;

            border-radius: 10px;

            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.08);

        }


        .contact-form-area .contact-form h2 {

            margin: 0 0 28px 0 !important;

        }


        .contact-form-area .contact-form .form-group {

            margin: 0 0 20px 0 !important;

        }


        .contact-form-area .contact-form .form-group:last-child {

            margin-bottom: 0 !important;

        }


        .contact-form-area .contact-form input,

        .contact-form-area .contact-form textarea {

            width: 100%;

        }


        .contact-form-area .contact-form textarea {

            min-height: 140px;

            resize: vertical;

        }


        /* =====================================================
           TABLET
           ===================================================== */

        @media only screen and (max-width: 991px) {

            .contact-form-area {

                height: auto !important;

                padding: 60px 0 !important;

            }


            /*
             * Bootstrap columns stack because col-lg-6
             * stops applying below lg.
             */

            .contact-form-area .row>div {

                width: 100% !important;

                max-width: 100% !important;

                flex: 0 0 100% !important;

            }


            .contact-form-area .row>div:first-child {

                margin-bottom: 45px !important;

            }


            .contact-form-area .contact-info,

            .contact-form-area .contact-form {

                height: auto !important;

            }

        }


        /* =====================================================
           MOBILE
           ===================================================== */

        @media only screen and (max-width: 767px) {

            .contact-form-area {

                display: block !important;

                height: auto !important;

                min-height: 0 !important;

                padding: 45px 0 55px !important;

                overflow: visible !important;

            }


            .contact-form-area .container {

                width: 100% !important;

                height: auto !important;

                min-height: 0 !important;

            }


            .contact-form-area .row {

                display: flex !important;

                flex-direction: column !important;

                height: auto !important;

                min-height: 0 !important;

            }


            .contact-form-area .row>div {

                position: relative !important;

                display: block !important;

                width: 100% !important;

                max-width: 100% !important;

                flex: 0 0 auto !important;

                height: auto !important;

                min-height: 0 !important;

            }


            /* Gap between both cards */

            .contact-form-area .row>div:first-child {

                margin-bottom: 40px !important;

            }


            /* Info */

            .contact-form-area .contact-info {

                display: block !important;

                width: 100% !important;

                max-width: none !important;

                height: auto !important;

                min-height: 0 !important;

                margin: 0 !important;

                padding: 25px 20px !important;

            }


            .contact-form-area .contact-info h2 {

                margin-bottom: 15px !important;

            }


            .contact-form-area .contact-info>p {

                margin-bottom: 30px !important;

            }


            .contact-form-area .contact-info ul>li {

                margin-bottom: 25px !important;

                padding-bottom: 25px !important;

                border-bottom:
                    1px solid rgba(212, 175, 55, 0.18);

            }


            .contact-form-area .contact-info ul>li:last-child {

                margin-bottom: 0 !important;

                padding-bottom: 0 !important;

                border-bottom: 0;

            }


            /* Form */

            .contact-form-area .contact-form {

                position: relative !important;

                display: block !important;

                visibility: visible !important;

                opacity: 1 !important;

                width: 100% !important;

                max-width: none !important;

                height: auto !important;

                min-height: 0 !important;

                margin: 0 !important;

                padding: 25px 20px !important;

                transform: none !important;

            }


            .contact-form-area .contact-form h2 {

                display: block !important;

                visibility: visible !important;

                opacity: 1 !important;

                margin: 0 0 25px 0 !important;

            }


            .contact-form-area .contact-form form {

                display: block !important;

                visibility: visible !important;

                opacity: 1 !important;

                height: auto !important;

            }


            .contact-form-area .contact-form .form-group {

                display: block !important;

                margin-bottom: 18px !important;

            }


            .contact-form-area .contact-form textarea {

                min-height: 130px !important;

            }

        }


        /* =====================================================
           SMALL MOBILE
           ===================================================== */

        @media only screen and (max-width: 480px) {

            .contact-form-area {

                padding: 35px 0 45px !important;

            }


            .contact-form-area .contact-info,

            .contact-form-area .contact-form {

                padding: 22px 16px !important;

            }


            .contact-form-area .row>div:first-child {

                margin-bottom: 30px !important;

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


                <!-- Main Menu -->

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

                                        Join NOW / JOIN US

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
         END HEADER
         ===================================================== -->



        <!-- =====================================================
         HERO / BREADCRUMB
         ===================================================== -->

        <section class="breadcrumb-area style2" style="background-image: url(images/resources/breadcrumb-bg-2.jpg);">


            <div class="container">


                <div class="row">


                    <div class="col-xl-12">


                        <div class="inner-content-box clearfix">


                            <div class="title-s2 text-center">


                                <span>
                                    Contact Us
                                </span>


                                <h1>
                                    We’d Love to Hear From You
                                </h1>


                            </div>


                            <div class="breadcrumb-menu float-left">


                                <ul class="clearfix">


                                    <li>


                                        <a href="index-3.php">
                                            Home
                                        </a>


                                    </li>


                                    <li class="active">
                                        Contact
                                    </li>


                                </ul>


                            </div>


                        </div>


                    </div>


                </div>


            </div>


        </section>


        <!-- =====================================================
         END HERO
         ===================================================== -->



        <!-- =====================================================
         CONTACT SECTION
         ===================================================== -->

        <section class="contact-form-area">


            <div class="container">


                <div class="row">


                    <!-- =================================================
                     LEFT — CONTACT INFORMATION
                     ================================================= -->

                    <div class="col-xl-6 col-lg-6">


                        <div class="contact-info">


                            <h2>
                                Get in Touch
                            </h2>


                            <p>

                                Feel free to drop us a message.
                                We’re here to answer all your queries
                                and provide assistance.

                            </p>


                            <ul>


                                <!-- Address -->

                                <li>


                                    <div class="single-footer-contact-info">


                                        <div class="inner">


                                            <div class="icon">


                                                <span class="icon-global"></span>


                                            </div>


                                            <div class="text">


                                                <p>
                                                    Sr. No. 149, VIP Road,
                                                    Vesu, Bharthana,
                                                </p>


                                                <p>
                                                    Surat, Gujarat 395007
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
                                                </p>


                                                <p>
                                                    Mon - Fri:
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


                                                <span class="icon-mail"></span>


                                            </div>


                                            <div class="text">


                                                <p>
                                                    support@plantasticevents.com
                                                </p>


                                                <p>
                                                    plantasticevents@gmail.com
                                                </p>


                                            </div>


                                        </div>


                                    </div>


                                </li>


                            </ul>


                        </div>


                    </div>


                    <!-- =================================================
                     RIGHT — CONTACT FORM
                     ================================================= -->

                    <div class="col-xl-6 col-lg-6">


                        <div class="contact-form">


                            <h2>
                                Send Us a Message
                            </h2>


                            <form action="#" method="post">


                                <!-- Name -->

                                <div class="form-group">


                                    <input type="text" name="name" placeholder="Your Name" required>


                                </div>


                                <!-- Email -->

                                <div class="form-group">


                                    <input type="email" name="email" placeholder="Your Email" required>


                                </div>


                                <!-- Phone -->

                                <div class="form-group">


                                    <input type="text" name="phone" placeholder="Your Phone Number" required>


                                </div>


                                <!-- Message -->

                                <div class="form-group">


                                    <textarea name="message" placeholder="Your Message" required></textarea>


                                </div>


                                <!-- Submit -->

                                <div class="form-group">


                                    <button type="submit" class="btn-style">

                                        Send Message

                                    </button>


                                </div>


                            </form>


                        </div>


                    </div>


                </div>


            </div>


        </section>


        <!-- =====================================================
         END CONTACT SECTION
         ===================================================== -->



        <!-- =====================================================
         FOOTER
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

    <script src="js/wow.js"></script>


    <!-- Assets -->

    <script src="assets/language-switcher/jquery.polyglot.language.switcher.js"></script>

    <script src="assets/timepicker/timePicker.js"></script>

    <script src="assets/html5lightbox/html5lightbox.js"></script>

    <script src="assets/bootstrap-sl-1.12.1/bootstrap-select.js"></script>

    <script src="assets/jquery-ui-1.11.4/jquery-ui.js"></script>


    <!-- Theme Script -->

    <script src="js/custom.js"></script>


    <!-- =========================================================
     LOGIN / PROFILE NAVIGATION
     ========================================================= -->

    <script>

        const isLoggedIn = <?php echo $status; ?>;


        if (isLoggedIn) {


            const navButtons =
                document.getElementById("navButtons");


            const joinButton =
                document.getElementById("joinButton");


            if (joinButton) {

                joinButton.remove();

            }


            if (navButtons) {


                const profileButton =
                    document.createElement("li");


                profileButton.className =
                    "nav-item";


                profileButton.innerHTML =
                    '<a href="profile.php">Profile</a>';


                navButtons.appendChild(profileButton);

            }

        }

    </script>


    <!-- Customer Navbar -->

    <script src="js/customer-navbar-dropdown-final.js?v=20260728-sticky2"></script>


</body>

</html>