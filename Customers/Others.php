<?php

session_start();
include('../conn.php');

// if (!isset($_SESSION['customer'])) {
//     header('location:login.php');
//     exit;
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>Others</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="images/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/favicon/favicon-16x16.png" sizes="16x16">

    <!-- Customer Navbar CSS -->
    <link rel="stylesheet" href="css/customer-navbar-logo.css?v=20260728-sticky2">
    <link rel="stylesheet" href="css/customer-navbar-dropdown-final.css">

    <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <script src="js/html5shiv.js"></script>
    <![endif]-->

</head>

<body>


    <!-- =========================================================
     MAIN HEADER
     ========================================================= -->

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

                        <button type="button" class="navbar-toggle">

                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>

                        </button>

                    </div>


                    <div class="navbar-collapse collapse clearfix">


                        <ul class="navigation clearfix" id="navButtons">


                            <li>
                                <a href="index-3.php">Home</a>
                            </li>


                            <li class="dropdown">

                                <a href="#">Events</a>

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
                                            Couples Exclusive
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


    <!-- =========================================================
     BREADCRUMB
     ========================================================= -->

    <section class="breadcrumb-area style2" style="background-image: url(images/resources/breadcrumb-bg-8.JPG);">


        <div class="container">


            <div class="row">


                <div class="col-xl-12">


                    <div class="inner-content-box clearfix">


                        <div class="title-s2 text-center">


                            <span>
                                PLANTASTIC EVENTS
                            </span>


                            <h1>
                                Other Significant Celebrations
                            </h1>


                        </div>


                        <div class="breadcrumb-menu float-left">


                            <ul class="clearfix">


                                <li>
                                    <a href="index-3.php">
                                        Home
                                    </a>
                                </li>


                                <li>
                                    <a href="index-3.php">
                                        Events
                                    </a>
                                </li>


                                <li class="active">
                                    Others
                                </li>


                            </ul>


                        </div>


                    </div>


                </div>


            </div>


        </div>


    </section>


    <!-- =========================================================
     OTHER OCCASIONS
     ========================================================= -->

    <div class="container">


        <!-- Heading -->

        <div class="sec-title with-text max-width text-center">


            <div class="title m-5">
                For Additional Occasions
            </div>


            <p class="bottom-text">

                We are specialists who believe in excellence,
                quality, and integrity. At Plantastic Events,
                we bring your events to life with exceptional
                designs and meticulous planning, creating
                memorable experiences that leave a lasting
                impression.

            </p>


        </div>


        <div class="row">


            <!-- =================================================
             01 — NAMKARAN
             ================================================= -->

            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">


                <div class="single-blog-post">


                    <div class="img-holder">


                        <img src="images/blog/o1.jpg" alt="Namkaran Event">


                        <div class="overlay-style-two"></div>


                        <div class="overlay">


                            <div class="box">


                                <div class="link-icon">


                                    <a href="booking.php">

                                        <span class="flaticon-zoom"></span>

                                    </a>


                                </div>


                            </div>


                        </div>


                    </div>


                    <div class="text-holder">


                        <h3 class="blog-title">

                            <a href="booking.php">
                                Namkaran
                            </a>

                        </h3>


                    </div>


                </div>


            </div>


            <!-- =================================================
             02 — VANA RASAM
             ================================================= -->

            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">


                <div class="single-blog-post">


                    <div class="img-holder">


                        <img src="images/blog/o2.jpg" alt="Vana Rasam Event">


                        <div class="overlay-style-two"></div>


                        <div class="overlay">


                            <div class="box">


                                <div class="link-icon">


                                    <a href="booking.php">

                                        <span class="flaticon-zoom"></span>

                                    </a>


                                </div>


                            </div>


                        </div>


                    </div>


                    <div class="text-holder">


                        <h3 class="blog-title">

                            <a href="booking.php">
                                Vana Rasam
                            </a>

                        </h3>


                    </div>


                </div>


            </div>


            <!-- =================================================
             03 — KANKOTRI LEKHAN
             ================================================= -->

            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">


                <div class="single-blog-post">


                    <div class="img-holder">


                        <img src="images/blog/o3.jpg" alt="Kankotri Lekhan Event">


                        <div class="overlay-style-two"></div>


                        <div class="overlay">


                            <div class="box">


                                <div class="link-icon">


                                    <a href="booking.php">

                                        <span class="flaticon-zoom"></span>

                                    </a>


                                </div>


                            </div>


                        </div>


                    </div>


                    <div class="text-holder">


                        <h3 class="blog-title">

                            <a href="booking.php">
                                Kankotri Lekhan
                            </a>

                        </h3>


                    </div>


                </div>


            </div>


            <!-- =================================================
             04 — ANNAPRASHAN
             ================================================= -->

            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">


                <div class="single-blog-post">


                    <div class="img-holder">


                        <img src="images/blog/o4.jpg" alt="Annaprashan Event">


                        <div class="overlay-style-two"></div>


                        <div class="overlay">


                            <div class="box">


                                <div class="link-icon">


                                    <a href="booking.php">

                                        <span class="flaticon-zoom"></span>

                                    </a>


                                </div>


                            </div>


                        </div>


                    </div>


                    <div class="text-holder">


                        <h3 class="blog-title">

                            <a href="booking.php">
                                Annaprashan
                            </a>

                        </h3>


                    </div>


                </div>


            </div>


            <!-- =================================================
             05 — CHHATHI POOJAN
             ================================================= -->

            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">


                <div class="single-blog-post">


                    <div class="img-holder">


                        <img src="images/blog/o5.jpg" alt="Chhathi Poojan Event">


                        <div class="overlay-style-two"></div>


                        <div class="overlay">


                            <div class="box">


                                <div class="link-icon">


                                    <a href="booking.php">

                                        <span class="flaticon-zoom"></span>

                                    </a>


                                </div>


                            </div>


                        </div>


                    </div>


                    <div class="text-holder">


                        <h3 class="blog-title">

                            <a href="booking.php">
                                Chhathi Poojan
                            </a>

                        </h3>


                    </div>


                </div>


            </div>


            <!-- =================================================
             06 — DURGA POOJA
             ================================================= -->

            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">


                <div class="single-blog-post">


                    <div class="img-holder">


                        <img src="images/blog/o6.jpg" alt="Durga Pooja Event">


                        <div class="overlay-style-two"></div>


                        <div class="overlay">


                            <div class="box">


                                <div class="link-icon">


                                    <a href="booking.php">

                                        <span class="flaticon-zoom"></span>

                                    </a>


                                </div>


                            </div>


                        </div>


                    </div>


                    <div class="text-holder">


                        <h3 class="blog-title">

                            <a href="booking.php">
                                Durga Pooja
                            </a>

                        </h3>


                    </div>


                </div>


            </div>


        </div>


    </div>


    <!-- =========================================================
     FOOTER CONTACT INFO
     ========================================================= -->

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

                                            Sr. No. 149, VIP Road, Vesu,

                                            <br>

                                            Bharthana, Surat, Gujarat 395007

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

                                            <span>Mon - Friday:</span>

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


    <!-- =========================================================
     FOOTER BOTTOM
     ========================================================= -->

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


    <!-- Scroll To Top -->

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

    <!-- WOW removed from this page -->

    <script src="assets/language-switcher/jquery.polyglot.language.switcher.js"></script>

    <script src="assets/timepicker/timePicker.js"></script>

    <script src="assets/html5lightbox/html5lightbox.js"></script>

    <script src="assets/bootstrap-sl-1.12.1/bootstrap-select.js"></script>

    <script src="assets/jquery-ui-1.11.4/jquery-ui.js"></script>


    <!-- Theme Custom Script -->

    <script src="js/custom.js"></script>


    <!-- =========================================================
     LOGIN / PROFILE NAVIGATION
     ========================================================= -->

    <?php

    $user = $_SESSION['customer'] ?? null;

    $status = $user ? 'true' : 'false';

    ?>


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


    <!-- Customer Navbar -->

    <script src="js/customer-navbar-dropdown-final.js?v=20260728-sticky2"></script>


</body>

</html>