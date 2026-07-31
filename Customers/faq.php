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


<!-- faq 06:46:46 GMT -->

<head>
    <meta charset="UTF-8">
    <title>FAQ’s || Plantastic Events</title>

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
    <div class="boxed_wrapper">

        <!-- <div class="preloader"></div> -->



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
                                        <li><a href="Couples.php">Couple Exclusive</a></li>
                                        <li><a href="Birthday.php">Birthdays</a></li>
                                        <li><a href="Party.php">Parties</a></li>
                                        <li><a href="Corporate.php">Corporate</a> </li>
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

        <!--Start breadcrumb area-->
        <section class="breadcrumb-area style2" style="background-image: url(images/resources/breadcrumb-bg-2.jpg);">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="inner-content-box clearfix">
                            <div class="title-s2 text-center">
                                <span>Custoemrs FAQ’s</span>
                                <h1>Find Answers to Your Queries</h1>
                            </div>
                            <div class="breadcrumb-menu float-left">
                                <ul class="clearfix">
                                    <li><a href="index-3.php">Home</a></li>
                                    <li class="active">FAQ’s</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End breadcrumb area-->

        <!--Start faq area-->
        <section class="faq-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="faq-content-box">
                            <div class="accordion-box">
                                <!--Start single accordion box-->
                                <div class="accordion accordion-block">
                                    <div class="accord-btn">
                                        <h4>What types of events does Plantastic Events organize?</h4>
                                    </div>
                                    <div class="accord-content">
                                        <p>We specialize in organizing a wide range of events, including weddings,
                                            corporate events, birthday parties, anniversaries, cultural programs, and
                                            community gatherings. If you can imagine it, we can plan it! </p>
                                    </div>
                                </div>
                                <!--End single accordion box-->
                                <!--Start single accordion box-->
                                <div class="accordion accordion-block">
                                    <div class="accord-btn active">
                                        <h4>How far in advance should I book my event with you?</h4>
                                    </div>
                                    <div class="accord-content collapsed">
                                        <p>We recommend booking at least 2-3 months in advance for large events like
                                            weddings and corporate conferences. For smaller events, 3-4 weeks is usually
                                            sufficient. However, feel free to contact us for last-minute planning; we’ll
                                            do our best to accommodate your request.
                                        </p>
                                    </div>
                                </div>
                                <!--End single accordion box-->
                                <!--Start single accordion box-->
                                <div class="accordion accordion-block">
                                    <div class="accord-btn">
                                        <h4>Can you help us find a venue for our event?</h4>
                                    </div>
                                    <div class="accord-content">
                                        <p>Absolutely! We have partnerships with a variety of venues, including banquet
                                            halls, outdoor gardens, conference centers, and more. Based on your
                                            preferences and budget, we’ll help you select the perfect location.
                                        </p>
                                    </div>
                                </div>
                                <!--End single accordion box-->
                                <!--Start single accordion box-->
                                <div class="accordion accordion-block">
                                    <div class="accord-btn">
                                        <h4>What services do you provide?</h4>
                                    </div>
                                    <div class="accord-content">
                                        <p>Our services include event planning, venue selection, decoration,
                                            entertainment, photography, and on-site coordination. We offer customizable
                                            packages to suit your specific needs.</p>
                                    </div>
                                </div>
                                <!--End single accordion box-->
                                <!--Start single accordion box-->
                                <div class="accordion accordion-block">
                                    <div class="accord-btn">
                                        <h4>Can I customize the decorations and themes for my event?</h4>
                                    </div>
                                    <div class="accord-content">
                                        <p>Of course! At Plantastic Events, we believe every event should be unique. We
                                            work closely with you to create personalized themes and decorations that
                                            reflect your vision.</p>
                                    </div>
                                </div>
                                <!--End single accordion box-->
                                <!--Start single accordion box-->
                                <div class="accordion accordion-block">
                                    <div class="accord-btn">
                                        <h4>What is your pricing structure?</h4>
                                    </div>
                                    <div class="accord-content">
                                        <p>Our pricing depends on the type and scale of the event, as well as the
                                            services required. We offer transparent and competitive pricing with no
                                            hidden costs. Contact us for a free consultation and quote.</p>
                                    </div>
                                </div>
                                <!--End single accordion box-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End faq area-->



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
                            <p><a href="index-3.php" target="_blank">Plantastic Events</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End footer bottom area-->

    </div>


    <div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>



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

    <script src="js/map-helper.js"></script>

    <script src="assets/language-switcher/jquery.polyglot.language.switcher.js"></script>
    <script src="assets/timepicker/timePicker.js"></script>
    <script src="assets/html5lightbox/html5lightbox.js"></script>

    <!--Revolution Slider-->
    <script src="plugins/revolution/js/jquery.themepunch.revolution.min.js"></script>
    <script src="plugins/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script src="plugins/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script src="plugins/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
    <script src="plugins/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script src="plugins/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script src="plugins/revolution/js/extensions/revolution.extension.migration.min.js"></script>
    <script src="plugins/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
    <script src="plugins/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
    <script src="plugins/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script src="plugins/revolution/js/extensions/revolution.extension.video.min.js"></script>
    <script src="js/main-slider-script.js"></script>

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


<!-- faq 06:46:49 GMT -->

</html>