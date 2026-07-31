<?php

session_start();
include('../conn.php');

// if(!isset($_SESSION['customer']))
// {
//      header('location:login.php');
// }

?>

<!DOCTYPE html>
<html lang="en">

<!-- account 07:01:11 GMT -->

<head>
    <meta charset="UTF-8">
    <title>Corporate</title>

    <!-- responsive meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- For IE -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- master stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive stylesheet -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="images/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/favicon/favicon-16x16.png" sizes="16x16">

    <!-- Fixing Internet Explorer-->
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


    <!--Start Main Header-->
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
                            <li><a href="index-3.php">Home</a></li>
                            <li class="dropdown"><a href="#">Events</a>
                                <ul>
                                    <li><a href="Anniversary.php">Anniverseries/Jubilees</a></li>
                                    <li><a href="Ceremony.php">Ceremony</a></li>
                                    <li><a href="Couples.php">Couples Exclusive</a></li>
                                    <li><a href="Birthday.php">Birthdays</a></li>
                                    <li><a href="Party.php">Parties</a></li>
                                    <li><a href="Corporate.php">Corporate</a></li>
                                    <li><a href="Others.php">Others</a></li>
                                </ul>
                            </li>

                            <!-- Organizers -->
                            <li>
                                <a href="booking.php">Organizers</a>
                            </li>
                            <li><a href="contact.php">Contact Us</a></li>
                            <li class="dropdown"><a href="about.php">About Us</a>
                                <ul>
                                    <li><a href="about.php">About Company</a></li>
                                    <li><a href="faq.php">FAQ’s</a></li>
                                </ul>
                            </li>
                            <li><a href="login.php" id="joinButton">Join NOW / JOIN US</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="mainmenu-right style2">
                    <div class="outer-search-box">
                        <div class="seach-toggle"><i class="fa fa-search"></i></div>
                        <ul class="search-box">
                            <li>
                                <form method="post" action="#">
                                    <div class="form-group">
                                        <input type="search" name="search" placeholder="Search Here" required>
                                        <button type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--End Main Header-->

    <section class="breadcrumb-area style2" style="background-image: url(images/resources/breadcrumb-bg-9.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="inner-content-box clearfix">
                        <div class="title-s2 text-center ">
                            <span>PLANTASTIC EVENTS</span>
                            <h1>Corporate Events</h1>
                        </div>
                        <div class="breadcrumb-menu float-left">
                            <ul class="clearfix">
                                <li><a href="index-3.php">Home</a></li>
                                <li><a href="index-3.php">Events</a></li>
                                <li class="active">Corporate</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Start Cards -->

    <div class="container">
        <div class="sec-title with-text max-width text-center wow fadeInDown animated" data-wow-delay="100ms"
            data-wow-duration="1200ms"
            style="visibility: visible; animation-duration: 1200ms; animation-delay: 100ms; animation-name: fadeInDown;">
            <div class="title m-5">For your distinguished organization</div>
            <p class="bottom-text">"We are specialists who believe in excellence, quality, and professionalism. At
                Plantastic Events, we bring your corporate events to life with innovative designs and refined themes,
                ensuring every occasion is a remarkable success that leaves a lasting impression."</p>
        </div>
        <div class="row">
            <!--Start single service style1-->
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="single-service-style1 wow fadeInUp animated" data-wow-delay="0ms" data-wow-duration="1200ms"
                    style="visibility: visible; animation-duration: 1200ms; animation-delay: 0ms; animation-name: fadeInUp;">
                    <div class="img-holder">
                        <img src="images/services/co1.jpg" alt="Awesome Image">
                        <div class="overlay-style-two"></div>
                        <div class="overlay-content-box">
                            <div class="box">
                                <div class="inner">

                                    <div class="title">
                                        <h3><a href="booking.php">Product Launch</a></h3>
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
                                    <h3><a href="booking.php">Product Launch</a></h3>
                                </div>
                                <div class="read-more">
                                    <a href="#"><span class="icon-next"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="overlay-content">
                            <div class="text">
                                <p>Make your product launch unforgettable with Plantastic Events, where creative designs
                                    and impactful setups highlight your brand and create an extraordinary first
                                    impression.</p>
                            </div>
                            <div class="read-more">
                                <a href="#"><span class="icon-next"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End single service style1-->

            <!--Start single service style1-->
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="single-service-style1 wow fadeInUp animated" data-wow-delay="0ms" data-wow-duration="1200ms"
                    style="visibility: visible; animation-duration: 1200ms; animation-delay: 0ms; animation-name: fadeInUp;">
                    <div class="img-holder">
                        <img src="images/services/co2.jpg" alt="Awesome Image">
                        <div class="overlay-style-two"></div>
                        <div class="overlay-content-box">
                            <div class="box">
                                <div class="inner">

                                    <div class="title">
                                        <h3><a href="booking.php">Award Ceremony</a></h3>
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
                                    <h3><a href="booking.php">Award Ceremony</a></h3>
                                </div>
                                <div class="read-more">
                                    <a href="#"><span class="icon-next"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="overlay-content">
                            <div class="text">
                                <p>Celebrate excellence with an elegant award ceremony designed by Plantastic Events,
                                    where sophisticated decor and meticulous planning honor achievements in style.</p>
                            </div>
                            <div class="read-more">
                                <a href="#"><span class="icon-next"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End single service style1-->
            <!--Start single service style1-->
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="single-service-style1 wow fadeInUp animated" data-wow-delay="0ms" data-wow-duration="1200ms"
                    style="visibility: visible; animation-duration: 1200ms; animation-delay: 0ms; animation-name: fadeInUp;">
                    <div class="img-holder">
                        <img src="images/services/co3.jpg" alt="Awesome Image">
                        <div class="overlay-style-two"></div>
                        <div class="overlay-content-box">
                            <div class="box">
                                <div class="inner">

                                    <div class="title">
                                        <h3><a href="booking.php">Charity Event</a></h3>
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
                                    <h3><a href="booking.php">Charity Event</a></h3>
                                </div>
                                <div class="read-more">
                                    <a href="#"><span class="icon-next"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="overlay-content">
                            <div class="text">
                                <p>At Plantastic Events, we create inspiring charity events with thoughtful themes and
                                    decor that amplify your cause and make a meaningful impact on your guests.</p>
                            </div>
                            <div class="read-more">
                                <a href="#"><span class="icon-next"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End single service style1-->

        </div>
    </div>

    <!-- End Cards -->

    <!--Start Themes -->

    <section class="latest-blog-area style2">

    </section>

    <!--End Themes -->
    <!--Start Footer Contact Info Area-->
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
                                        <p>Sr. No. 149, VIP Road, Vesu,<br>Bharthana, Surat, Gujarat 395007</p>
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
                                        <p>+91 998 877 6655<br> <span>Mon - Friday:</span> 9.00am to 6.00pm</p>
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
                                        <p>support@plantasticevents.com<br> plantasticevents@gmail.com</p>
                                    </div>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--End Footer Contact Info Area-->

    <!--Start footer bottom area-->
    <section class="footer-bottom-area style3">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="copyright-text text-center">
                        <p><a href="#" target="_blank">plantastic Events</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End footer bottom area-->


    <div class="scroll-to-top scroll-to-target" data-target="html" style="display: block;"><span
            class="fa fa-angle-up"></span></div>

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
    <script src="js/jquery.bootstrap-touchspin.js"></script>

    <script src="assets/language-switcher/jquery.polyglot.language.switcher.js"></script>
    <script src="assets/timepicker/timePicker.js"></script>
    <script src="assets/html5lightbox/html5lightbox.js"></script>

    <script src="assets/bootstrap-sl-1.12.1/bootstrap-select.js"></script>
    <script src="assets/jquery-ui-1.11.4/jquery-ui.js"></script>

    <!-- thm custom script -->
    <script src="js/custom.js"></script>

    <?php

    $user = $_SESSION['customer'];

    if ($user) {
        $status = 'true';
    } else {
        $status = 'false';
    }

    ?>

    <script>

        const isLoggedIn = <?php echo $status; ?>; // change to false to simulate logged out

        if (isLoggedIn) {
            const navButtons = document.getElementById("navButtons");
            const joinBtn = document.getElementById("joinButton");

            // Remove the join button
            if (joinBtn) {
                joinBtn.remove();
            }

            // Create a new "Profile" button
            const profileBtn = document.createElement("li");
            profileBtn.className = "nav-item";
            profileBtn.innerHTML = `<a class="navigation clearfix" href="profile.php">Profile</a>`;

            navButtons.appendChild(profileBtn);
        }
    </script>
    <script src="js/customer-navbar-dropdown-final.js?v=20260728-sticky2"></script>
</body>

</html>