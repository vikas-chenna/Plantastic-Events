<?php

session_start();
include('../conn.php');

// if(!isset($_SESSION['customer']))
// {
//     header('location:login.php');
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ceremony</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Master stylesheet -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Responsive stylesheet -->
    <link rel="stylesheet" href="css/responsive.css">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="images/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/favicon/favicon-16x16.png" sizes="16x16">

    <!-- Fixing Internet Explorer -->
    <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <script src="js/html5shiv.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="css/customer-premium.css">
    <link rel="stylesheet" href="css/golden-refined-v3.css">
    <link rel="stylesheet" href="css/customer-navbar-logo.css?v=20260728-sticky2">
    <link rel="stylesheet" href="css/customer-navbar-dropdown-final.css">

</head>

<body>

    <!-- =====================================================
         MAIN HEADER
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
                                        <a href="Anniversary.php">Anniverseries/Jubilees</a>
                                    </li>

                                    <li>
                                        <a href="Ceremony.php">Ceremony</a>
                                    </li>

                                    <li>
                                        <a href="Couples.php">Couples Exclusive</a>
                                    </li>

                                    <li>
                                        <a href="Birthday.php">Birthdays</a>
                                    </li>

                                    <li>
                                        <a href="Party.php">Parties</a>
                                    </li>

                                    <li>
                                        <a href="Corporate.php">Corporate</a>
                                    </li>

                                    <li>
                                        <a href="Others.php">Others</a>
                                    </li>
                                </ul>

                            </li>

                            <!-- Organizers -->
                            <li>
                                <a href="booking.php">Organizers</a>
                            </li>
                            <li>
                                <a href="contact.php">Contact Us</a>
                            </li>

                            <li class="dropdown">

                                <a href="about.php">About Us</a>

                                <ul>
                                    <li>
                                        <a href="about.php">About Company</a>
                                    </li>

                                    <li>
                                        <a href="faq.php">FAQ’s</a>
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

    <!-- =====================================================
         END MAIN HEADER
         ===================================================== -->


    <!-- =====================================================
         BREADCRUMB
         ===================================================== -->

    <section class="breadcrumb-area style2" style="background-image: url(images/resources/breadcrumb-bg-5.jpg);">

        <div class="container">

            <div class="row">

                <div class="col-xl-12">

                    <div class="inner-content-box clearfix">

                        <div class="title-s2 text-center">

                            <span>PLANTASTIC EVENTS</span>

                            <h1>Ceremonies</h1>

                        </div>


                        <div class="breadcrumb-menu float-left">

                            <ul class="clearfix">

                                <li>
                                    <a href="index-3.php">Home</a>
                                </li>

                                <li>
                                    <a href="index-3.php">Events</a>
                                </li>

                                <li class="active">
                                    Ceremonies
                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- =====================================================
         END BREADCRUMB
         ===================================================== -->


    <!-- =====================================================
         CEREMONY CARDS
         ===================================================== -->

    <div class="container">


        <!-- Heading -->

        <div class="sec-title with-text max-width text-center">

            <div class="title m-5">
                For cherished your ceremonies
            </div>

            <p class="bottom-text">
                We bring your celebrations to life with beautifully crafted
                themes and designs, creating memories that last a lifetime.
            </p>

        </div>


        <div class="row">


            <!-- =================================================
                 01 — WEDDING
                 ================================================= -->

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">

                <div class="single-service-style1">

                    <div class="img-holder">

                        <img src="images/services/c1.jpg" alt="Wedding">

                        <div class="overlay-style-two"></div>


                        <div class="overlay-content-box">

                            <div class="box">

                                <div class="inner">

                                    <div class="title">

                                        <h3>
                                            <a href="booking.php">
                                                Wedding
                                            </a>
                                        </h3>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="text-holder">

                        <div class="inner-content">

                            <div class="top">

                                <div class="count">
                                    <h1>01</h1>
                                </div>

                            </div>


                            <div class="bottom">

                                <div class="title">

                                    <h3>
                                        <a href="booking.php">
                                            Wedding
                                        </a>
                                    </h3>

                                </div>


                                <div class="read-more">

                                    <a href="booking.php">
                                        <span class="icon-next"></span>
                                    </a>

                                </div>

                            </div>

                        </div>


                        <div class="overlay-content">

                            <div class="text">

                                <p>
                                    We craft enchanting wedding setups that
                                    reflect your love story and make your big
                                    day truly magical.
                                </p>

                            </div>


                            <div class="read-more">

                                <a href="booking.php">
                                    <span class="icon-next"></span>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 02 — ENGAGEMENT
                 ================================================= -->

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">

                <div class="single-service-style1">

                    <div class="img-holder">

                        <img src="images/services/c2.jpg" alt="Engagement">

                        <div class="overlay-style-two"></div>


                        <div class="overlay-content-box">

                            <div class="box">

                                <div class="inner">

                                    <div class="title">

                                        <h3>
                                            <a href="booking.php">
                                                Engagement
                                            </a>
                                        </h3>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="text-holder">

                        <div class="inner-content">

                            <div class="top">

                                <div class="count">
                                    <h1>02</h1>
                                </div>

                            </div>


                            <div class="bottom">

                                <div class="title">

                                    <h3>
                                        <a href="booking.php">
                                            Engagement
                                        </a>
                                    </h3>

                                </div>


                                <div class="read-more">

                                    <a href="booking.php">
                                        <span class="icon-next"></span>
                                    </a>

                                </div>

                            </div>

                        </div>


                        <div class="overlay-content">

                            <div class="text">

                                <p>
                                    Make your engagement truly special with
                                    elegant themes and decor that mark the
                                    beginning of your beautiful journey together.
                                </p>

                            </div>


                            <div class="read-more">

                                <a href="booking.php">
                                    <span class="icon-next"></span>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 03 — MEHNDI
                 ================================================= -->

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">

                <div class="single-service-style1">

                    <div class="img-holder">

                        <img src="images/services/c3.jpg" alt="Mehndi">

                        <div class="overlay-style-two"></div>


                        <div class="overlay-content-box">

                            <div class="box">

                                <div class="inner">

                                    <div class="title">

                                        <h3>
                                            <a href="booking.php">
                                                Mehndi
                                            </a>
                                        </h3>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="text-holder">

                        <div class="inner-content">

                            <div class="top">

                                <div class="count">
                                    <h1>03</h1>
                                </div>

                            </div>


                            <div class="bottom">

                                <div class="title">

                                    <h3>
                                        <a href="booking.php">
                                            Mehndi
                                        </a>
                                    </h3>

                                </div>


                                <div class="read-more">

                                    <a href="booking.php">
                                        <span class="icon-next"></span>
                                    </a>

                                </div>

                            </div>

                        </div>


                        <div class="overlay-content">

                            <div class="text">

                                <p>
                                    Let us design a colorful and lively Mehndi
                                    celebration, blending tradition with
                                    creativity for an unforgettable experience.
                                </p>

                            </div>


                            <div class="read-more">

                                <a href="booking.php">
                                    <span class="icon-next"></span>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 04 — HALDI
                 ================================================= -->

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">

                <div class="single-service-style1">

                    <div class="img-holder">

                        <img src="images/services/c4.jpg" alt="Haldi">

                        <div class="overlay-style-two"></div>


                        <div class="overlay-content-box">

                            <div class="box">

                                <div class="inner">

                                    <div class="title">

                                        <h3>
                                            <a href="booking.php">
                                                Haldi
                                            </a>
                                        </h3>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="text-holder">

                        <div class="inner-content">

                            <div class="top">

                                <div class="count">
                                    <h1>04</h1>
                                </div>

                            </div>


                            <div class="bottom">

                                <div class="title">

                                    <h3>
                                        <a href="booking.php">
                                            Haldi
                                        </a>
                                    </h3>

                                </div>


                                <div class="read-more">

                                    <a href="booking.php">
                                        <span class="icon-next"></span>
                                    </a>

                                </div>

                            </div>

                        </div>


                        <div class="overlay-content">

                            <div class="text">

                                <p>
                                    Celebrate the joyous Haldi ceremony with
                                    vibrant decor and personalized touches that
                                    bring tradition and happiness together.
                                </p>

                            </div>


                            <div class="read-more">

                                <a href="booking.php">
                                    <span class="icon-next"></span>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 05 — SANGEET
                 ================================================= -->

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">

                <div class="single-service-style1">

                    <div class="img-holder">

                        <img src="images/services/c5.jpg" alt="Sangeet">

                        <div class="overlay-style-two"></div>


                        <div class="overlay-content-box">

                            <div class="box">

                                <div class="inner">

                                    <div class="title">

                                        <h3>
                                            <a href="booking.php">
                                                Sangeet
                                            </a>
                                        </h3>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="text-holder">

                        <div class="inner-content">

                            <div class="top">

                                <div class="count">
                                    <h1>05</h1>
                                </div>

                            </div>


                            <div class="bottom">

                                <div class="title">

                                    <h3>
                                        <a href="booking.php">
                                            Sangeet
                                        </a>
                                    </h3>

                                </div>


                                <div class="read-more">

                                    <a href="booking.php">
                                        <span class="icon-next"></span>
                                    </a>

                                </div>

                            </div>

                        </div>


                        <div class="overlay-content">

                            <div class="text">

                                <p>
                                    Turn your Sangeet night into a lively
                                    celebration with dazzling decor and a
                                    vibrant ambiance that keeps everyone dancing.
                                </p>

                            </div>


                            <div class="read-more">

                                <a href="booking.php">
                                    <span class="icon-next"></span>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 06 — BABY SHOWER
                 ================================================= -->

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">

                <div class="single-service-style1">

                    <div class="img-holder">

                        <img src="images/services/c6.jpg" alt="Baby Shower">

                        <div class="overlay-style-two"></div>


                        <div class="overlay-content-box">

                            <div class="box">

                                <div class="inner">

                                    <div class="title">

                                        <h3>
                                            <a href="booking.php">
                                                Baby Shower
                                            </a>
                                        </h3>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="text-holder">

                        <div class="inner-content">

                            <div class="top">

                                <div class="count">
                                    <h1>06</h1>
                                </div>

                            </div>


                            <div class="bottom">

                                <div class="title">

                                    <h3>
                                        <a href="booking.php">
                                            Baby Shower
                                        </a>
                                    </h3>

                                </div>


                                <div class="read-more">

                                    <a href="booking.php">
                                        <span class="icon-next"></span>
                                    </a>

                                </div>

                            </div>

                        </div>


                        <div class="overlay-content">

                            <div class="text">

                                <p>
                                    Celebrate the welcome of your soon-to-be
                                    little one with a heartwarming baby shower
                                    setup filled with love, charm, and
                                    delightful memories.
                                </p>

                            </div>


                            <div class="read-more">

                                <a href="booking.php">
                                    <span class="icon-next"></span>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 07 — WEDDING RECEPTION
                 ================================================= -->

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">

                <div class="single-service-style1">

                    <div class="img-holder">

                        <img src="images/services/c7.jpg" alt="Wedding Reception">

                        <div class="overlay-style-two"></div>


                        <div class="overlay-content-box">

                            <div class="box">

                                <div class="inner">

                                    <div class="title">

                                        <h3>
                                            <a href="booking.php">
                                                Wedding Reception
                                            </a>
                                        </h3>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="text-holder">

                        <div class="inner-content">

                            <div class="top">

                                <div class="count">
                                    <h1>07</h1>
                                </div>

                            </div>


                            <div class="bottom">

                                <div class="title">

                                    <h3>
                                        <a href="booking.php">
                                            Wedding Reception
                                        </a>
                                    </h3>

                                </div>


                                <div class="read-more">

                                    <a href="booking.php">
                                        <span class="icon-next"></span>
                                    </a>

                                </div>

                            </div>

                        </div>


                        <div class="overlay-content">

                            <div class="text">

                                <p>
                                    Celebrate your wedding reception in style,
                                    where elegant decor and meticulous planning
                                    create a grand and unforgettable evening.
                                </p>

                            </div>


                            <div class="read-more">

                                <a href="booking.php">
                                    <span class="icon-next"></span>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 08 — OTHERS
                 ================================================= -->

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">

                <div class="single-service-style1">

                    <div class="img-holder">

                        <img src="images/services/c8.jpg" alt="Other Ceremonies">

                        <div class="overlay-style-two"></div>


                        <div class="overlay-content-box">

                            <div class="box">

                                <div class="inner">

                                    <div class="title">

                                        <h3>
                                            <a href="booking.php">
                                                Others
                                            </a>
                                        </h3>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="text-holder">

                        <div class="inner-content">

                            <div class="top">

                                <div class="count">
                                    <h1>08</h1>
                                </div>

                            </div>


                            <div class="bottom">

                                <div class="title">

                                    <h3>
                                        <a href="booking.php">
                                            Others
                                        </a>
                                    </h3>

                                </div>


                                <div class="read-more">

                                    <a href="booking.php">
                                        <span class="icon-next"></span>
                                    </a>

                                </div>

                            </div>

                        </div>


                        <div class="overlay-content">

                            <div class="text">

                                <p>
                                    For every unique celebration, Plantastic
                                    Events creates personalized setups to make
                                    your moments extraordinary and unforgettable.
                                </p>

                            </div>


                            <div class="read-more">

                                <a href="booking.php">
                                    <span class="icon-next"></span>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>

    <!-- =====================================================
         END CEREMONY CARDS
         ===================================================== -->



    <!-- =====================================================
         THEMES
         ===================================================== -->

    <section class="latest-blog-area style2">

    </section>

    <!-- End Themes -->



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
                                            Sr. No. 149, VIP Road, Vesu,<br>
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
                                            +91 998 877 6655<br>
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
                                            support@plantasticevents.com<br>
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

    <!-- End Footer Contact Info -->



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

    <!-- End Footer Bottom -->



    <!-- Scroll To Top -->

    <div class="scroll-to-top scroll-to-target" data-target="html" style="display: block;">

        <span class="fa fa-angle-up"></span>

    </div>



    <!-- =====================================================
         JAVASCRIPT
         ===================================================== -->

    <script src="js/jquery.js"></script>
    <script src="js/appear.js"></script>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>

    <script src="js/isotope.js"></script>

    <!-- Load only once -->
    <script src="js/jquery.bootstrap-touchspin.js"></script>

    <script src="js/jquery.countTo.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/jquery.enllax.min.js"></script>
    <script src="js/jquery.fancybox.js"></script>
    <script src="js/jquery.mixitup.min.js"></script>
    <script src="js/jquery.paroller.min.js"></script>

    <script src="js/owl.js"></script>
    <script src="js/validation.js"></script>

    <!-- Keep WOW library because other template functionality may use it -->
    <script src="js/wow.js"></script>


    <script src="assets/language-switcher/jquery.polyglot.language.switcher.js"></script>
    <script src="assets/timepicker/timePicker.js"></script>
    <script src="assets/html5lightbox/html5lightbox.js"></script>

    <script src="assets/bootstrap-sl-1.12.1/bootstrap-select.js"></script>
    <script src="assets/jquery-ui-1.11.4/jquery-ui.js"></script>


    <!-- Theme custom script -->

    <script src="js/custom.js"></script>



    <!-- =====================================================
         LOGIN / PROFILE NAVIGATION
         ===================================================== -->

    <?php

    $user = $_SESSION['customer'] ?? null;

    if ($user) {
        $status = 'true';
    } else {
        $status = 'false';
    }

    ?>


    <script>

        const isLoggedIn = <?php echo $status; ?>;

        if (isLoggedIn) {

            const navButtons =
                document.getElementById("navButtons");

            const joinBtn =
                document.getElementById("joinButton");


            // Remove Join button

            if (joinBtn) {
                joinBtn.remove();
            }


            // Add Profile button

            const profileBtn =
                document.createElement("li");

            profileBtn.className = "nav-item";

            profileBtn.innerHTML =
                `<a href="profile.php">Profile</a>`;

            navButtons.appendChild(profileBtn);
        }

    </script>


    <!-- Customer navbar functionality -->

    <script src="js/customer-navbar-dropdown-final.js?v=20260728-sticky2"></script>


</body>

</html>