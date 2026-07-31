<?php
require_once __DIR__ . '/../includes/bootstrap.php';
// if(!isset($_SESSION['customer']))
// {
//      header('location:login.php');
// }


// Home gallery: latest approved organizer event images
$home_gallery = $conn->query(
    "SELECT e.eve_name, e.event_type, e.image1, e.image2, o.org_id, o.user_name, o.company_name, o.city
     FROM tbl_event e
     INNER JOIN tbl_organizer o ON o.org_id = e.org_id
     WHERE o.approve = 'Approve' AND (o.block = '' OR o.block IS NULL)
     ORDER BY e.evn_id DESC
     LIMIT 12"
);
?>

<!DOCTYPE html>
<html lang="en">


<!-- index-2 06:41:43 GMT -->

<head>
    <meta charset="UTF-8">
    <title>Plantastic Events</title>

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

    <!-- <link rel="stylesheet" href="css/customer-premium.css"> -->
    <!-- <link rel="stylesheet" href="css/golden-refined-v3.css"> -->
    <link rel="stylesheet" href="css/customer-navbar-logo.css?v=20260728-sticky2">
    <link rel="stylesheet" href="css/customer-navbar-dropdown-final.css">
    <!-- <link rel="stylesheet" href="css/customer-home-hero.css"> -->

    <style>
        .pe-video-trigger {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-height: 50px;
            padding: 7px 18px 7px 7px;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 999px;
            color: #fff;
            background: rgba(18, 15, 10, .72);
            box-shadow: 0 10px 28px rgba(0, 0, 0, .18);
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 800;
            transition: .2s ease
        }

        .pe-video-trigger:hover,
        .pe-video-trigger:focus {
            color: #fff;
            background: rgba(18, 15, 10, .94);
            border-color: #d4af37;
            outline: none;
            transform: translateY(-2px)
        }

        .pe-video-trigger-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            border-radius: 50%;
            color: #17130d;
            background: linear-gradient(135deg, #efd471, #c99c24)
        }

        .pe-video-modal {
            position: fixed;
            inset: 0;
            z-index: 999999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(7, 6, 4, .88);
            backdrop-filter: blur(9px)
        }

        .pe-video-modal.is-open {
            display: flex
        }

        .pe-video-dialog {
            width: min(960px, 100%);
            overflow: hidden;
            border: 1px solid rgba(212, 175, 55, .38);
            border-radius: 20px;
            background: #0f0d09;
            box-shadow: 0 30px 90px rgba(0, 0, 0, .58)
        }

        .pe-video-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 16px 14px 20px;
            border-bottom: 1px solid rgba(212, 175, 55, .18);
            background: linear-gradient(135deg, #17130d, #211a0d)
        }

        .pe-video-modal-title {
            margin: 0;
            color: #f2e7c7;
            font-size: 15px;
            font-weight: 800
        }

        .pe-video-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 50%;
            color: #fff;
            background: rgba(255, 255, 255, .06);
            cursor: pointer;
            font-size: 24px
        }

        .pe-video-frame {
            width: 100%;
            aspect-ratio: 16/9;
            background: #000
        }

        .pe-video-frame video {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #000
        }

        body.pe-video-open {
            overflow: hidden
        }

        @media(max-width:767px) {
            .pe-video-modal {
                padding: 12px
            }

            .pe-video-dialog {
                border-radius: 15px
            }

            .pe-video-trigger {
                min-height: 46px;
                padding-right: 14px;
                font-size: 12px
            }

            .pe-video-trigger-icon {
                width: 33px;
                height: 33px;
                flex-basis: 33px
            }
        }
    </style>

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
                                        <li><a href="Ceremony.php">Ceremony</a> </li>
                                        <li><a href="Couples.php">Couple Exclusive</a></li>
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
                                <!-- <li><a href="login.php">Sign In</a></li> -->
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

        <!--Main Slider-->
        <section class="main-slider style2">
            <div class="rev_slider_wrapper fullwidthbanner-container" id="rev_slider_one_wrapper" data-source="gallery">
                <div class="rev_slider fullwidthabanner" id="rev_slider_two" data-version="5.4.1">
                    <ul>
                        <li data-description="Slide Description" data-easein="default" data-easeout="default"
                            data-fsmasterspeed="1500" data-fsslotamount="7" data-fstransition="fade"
                            data-hideafterloop="0" data-hideslideonmobile="off" data-index="rs-1689"
                            data-masterspeed="default" data-param1="" data-param10="" data-param2="" data-param3=""
                            data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9=""
                            data-rotate="0" data-saveperformance="off" data-slotamount="default"
                            data-thumb="images/slides/v2-1.jpg" data-title="Slide Title"
                            data-transition="parallaxvertical">

                            <img alt="" class="rev-slidebg" data-bgfit="cover" data-bgparallax="10"
                                data-bgposition="center center" data-bgrepeat="no-repeat" data-no-retina=""
                                src="images/slides/v2-1.jpg">

                            <div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on"
                                data-type="text" data-height="none" data-width="['800','800','700','400']"
                                data-whitespace="normal" data-hoffset="['15','15','15','15']"
                                data-voffset="['-80','-95','-80','-90']" data-x="['left','left','left','left']"
                                data-y="['middle','middle','middle','middle']"
                                data-textalign="['top','top','top','top']"
                                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                                style="z-index: 7; white-space: nowrap;">
                                <div class="slide-content left-slide">
                                    <div class="big-title">
                                        Your Vision, <br>Our Passion.
                                    </div>
                                </div>
                            </div>
                            <div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on"
                                data-type="text" data-height="none" data-width="['800','800','700','400']"
                                data-whitespace="normal" data-hoffset="['15','15','15','15']"
                                data-voffset="['25','0','-5','-20']" data-x="['left','left','left','left']"
                                data-y="['middle','middle','middle','middle']"
                                data-textalign="['top','top','top','top']"
                                data-frames='[{"from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1500,"ease":"Power3.easeInOut"},
                        {"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                                style="z-index: 7; white-space: nowrap;">
                                <div class="slide-content left-slide">
                                    <div class="text">Creating lasting impressions through Fantastic Events.</div>
                                </div>
                            </div>
                            <div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on"
                                data-type="text" data-height="none" data-width="['800','800','700','400']"
                                data-whitespace="normal" data-hoffset="['15','15','15','15']"
                                data-voffset="['105','90','75','65']" data-x="['left','left','left','left']"
                                data-y="['middle','middle','middle','middle']"
                                data-textalign="['top','top','top','top']"
                                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1500,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                                style="z-index: 7; white-space: nowrap;">
                                <div class="slide-content left-slide">
                                    <!-- <div class="btn-box">
                                        <a class="html5lightbox play-button" title="Birthday Decoration">
                                            <video src="/images/services/v1.mp4">
                                            <span class="flaticon-play-button"></span>
                                        </video>
                                        </a>
                                        <a class="slide-style2-button" href="#">More About Us</a>
                                    </div> -->


                                    <div class="btn-box">
                                        <!-- Button to Play Video -->
                                        <button type="button" class="pe-video-trigger"
                                            data-video="images/services/v1.mp4" data-title="Birthday Decoration">
                                            <span class="pe-video-trigger-icon"><span
                                                    class="flaticon-play-button"></span></span>
                                            <span>Play Video</span>
                                        </button>

                                        <!-- More About Us Button -->
                                        <a class="slide-style2-button" href="#">More About Us</a>
                                    </div>

                                </div>
                            </div>



                        </li>

                        <li data-description="Slide Description" data-easein="default" data-easeout="default"
                            data-fsmasterspeed="1500" data-fsslotamount="7" data-fstransition="fade"
                            data-hideafterloop="0" data-hideslideonmobile="off" data-index="rs-1687"
                            data-masterspeed="default" data-param1="" data-param10="" data-param2="" data-param3=""
                            data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9=""
                            data-rotate="0" data-saveperformance="off" data-slotamount="default"
                            data-thumb="images/slides/v2-2.jpg" data-title="Slide Title"
                            data-transition="parallaxvertical">

                            <img alt="" class="rev-slidebg" data-bgfit="cover" data-bgparallax="10"
                                data-bgposition="center center" data-bgrepeat="no-repeat" data-no-retina=""
                                src="images/slides/v2-2.jpg">

                            <div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on"
                                data-type="text" data-height="none" data-width="['800','800','700','400']"
                                data-whitespace="normal" data-hoffset="['15','15','15','15']"
                                data-voffset="['-80','-95','-80','-90']" data-x="['left','left','left','left']"
                                data-y="['middle','middle','middle','middle']"
                                data-textalign="['top','top','top','top']"
                                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                                style="z-index: 7; white-space: nowrap;">
                                <div class="slide-content left-slide">
                                    <div class="big-title">
                                        Your Moment, <br>Magnified.
                                    </div>
                                </div>
                            </div>
                            <div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on"
                                data-type="text" data-height="none" data-width="['800','800','700','400']"
                                data-whitespace="normal" data-hoffset="['15','15','15','15']"
                                data-voffset="['25','0','-5','-20']" data-x="['left','left','left','left']"
                                data-y="['middle','middle','middle','middle']"
                                data-textalign="['top','top','top','top']"
                                data-frames='[{"from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1500,"ease":"Power3.easeInOut"},
                        {"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                                style="z-index: 7; white-space: nowrap;">
                                <div class="slide-content left-slide">
                                    <div class="text">Turning Ordinary into Extraordinary.</div>
                                </div>
                            </div>
                            <div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on"
                                data-type="text" data-height="none" data-width="['800','800','700','400']"
                                data-whitespace="normal" data-hoffset="['15','15','15','15']"
                                data-voffset="['105','90','75','65']" data-x="['left','left','left','left']"
                                data-y="['middle','middle','middle','middle']"
                                data-textalign="['top','top','top','top']"
                                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1500,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                                style="z-index: 7; white-space: nowrap;">
                                <div class="slide-content left-slide">
                                    <div class="btn-box">
                                        <button type="button" class="pe-video-trigger"
                                            data-video="images/services/v2.mp4" data-title="Valentine Decoration">
                                            <span class="pe-video-trigger-icon"><span
                                                    class="flaticon-play-button"></span></span>
                                            <span>Play Video</span>
                                        </button>
                                        <a class="slide-style2-button" href="#">Services We Provide</a>
                                    </div>
                                </div>
                            </div>

                        </li>

                        <li data-description="Slide Description" data-easein="default" data-easeout="default"
                            data-fsmasterspeed="1500" data-fsslotamount="7" data-fstransition="fade"
                            data-hideafterloop="0" data-hideslideonmobile="off" data-index="rs-1688"
                            data-masterspeed="default" data-param1="" data-param10="" data-param2="" data-param3=""
                            data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9=""
                            data-rotate="0" data-saveperformance="off" data-slotamount="default"
                            data-thumb="images/slides/v2-3.jpg" data-title="Slide Title"
                            data-transition="parallaxvertical">

                            <img alt="" class="rev-slidebg" data-bgfit="cover" data-bgparallax="10"
                                data-bgposition="center center" data-bgrepeat="no-repeat" data-no-retina=""
                                src="images/slides/v2-3.jpg">

                            <div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on"
                                data-type="text" data-height="none" data-width="['800','800','700','400']"
                                data-whitespace="normal" data-hoffset="['15','15','15','15']"
                                data-voffset="['-80','-95','-80','-90']" data-x="['left','left','left','left']"
                                data-y="['middle','middle','middle','middle']"
                                data-textalign="['top','top','top','top']"
                                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                                style="z-index: 7; white-space: nowrap;">
                                <div class="slide-content left-slide">
                                    <div class="big-title">
                                        Dreams, <br>Delivered.
                                    </div>
                                </div>
                            </div>
                            <div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on"
                                data-type="text" data-height="none" data-width="['800','800','700','400']"
                                data-whitespace="normal" data-hoffset="['15','15','15','15']"
                                data-voffset="['25','0','-5','-20']" data-x="['left','left','left','left']"
                                data-y="['middle','middle','middle','middle']"
                                data-textalign="['top','top','top','top']"
                                data-frames='[{"from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1500,"ease":"Power3.easeInOut"},
                        {"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                                style="z-index: 7; white-space: nowrap;">
                                <div class="slide-content left-slide">
                                    <div class="text">Crafting Experiences, One Event at a Time.</div>
                                </div>
                            </div>
                            <div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on"
                                data-type="text" data-height="none" data-width="['800','800','700','400']"
                                data-whitespace="normal" data-hoffset="['15','15','15','15']"
                                data-voffset="['105','90','75','65']" data-x="['left','left','left','left']"
                                data-y="['middle','middle','middle','middle']"
                                data-textalign="['top','top','top','top']"
                                data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1500,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                                style="z-index: 7; white-space: nowrap;">
                                <div class="slide-content left-slide">
                                    <div class="btn-box">
                                        <button type="button" class="pe-video-trigger"
                                            data-video="images/services/v3.mp4" data-title="Mehndi Decoration">
                                            <span class="pe-video-trigger-icon"><span
                                                    class="flaticon-play-button"></span></span>
                                            <span>Play Video</span>
                                        </button>
                                        <a class="slide-style2-button" href="#">View Our Projects</a>
                                    </div>
                                </div>
                            </div>



                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!--End Main Slider-->

        <!--Start About Style2 Area-->
        <section class="about-style2-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5">
                        <div class="about-style2-text">
                            <div class="sec-title">
                                <p>About Plantastic Events</p>

                                <div class="title">Creating <br>Magical Moments,<br><span> Every Step of the Way</span>
                                </div>
                            </div>
                            <div class="text">
                                <p>We believe in crafting experiences that leave lasting impressions.
                                    With a passion for perfection, we design, plan, and execute events that reflect your
                                    unique vision and personality.
                                </p>
                                <br>
                                <p>Let Plantastic Events turn your dream celebration into a breathtaking reality!</p>
                            </div>
                            <!-- <div class="authorised-info">
                                <div class="signature">
                                    <img src="images/icon/signature.png" alt="Signature">
                                </div>
                                <div class="name">
                                    <h3>KL. Carl Ambrose</h3>
                                    <p>Chaiman & Founder</p>
                                </div>
                            </div> -->
                            <!-- <div class="button">
                                <a class="btn-one" href="#">More About Us<span class="flaticon-next"></span></a>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <div class="about-style2-image-box">
                            <div class="pattern wow slideInUp" data-wow-delay="100ms" data-wow-duration="1500ms"></div>
                            <div class="image">
                                <img src="images/resources/about-style2-image.jpg" alt="Awesome Image">
                                <div class="overlay-box">
                                    <div class="title">
                                        <h3>We're <span>Not Just Planners,<br>We're Dream Weavers.</span></h3>
                                    </div>
                                    <div class="button">
                                        <a href="#"><span class="icon-back"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End About Style2 Area-->
        <!--End Why choose Area-->

        <!--Start Recently Project style2 Area-->
        <section class="recently-project-style2-area">
            <div class="container">
                <div class="sec-title text-center">
                    <p>Our Recent Events</p>
                    <div class="title">Memorable Moments <span>We've Created</span></div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="project-carousel-v2 owl-carousel owl-theme">
                            <!--Start single project style1-->
                            <div class="single-project-style2">
                                <div class="img-holder">
                                    <img src="images/projects/lat-pro-v2-1.jpg" alt="Awesome Image">
                                    <div class="read-more">
                                        <a href="#"><span class="icon-next"></span></a>
                                    </div>
                                    <div class="title-box">
                                        <span>Birthday Decoration</span>
                                        <h3>By Sony Events</h3>
                                    </div>
                                </div>
                            </div>
                            <!--End single project style1-->
                            <!--Start single project style1-->
                            <div class="single-project-style2">
                                <div class="img-holder">
                                    <img src="images/projects/lat-pro-v2-2.jpg" alt="Awesome Image">
                                    <div class="read-more">
                                        <a href="#"><span class="icon-next"></span></a>
                                    </div>
                                    <div class="title-box">
                                        <span>Wedding Anniversery</span>
                                        <h3>By JBM Event</h3>
                                    </div>
                                </div>
                            </div>
                            <!--End single project style1-->
                            <!--Start single project style1-->
                            <div class="single-project-style2">
                                <div class="img-holder">
                                    <img src="images/projects/lat-pro-v2-3.jpg" alt="Awesome Image">
                                    <div class="read-more">
                                        <a href="#"><span class="icon-next"></span></a>
                                    </div>
                                    <div class="title-box">
                                        <span>Marriage Proposal</span>
                                        <h3>By Shaadi Tadka</h3>
                                    </div>
                                </div>
                            </div>
                            <!--End single project style1-->

                            <!--Start single project style1-->
                            <div class="single-project-style2">
                                <div class="img-holder">
                                    <img src="images/projects/lat-pro-v2-4.jpg" alt="Awesome Image">
                                    <div class="read-more">
                                        <a href="#"><span class="icon-next"></span></a>
                                    </div>
                                    <div class="title-box">
                                        <span>Valentine</span>
                                        <h3>By Magical Wedding Planners</h3>
                                    </div>
                                </div>
                            </div>
                            <!--End single project style1-->
                            <!--Start single project style1-->
                            <div class="single-project-style2">
                                <div class="img-holder">
                                    <img src="images/projects/lat-pro-v2-5.jpg" alt="Awesome Image">
                                    <div class="read-more">
                                        <a href="#"><span class="icon-next"></span></a>
                                    </div>
                                    <div class="title-box">
                                        <span>Retirement</span>
                                        <h3>By Rock & Roll Event</h3>
                                    </div>
                                </div>
                            </div>
                            <!--End single project style1-->
                            <!--Start single project style1-->
                            <div class="single-project-style2">
                                <div class="img-holder">
                                    <img src="images/projects/lat-pro-v2-6.jpg" alt="Awesome Image">
                                    <div class="read-more">
                                        <a href="#"><span class="icon-next"></span></a>
                                    </div>
                                    <div class="title-box">
                                        <span>Engagement</span>
                                        <h3>By Create My Event</h3>
                                    </div>
                                </div>
                            </div>
                            <!--End single project style1-->

                            <!--Start single project style1-->
                            <div class="single-project-style2">
                                <div class="img-holder">
                                    <img src="images/projects/lat-pro-v2-7.jpg" alt="Awesome Image">
                                    <div class="read-more">
                                        <a href="#"><span class="icon-next"></span></a>
                                    </div>
                                    <div class="title-box">
                                        <span>Bachelorette Party</span>
                                        <h3>By Creativity Events</h3>
                                    </div>
                                </div>
                            </div>
                            <!--End single project style1-->
                            <!--Start single project style1-->
                            <div class="single-project-style2">
                                <div class="img-holder">
                                    <img src="images/projects/lat-pro-v2-8.jpg" alt="Awesome Image">
                                    <div class="read-more">
                                        <a href="#"><span class="icon-next"></span></a>
                                    </div>
                                    <div class="title-box">
                                        <span>Mehndi & Haldi</span>
                                        <h3>By Empire Events</h3>
                                    </div>
                                </div>
                            </div>
                            <!--End single project style1-->
                            <!--Start single project style1-->
                            <div class="single-project-style2">
                                <div class="img-holder">
                                    <img src="images/projects/lat-pro-v2-9.jpg" alt="Awesome Image">
                                    <div class="read-more">
                                        <a href="#"><span class="icon-next"></span></a>
                                    </div>
                                    <div class="title-box">
                                        <span>Wedding</span>
                                        <h3>By Destiny Mandap & Caterers</h3>
                                    </div>
                                </div>
                            </div>
                            <!--End single project style1-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Recently Project style2 Area-->



        <!--Start Testimonial Style2 Area-->
        <section class="testimonial-style2-area">
            <div class="container">
                <div class="sec-title text-center">
                    <p>Memorable Experiences Shared</p>
                    <div class="title">Our Happy <span>Clients</span></div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="testimonial-style2-content">
                            <div class="testimonial-carousel owl-carousel owl-theme">
                                <!--Start Single Testimonial style2-->
                                <div class="single-testimonial-style2 text-center">
                                    <div class="inner-content">
                                        <div class="static-content">
                                            <div class="quote-icon">
                                                <span class="icon-quote3"></span>
                                            </div>
                                            <div class="text-box">
                                                <p>"Absolutely Amazing!"
                                                    Plantastic Events made my dream wedding come to life! Highly
                                                    recommend them for any special occasion!</p>
                                            </div>
                                            <div class="client-info">
                                                <div class="review-box">
                                                    <ul>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h3>Meenakshi Rathor</h3>
                                            </div>
                                        </div>
                                        <div class="overlay-content">
                                            <div class="img-box">
                                                <img src="images/testimonial/testi-style2-1.png" alt="Awesome Image">
                                            </div>
                                            <div class="text-box">
                                                <p>From the decorations to the organization, everything was flawless.
                                                    Their attention to detail is unmatched.</p>
                                                <div class="quote-icon">
                                                    <span class="icon-quote3"></span>
                                                </div>
                                            </div>
                                            <div class="client-info">
                                                <div class="review-box">
                                                    <ul>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h3>Minakshi Rathor</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End Single Testimonial style2 -->

                                <!--Start Single Testimonial style2-->
                                <div class="single-testimonial-style2 text-center">
                                    <div class="inner-content">
                                        <div class="static-content">
                                            <div class="quote-icon">
                                                <span class="icon-quote3"></span>
                                            </div>
                                            <div class="text-box">
                                                <p>"Perfect Execution!"
                                                    I hired Plantastic Events for my daughter’s birthday party, and they
                                                    went above and beyond!</p>
                                            </div>
                                            <div class="client-info">
                                                <div class="review-box">
                                                    <ul>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h3>Saraswati Chandra</h3>
                                            </div>
                                        </div>
                                        <div class="overlay-content">
                                            <div class="img-box">
                                                <img src="images/testimonial/testi-style2-2.png" alt="Awesome Image">
                                            </div>
                                            <div class="text-box">
                                                <p>The theme, activities, and setup were all magical. The team is
                                                    incredibly professional and creative. I couldn’t be happier!</p>
                                                <div class="quote-icon">
                                                    <span class="icon-quote3"></span>
                                                </div>
                                            </div>
                                            <div class="client-info">
                                                <div class="review-box">
                                                    <ul>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h3>Saraswati Chandra</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End Single Testimonial style2 -->

                                <!--Start Single Testimonial style2-->
                                <div class="single-testimonial-style2 text-center">
                                    <div class="inner-content">
                                        <div class="static-content">
                                            <div class="quote-icon">
                                                <span class="icon-quote3"></span>
                                            </div>
                                            <div class="text-box">
                                                <p>Plantastic Events made event planning so easy for us. They turned our
                                                    corporate gala into a night to remember.</p>
                                            </div>
                                            <div class="client-info">
                                                <div class="review-box">
                                                    <ul>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h3>Mahima Chaudhary</h3>
                                            </div>
                                        </div>
                                        <div class="overlay-content">
                                            <div class="img-box">
                                                <img src="images/testimonial/testi-style2-3.png" alt="Awesome Image">
                                            </div>
                                            <div class="text-box">
                                                <p>Their team took care of every little detail, and the outcome was
                                                    stunning. They turned our corporate gala into a night to remember.
                                                </p>
                                                <div class="quote-icon">
                                                    <span class="icon-quote3"></span>
                                                </div>
                                            </div>
                                            <div class="client-info">
                                                <div class="review-box">
                                                    <ul>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                        <li><i class="fa fa-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h3>Mahima Chaudhary</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End Single Testimonial style2 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Testimonial Style2 Area-->

        <!--Start latest blog area style2-->
        <section class="latest-blog-area style2">
            <div class="container inner-content">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="sec-title float-left">
                            <p>Event News & Updates</p>
                            <div class="title">Latest From <span>Plantastic Blog</span></div>
                        </div>
                        <div class="more-blog-button float-right">
                            <a class="btn-two" href="blog.html">More Event Highlights<span
                                    class="flaticon-next"></span></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!--Start single blog post-->
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="single-blog-post wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <div class="img-holder">
                                <img src="images/blog/latest-b-v1-1.jpg" alt="Awesome Image">
                                <div class="overlay-style-two"></div>
                                <div class="overlay">
                                    <div class="box">
                                        <div class="link-icon">
                                            <a href="#"><span class="flaticon-zoom"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-holder">
                                <div class="post-date">
                                    <h3>12 <span>Nov 2024</span></h3>
                                </div>
                                <div class="meta-box">
                                    <ul class="meta-info">
                                        <li>The <a href="#">Birthday Party</a></li>
                                        <li>By <a href="#">E & M Events</a></li>
                                    </ul>
                                </div>
                                <h3 class="blog-title"><a href="blog-single.html">Low-Cost Event Planning Ideas</a></h3>
                                <div class="text">
                                    <p>Just like transforming challenges into unforgettable moments, these ideas prove
                                        that creating magical events can be both simple and budget-friendly.</p>
                                    <!-- <a class="btn-two" href="#">Read More<span class="flaticon-next"></span></a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End single blog post-->
                    <!--Start single blog post-->
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="single-blog-post wow fadeInLeft" data-wow-delay="200ms" data-wow-duration="1500ms">
                            <div class="img-holder">
                                <img src="images/blog/latest-b-v1-2.jpg" alt="Awesome Image">
                                <div class="overlay-style-two"></div>
                                <div class="overlay">
                                    <div class="box">
                                        <div class="link-icon">
                                            <a href="#"><span class="flaticon-zoom"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-holder">
                                <div class="post-date">
                                    <h3>24 <span>Nov 2024</span></h3>
                                </div>
                                <div class="meta-box">
                                    <ul class="meta-info">
                                        <li>The <a href="#">Wedding Anniversery</a></li>
                                        <li>By <a href="#">Sandhya Events</a></li>
                                    </ul>
                                </div>
                                <h3 class="blog-title"><a href="blog-single.html">Event Concepts for Celebration</a>
                                </h3>
                                <div class="text">
                                    <p>Just as we transform challenges into seamless celebrations, these innovative
                                        ideas are designed to make every moment extraordinary.</p>
                                    <!-- <a class="btn-two" href="#">Read More<span class="flaticon-next"></span></a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End single blog post-->
                    <!--Start single blog post-->
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="single-blog-post wow fadeInLeft" data-wow-delay="400ms" data-wow-duration="1500ms">
                            <div class="img-holder">
                                <img src="images/blog/latest-b-v1-3.jpg" alt="Awesome Image">
                                <div class="overlay-style-two"></div>
                                <div class="overlay">
                                    <div class="box">
                                        <div class="link-icon">
                                            <a href="#"><span class="flaticon-zoom"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-holder">
                                <div class="post-date">
                                    <h3>29 <span>Nov 2024</span></h3>
                                </div>
                                <div class="meta-box">
                                    <ul class="meta-info">
                                        <li>The <a href="#">Marriage Proposal</a></li>
                                        <li>By <a href="#">Mantraa Event</a></li>
                                    </ul>
                                </div>
                                <h3 class="blog-title"><a href="blog-single.html">Our Event Trends Prediction 2019</a>
                                </h3>
                                <div class="text">
                                    <p>Every celebration deserves to be memorable. In certain circumstances, creativity
                                        meets responsibility to craft unforgettable experiences.</p>
                                    <!-- <a class="btn-two" href="#">Read More<span class="flaticon-next"></span></a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End single blog post-->

                </div>
            </div>
        </section>
        <!--End latest blog area style2-->

        <!--Start Brand area style2-->
        <section class="brand-area style2">
            <div class="container">
                <div class="sec-title text-center">
                    <p>Your City, Our Expertise</p>
                    <div class="title">Serving More than <span>2000 Cities Across India</span></div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <ul>
                            <!--Start Single Brand Item-->
                            <li class="single-brand-item style2 wow fadeInUp" data-wow-delay="0ms"
                                data-wow-duration="1500ms">
                                <a href="#"><img src="images/brand/1.png" alt="Awesome Brand Image"></a>
                                <div class="overlay-content">
                                    <p>Surat</p>
                                </div>
                            </li>
                            <!--End Single Brand Item-->
                            <!--Start Single Brand Item-->
                            <li class="single-brand-item style2 wow fadeInUp" data-wow-delay="200ms"
                                data-wow-duration="1500ms">
                                <a href="#"><img src="images/brand/2.png" alt="Awesome Brand Image"></a>
                                <div class="overlay-content">
                                    <p>Mumbai</p>
                                </div>
                            </li>
                            <!--End Single Brand Item-->
                            <!--Start Single Brand Item-->
                            <li class="single-brand-item style2 wow fadeInUp" data-wow-delay="400ms"
                                data-wow-duration="1500ms">
                                <a href="#"><img src="images/brand/3.png" alt="Awesome Brand Image"></a>
                                <div class="overlay-content">
                                    <p>Delhi</p>
                                </div>
                            </li>
                            <!--End Single Brand Item-->
                            <!--Start Single Brand Item-->
                            <li class="single-brand-item style2 wow fadeInUp" data-wow-delay="600ms"
                                data-wow-duration="1500ms">
                                <a href="#"><img src="images/brand/4.png" alt="Awesome Brand Image"></a>
                                <div class="overlay-content">
                                    <p>Chennai</p>
                                </div>
                            </li>
                            <!--End Single Brand Item-->
                            <!--Start Single Brand Item-->
                            <li class="single-brand-item style2 wow fadeInUp" data-wow-delay="800ms"
                                data-wow-duration="1500ms">
                                <a href="#"><img src="images/brand/5.png" alt="Awesome Brand Image"></a>
                                <div class="overlay-content">
                                    <p>Hyderbad</p>
                                </div>
                            </li>
                            <!--End Single Brand Item-->
                            <!--Start Single Brand Item-->
                            <li class="single-brand-item style2 wow fadeInUp" data-wow-delay="800ms"
                                data-wow-duration="1500ms">
                                <a href="#"><img src="images/brand/6.png" alt="Awesome Brand Image"></a>
                                <div class="overlay-content">
                                    <p>Amritsar</p>
                                </div>
                            </li>
                            <!--End Single Brand Item-->

                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--End Brand area style2-->


        <!--Start Plantastic Live Gallery-->
        <section class="instagram-area" style="padding:70px 0;background:linear-gradient(180deg,#fff7ed,#ffffff);">
            <div class="container">
                <div class="sec-title text-center" style="margin-bottom:30px">
                    <div class="title" style="font-size:2rem;font-weight:800;letter-spacing:-.02em">Celebration Gallery
                    </div>
                    <p style="color:#78716c;max-width:560px;margin:8px auto 0">Fresh looks from approved organizers on
                        Plantastic — birthdays, weddings, parties and more.</p>
                </div>
                <div class="row clearfix">
                    <?php if ($home_gallery && $home_gallery->num_rows > 0) {
                        $home_gallery->data_seek(0);
                        while ($g = $home_gallery->fetch_assoc()) {
                            $img = !empty($g['image1']) ? $g['image1'] : (!empty($g['image2']) ? $g['image2'] : null);
                            if (!$img)
                                continue;
                            ?>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12" style="margin-bottom:22px">
                                <div
                                    style="border-radius:18px;overflow:hidden;box-shadow:0 10px 30px rgba(28,20,16,.08);border:1px solid #e7ddd2;background:#fff">
                                    <a href="project-single.php?display_org=<?php echo (int) $g['org_id']; ?>">
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($img); ?>"
                                            alt="<?php echo htmlspecialchars($g['eve_name']); ?>"
                                            style="width:100%;height:240px;object-fit:cover;display:block">
                                    </a>
                                    <div style="padding:14px 16px">
                                        <div style="font-weight:800;font-size:1.02rem">
                                            <?php echo htmlspecialchars($g['eve_name']); ?>
                                        </div>
                                        <div style="color:#78716c;font-size:.88rem;margin-top:4px">
                                            <?php echo htmlspecialchars($g['event_type']); ?> ·
                                            <?php echo htmlspecialchars($g['company_name'] ?: $g['user_name']); ?>
                                            <?php if (!empty($g['city']))
                                                echo ' · ' . htmlspecialchars($g['city']); ?>
                                        </div>
                                        <a href="project-single.php?display_org=<?php echo (int) $g['org_id']; ?>"
                                            style="display:inline-block;margin-top:10px;font-weight:700;color:#c2410c;text-decoration:none">View
                                            & book →</a>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="col-12 text-center" style="color:#78716c;padding:30px 0">Event gallery will appear here
                            once organizers add packages.</div>
                    <?php } ?>
                </div>
                <div class="text-center" style="margin-top:10px">
                    <a class="btn-one browse-organizers-btn" href="booking.php"
                        style="display:inline-block;padding:12px 22px;border-radius:999px;background:linear-gradient(135deg,#f59e0b,#c2410c);color:#fff;font-weight:800;text-decoration:none">Browse
                        all organizers</a>
                </div>
            </div>
        </section>
        <!--End Plantastic Live Gallery-->




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
                            <p><a href="https://www.templateshub.net" target="_blank">Plantastic Events</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End footer bottom area-->

    </div>



    <div class="pe-video-modal" id="peVideoModal" aria-hidden="true">
        <div class="pe-video-dialog" role="dialog" aria-modal="true" aria-labelledby="peVideoTitle">
            <div class="pe-video-modal-head">
                <h3 class="pe-video-modal-title" id="peVideoTitle">Plantastic Events</h3>
                <button type="button" class="pe-video-close" id="peVideoClose" aria-label="Close video">&times;</button>
            </div>
            <div class="pe-video-frame">
                <video id="peVideoPlayer" controls playsinline preload="metadata"></video>
            </div>
        </div>
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

    <!-- <script>
(function(){
  try {
    var k='ems_customer_theme';
    var root=document.documentElement;
    var saved=localStorage.getItem(k);
    if(saved==='dark'){ root.classList.add('dark'); document.body.style.background='#0f0c0a'; }
    var btn=document.createElement('button');
    btn.textContent='◐';
    btn.title='Toggle theme hint (full theme on profile pages)';
    btn.style.cssText='position:fixed;right:16px;bottom:16px;z-index:9999;width:44px;height:44px;border-radius:14px;border:0;background:linear-gradient(135deg,#f59e0b,#c2410c);color:#fff;font-size:18px;box-shadow:0 10px 24px rgba(194,65,12,.35);cursor:pointer';
    btn.onclick=function(){
      var dark=root.classList.toggle('dark');
      localStorage.setItem(k, dark?'dark':'light');
      document.body.style.background = dark ? '#0f0c0a' : '';
    };
    document.body.appendChild(btn);
  } catch(e) {}
})();
</script> -->

    <script>
        (function () {
            var modal = document.getElementById('peVideoModal');
            var player = document.getElementById('peVideoPlayer');
            var title = document.getElementById('peVideoTitle');
            var closeBtn = document.getElementById('peVideoClose');
            if (!modal || !player || !title || !closeBtn) return;

            function closeVideo() {
                player.pause();
                player.removeAttribute('src');
                player.load();
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('pe-video-open');
            }

            document.querySelectorAll('.pe-video-trigger').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var src = btn.getAttribute('data-video');
                    if (!src) return;
                    title.textContent = btn.getAttribute('data-title') || 'Plantastic Events';
                    player.src = src;
                    modal.classList.add('is-open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('pe-video-open');
                    var promise = player.play();
                    if (promise && typeof promise.catch === 'function') promise.catch(function () { });
                });
            });

            closeBtn.addEventListener('click', closeVideo);
            modal.addEventListener('click', function (e) { if (e.target === modal) closeVideo(); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modal.classList.contains('is-open')) closeVideo(); });
        })();
    </script>

    <script src="js/customer-navbar-dropdown-final.js?v=20260728-sticky2"></script>
</body>


</html>