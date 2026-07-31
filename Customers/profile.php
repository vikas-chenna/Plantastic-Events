<?php
session_start();

include('../conn.php');

if (!isset($_SESSION['customer'])) {
  echo "<script>alert('Login Now!'); window.location.href='login.php'</script>";
  exit();
}

$cid = $_SESSION['customer'];

if (isset($_POST['logout'])) {
  session_start();
  session_destroy();
  header('location:login.php');
}

//Overview
$query = "SELECT * FROM tbl_customer WHERE cust_id = $cid";
$profile = $conn->query($query);

$query2 = "SELECT * FROM tbl_customer WHERE cust_id = $cid";
$profile2 = $conn->query($query2);

if ($profile2->num_rows > 0) {
  $user = $profile2->fetch_assoc();
}

//left profile
$query3 = "SELECT * FROM tbl_customer WHERE cust_id = $cid";
$profile3 = $conn->query($query3);

if ($profile3->num_rows > 0) {
  while ($user3 = $profile3->fetch_assoc()) {
    $imageData = $user3['profile_pic'];

    if (!empty($imageData)) {
      $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);
    }
  }
}

//edit profile
if (isset($_POST['edit_profile'])) {
  $profile_pic_check = $_POST['profile_pic'];
  $uname = $_POST['uname'];
  $contact = $_POST['contact'];
  $email = $_POST['email'];
  $gender = $_POST['gender'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $pincode = $_POST['pincode'];
  $twitter = $_POST['twitter'];
  $instagram = $_POST['instagram'];
  $facebook = $_POST['facebook'];
  $linkedin = $_POST['linkedin'];


  if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $profile_pic = addslashes(file_get_contents($_FILES['profile_pic']['tmp_name']));

    // $update_pro = "UPDATE tbl_customer SET profile_pic = '$profile_pic' WHERE cust_id = '$cid'";

    $update_pro = "UPDATE tbl_customer SET 
      user_name = '$uname',
      contact = '$contact',
      email = '$email',
      gender = '$gender',
      address2 = '$address',
      city = '$city',
      state = '$state',
      pincode = '$pincode',
      profile_pic = '$profile_pic',
      insta_profile = '$instagram',
      twitter_profile = '$twitter',
      facebook_profile = '$facebook',
      linkedin_profile = '$linkedin' 
      WHERE cust_id = '$cid'";

    $res2 = $conn->query($update_pro);

    if ($res2) {
      echo "<script>alert('Profile picture updated'); window.location.href='profile.php'</script>";
    } else {
      echo "<script>alert('Failed to update profile picture');</script>";
    }

  } else {
    $update_detail1 = "UPDATE tbl_customer SET 
          user_name = '$uname',
          contact = '$contact',
          email = '$email',
          gender = '$gender',
          address2 = '$address',
          city = '$city',
          state = '$state',
          pincode = '$pincode',
          insta_profile = '$instagram',
          twitter_profile = '$twitter',
          facebook_profile = '$facebook',
          linkedin_profile = '$linkedin' 
          WHERE cust_id = '$cid'";

    $res1 = $conn->query($update_detail1);

    if ($res1) {
      echo "<script>alert('Profile updated successfully'); window.location.href='profile.php'</script>";
    } else {
      echo "<script>alert('Failed to update profile');</script>";
    }
  }
}

if (isset($_POST['change_pass'])) {
  $password = $_POST['password'];
  $newpassword = $_POST['newpassword'];
  $renewpassword = $_POST['renewpassword'];

  $sql = "select * from tbl_customer where cust_id = '$cid' AND password = '$password'";
  $res = $conn->query($sql);

  if ($res) {
    if ($newpassword == $renewpassword) {
      $update_pass = "UPDATE tbl_customer SET password = '$newpassword' WHERE cust_id = '$cid'";
      $result = $conn->query($update_pass);

      if ($result) {
        echo "<script>alert('Password Change successfully'); window.location.href='profile.php'</script>";
      }
    } else {
      echo "<script>alert('Password Not Match! Try Again'); window.location.href='profile.php'</script>";
    }
  } else {
    echo "<script>alert('Incorrect Password! Try Again'); window.location.href='profile.php'</script>";
  }
}




$booking_report = "SELECT DISTINCT tbl_organizer.org_id, tbl_booking.b_id, tbl_organizer.user_name,
tbl_organizer.company_name, tbl_booking.eve_type, tbl_booking.booking_date, tbl_booking.status
FROM tbl_booking
INNER JOIN tbl_customer ON tbl_customer.cust_id = tbl_booking.cust_id
INNER JOIN tbl_organizer ON tbl_organizer.org_id = tbl_booking.org_id
WHERE tbl_booking.cust_id = '$cid'
ORDER BY tbl_booking.booking_date DESC";

$done1 = $conn->query($booking_report);

$organizerOptions = [];

if ($done1 && $done1->num_rows > 0) {
  while ($row = $done1->fetch_assoc()) {
    $organizerOptions[] = $row;
  }
}

if (isset($_POST['report'])) {
  $cust_id = $_SESSION['customer']; // adjust if named differently

  // Split combined value from dropdown
  list($org_id, $b_id) = explode('|', $_POST['organizerName']);

  // Get organizer name from DB
  $org_name = '';

  $getOrg = $conn->query("SELECT user_name FROM tbl_organizer WHERE org_id = '$org_id'");
  if ($getOrg && $getOrg->num_rows > 0) {
    $org_row = $getOrg->fetch_assoc();
    $org_name = $org_row['user_name'];
  }

  // Get selected issues
  $repo_name = isset($_POST['issues']) ? implode(', ', $_POST['issues']) : '';

  // Get detailed comments
  $repo_desc = $_POST['reportDetails'];

  // Insert into tbl_report
  $sql = "INSERT INTO tbl_report (cust_id, org_id, b_id, org_name, repo_name, repo_desc)
          VALUES ('$cust_id', '$org_id', '$b_id', '$org_name', '$repo_name', '$repo_desc')";

  if ($conn->query($sql)) {
    echo "<script>alert('Report submitted successfully!');</script>";
  } else {
    echo "<script>alert('Error: " . $conn->error . "');</script>";
  }
}


$notify = "select * from tbl_report where cust_id = '$cid'";
$notify1 = $conn->query($notify);


$your_event = "SELECT tbl_booking.b_id, tbl_organizer.profile_pic, tbl_organizer.user_name, tbl_booking.booking_date, tbl_booking.eve_type 
               FROM tbl_booking 
               INNER JOIN tbl_organizer ON tbl_organizer.org_id = tbl_booking.org_id
               INNER JOIN tbl_customer ON tbl_customer.cust_id = tbl_booking.cust_id  
               WHERE tbl_booking.cust_id='$cid' and tbl_booking.status='confirm'";

$events = $conn->query($your_event);



if (isset($_POST['Receipt'])) {
  $bid = $_POST['bid'];
  header("location:receipt.php?b_ids=$bid");
  //  echo "<script>alert('Incorrect Password! Try Again'); window.location.href='receipt.html'</script>";
}


// Direct customer messaging
$message_notice = '';
if (isset($_POST['send_admin_message'])) {
  $message = trim($_POST['message'] ?? '');
  if ($message !== '') {
    $stmt = $conn->prepare("INSERT INTO tbl_cust_admin_msg (cust_id, cust_msg) VALUES (?, ?)");
    if ($stmt) {
      $stmt->bind_param("is", $cid, $message);
      $message_notice = $stmt->execute() ? 'Message sent to Admin.' : 'Could not send message.';
      $stmt->close();
    }
  }
}
if (isset($_POST['send_org_message'])) {
  $message = trim($_POST['message'] ?? '');
  $target = $_POST['organizer_target'] ?? '';
  if ($message !== '' && strpos($target, '|') !== false) {
    [$org_id, $b_id] = array_map('intval', explode('|', $target, 2));
    $check = $conn->prepare("SELECT b_id FROM tbl_booking WHERE b_id = ? AND cust_id = ? AND org_id = ? LIMIT 1");
    $allowed = false;
    if ($check) {
      $check->bind_param("iii", $b_id, $cid, $org_id);
      $check->execute();
      $allowed = $check->get_result()->num_rows === 1;
      $check->close();
    }
    if ($allowed) {
      $stmt = $conn->prepare("INSERT INTO tbl_org_cust_msg (cust_id, org_id, cust_msg) VALUES (?, ?, ?)");
      if ($stmt) {
        $stmt->bind_param("iis", $cid, $org_id, $message);
        $message_notice = $stmt->execute() ? 'Message sent to Organizer.' : 'Could not send message.';
        $stmt->close();
      }
    }
  }
}

$cust_org_msg = "SELECT tbl_org_cust_msg.cust_msg_id , tbl_organizer.user_name, tbl_org_cust_msg.org_msg 
                  FROM tbl_org_cust_msg
                  INNER JOIN tbl_organizer ON tbl_organizer.org_id = tbl_org_cust_msg.org_id
                  INNER JOIN tbl_customer ON tbl_customer.cust_id = tbl_org_cust_msg.cust_id WHERE tbl_customer.cust_id=$cid";
$done = $conn->query($cust_org_msg);


if (isset($_POST['reply_org'])) {
  $reply = $_POST['reply'];
  $cust_msg_id = $_POST['cust_msg_id'];

  $sql = "UPDATE tbl_org_cust_msg SET cust_msg='$reply' WHERE cust_msg_id = '$cust_msg_id' ";
  $res = $conn->query($sql);
}

// cust_admin_msg_id

$admin_msg = "SELECT tbl_cust_admin_msg.cust_admin_msg_id , tbl_cust_admin_msg.admin_msg 
                  FROM tbl_cust_admin_msg
                  INNER JOIN tbl_customer ON tbl_customer.cust_id = tbl_cust_admin_msg.cust_id WHERE tbl_customer.cust_id=$cid";
$done1 = $conn->query($admin_msg);


if (isset($_POST['reply_cust'])) {
  $reply = $_POST['reply'];
  $cust_admin_msg_id = $_POST['cust_admin_msg_id'];

  $sql = "UPDATE tbl_cust_admin_msg SET cust_msg='$reply' WHERE cust_admin_msg_id  = '$cust_admin_msg_id' ";
  $res1 = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Users/Profile</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <!-- <link href="others/assets/img/favicon.png" rel="icon">
  <link href="others/assets/img/apple-touch-icon.png" rel="apple-touch-icon"> -->

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="others/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="others/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="others/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="others/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="others/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="others/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="others/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="others/assets/css/style.css" rel="stylesheet">

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
  <link href="css/customer-portal-v2.css" rel="stylesheet">
  <style>
    /* Profile tab stability */
    .customer-profile-main .tab-content {
      overflow-anchor: none;
    }

    #profile-edit {
      min-width: 0;
    }

    #profile-edit form {
      width: 100%;
      max-width: 920px;
    }

    #profile-edit .row {
      margin-left: 0;
      margin-right: 0;
    }

    #profile-edit .row>* {
      min-width: 0;
    }

    #profile-edit img {
      width: 110px;
      height: 110px;
      object-fit: cover;
      display: block;
      border-radius: 50%;
    }

    #profile-edit input[type="file"] {
      width: 100%;
      max-width: 420px;
    }

    @media (min-width: 992px) {
      html {
        overflow-y: scroll;
      }
    }

    /* Mobile Report tab stability */
    @media (max-width: 767px) {
      #report {
        width: 100%;
        min-width: 0;
        overflow: hidden;
        overflow-anchor: none;
      }

      #report .row,
      #report [class*="col-"] {
        min-width: 0;
        max-width: 100%;
      }

      #report .table-responsive,
      #report .table-wrap {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior-x: contain;
      }

      #report table {
        width: max-content;
        min-width: 100%;
        margin-bottom: 0;
      }

      #report img,
      #report iframe,
      #report object {
        max-width: 100%;
      }

      #report .btn,
      #report input,
      #report select {
        max-width: 100%;
      }

      .customer-profile-main .tab-content {
        min-width: 0;
        overflow-x: hidden;
      }
    }


    /* Mobile tab transition fix: prevents Report pane vertical shake */
    @media (max-width: 767px) {
      .customer-profile-main .tab-content>.tab-pane {
        transition: none !important;
        animation: none !important;
      }

      .customer-profile-main .tab-content>.fade {
        transition: none !important;
      }

      .customer-profile-main .tab-content>.tab-pane:not(.active) {
        display: none !important;
      }

      .customer-profile-main .tab-content>.tab-pane.active {
        display: block !important;
        opacity: 1 !important;
      }

      #report form {
        width: 100%;
        margin: 0;
      }

      #report .row {
        --bs-gutter-x: 0;
        margin-left: 0 !important;
        margin-right: 0 !important;
      }

      #report .col-form-label {
        padding-left: 0;
        padding-right: 0;
      }

      #report .form-control,
      #report textarea,
      #report select {
        width: 100%;
        box-sizing: border-box;
      }
    }
  </style>
  <link rel="stylesheet" href="css/customer-navbar-logo.css?v=20260728-sticky2">
  <link rel="stylesheet" href="css/customer-navbar-dropdown-final.css">
</head>
<style>
  #fbsubmit {
    position: relative;
    left: 250px;
  }

  /* Dashboard profile details */
  #profile-overview {
    max-width: 900px;
  }

  .dashboard-profile-head {
    margin-bottom: 10px;
    padding-bottom: 16px;
    border-bottom: 2px solid #eadcae;
  }

  .dashboard-profile-head span {
    display: block;
    margin-bottom: 4px;
    color: #a77b18;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .14em;
  }

  .dashboard-profile-head .card-title {
    margin: 0 0 3px !important;
    padding: 0 !important;
    font-size: 22px;
    color: #241e14 !important;
  }

  .dashboard-profile-head p {
    margin: 0;
    color: #807667;
    font-size: 14px;
  }

  #profile-overview .dashboard-detail-row {
    margin: 0 !important;
    padding: 15px 4px;
    align-items: center;
    border-bottom: 1px solid #e9e1d2;
  }

  #profile-overview .dashboard-detail-row:last-child {
    border-bottom: 0;
  }

  #profile-overview .dashboard-detail-row .label {
    color: #80601c !important;
    font-weight: 700;
  }

  #profile-overview .dashboard-detail-row>div:last-child {
    color: #2f2a22;
    font-weight: 500;
    overflow-wrap: anywhere;
  }

  #profile-overview .dashboard-detail-row:hover {
    background: rgba(212, 175, 55, .055);
  }

  @media (max-width: 767px) {
    #profile-overview .dashboard-detail-row {
      padding: 12px 2px;
      gap: 4px;
    }
  }
</style>

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
                <!-- <li><a href="login.html">Sign In</a></li> -->
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



    <section class="section profile">
      <div class="row">

        <div class="col-xl-3 customer-profile-side">
          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <img src="<?php echo $base64Image ?>" alt="Add Profile" class="rounded-circle">
              <h2><?php echo $user['user_name']; ?></h2>
              <h6><?php echo $user['email']; ?></h6>
              <div class="social-links mt-2">
                <a href="<?php echo $user['twitter_profile']; ?>" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="<?php echo $user['facebook_profile']; ?>" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="<?php echo $user['insta_profile']; ?>" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="<?php echo $user['linkedin_profile']; ?>" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
              <form action='' method='post'>
                <button class="" name='logout'>Logout</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-9 customer-profile-main">
          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered customer-account-nav">
                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">
                    Dashboard
                  </button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">
                    Edit Profile
                  </button>
                </li>

                <!-- <li class="nav-item">
                  <button
                    class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#profile-settings"
                  >
                    Settings
                  </button>
                </li> -->

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">
                    Change Password
                  </button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#notification">
                    Messages
                  </button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#report">
                    Report
                  </button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#your-events">
                    My Events
                  </button>
                </li>
              </ul>

              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                  <?php
                  if ($profile->num_rows > 0) {
                    while ($dis_pro = $profile->fetch_assoc()) {


                      // echo"<div class='tab-pane fade show active profile-overview' id='profile-overview'>";
                      //  echo"<h5 class='card-title'>About</h5>";
                      //  echo"<p class="small fst-italic">Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</p>";
                  
                      echo "<div class='dashboard-profile-head'><span>ACCOUNT OVERVIEW</span><h5 class='card-title'>Profile Details</h5><p>Your personal and account information.</p></div>";

                      echo "<div class='row dashboard-detail-row'>";
                      echo "<div class='col-lg-3 col-md-4 label'>User Name</div>";
                      echo "<div class='col-lg-9 col-md-8'>$dis_pro[user_name]</div>";
                      echo "</div>";

                      echo "<div class='row dashboard-detail-row'>";
                      echo "<div class='col-lg-3 col-md-4 label'>Email</div>";
                      echo "<div class='col-lg-9 col-md-8'>$dis_pro[email]</div>";
                      echo "</div>";

                      echo "<div class='row dashboard-detail-row'>";
                      echo "<div class='col-lg-3 col-md-4 label'>Phone</div>";
                      echo "<div class='col-lg-9 col-md-8'>$dis_pro[contact]</div>";
                      echo " </div>";

                      echo "<div class='row dashboard-detail-row'>";
                      echo " <div class='col-lg-3 col-md-4 label'>Gender</div>";
                      echo "<div class='col-lg-9 col-md-8'>$dis_pro[gender]</div>";
                      echo "</div>";
                      echo "<div class='row dashboard-detail-row'>";
                      echo "<div class='col-lg-3 col-md-4 label'>Address</div>";
                      echo "<div class='col-lg-9 col-md-8'>$dis_pro[address2]</div>";
                      echo "</div>";
                      echo "<div class='row dashboard-detail-row'>";
                      echo "<div class='col-lg-3 col-md-4 label'>City</div>";
                      echo "<div class='col-lg-9 col-md-8'>$dis_pro[city]</div>";
                      echo "</div>";
                      echo "<div class='row dashboard-detail-row'>";
                      echo "<div class='col-lg-3 col-md-4 label'>Pincode</div>";
                      echo "<div class='col-lg-9 col-md-8'>$dis_pro[pincode]</div>";
                      echo " </div>";
                      echo "<div class='row dashboard-detail-row'>";
                      echo "<div class='col-lg-3 col-md-4 label'>State</div>";
                      echo "<div class='col-lg-9 col-md-8'>$dis_pro[state]</div>";
                      echo "</div>";

                      echo "<div class='row dashboard-detail-row'>";
                      echo "<div class='col-lg-3 col-md-4 label'>Join Date</div>";
                      echo "<div class='col-lg-9 col-md-8'>$dis_pro[created_at]</div>";
                      echo "</div>";
                      // echo"</div>";
                  
                    }
                  }
                  ?>
                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                  <!-- Profile Edit Form -->
                  <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="<?php echo $base64Image ?>" alt="Profile Picture">
                        <div class="pt-2">
                          <!-- <a href="#" class="form-control form-control-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                              <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a> -->

                          <input type="file" name="profile_pic" id="imgupload" class="form-control form-control-sm"
                            title="Upload new profile image">

                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="uname" type="text" class="form-control" id="fullName"
                          value="<?php echo $user['user_name']; ?>">
                      </div>
                    </div>

                    <!-- <div class="row mb-3">
                          <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                          <div class="col-md-8 col-lg-9">
                            <textarea name="about" class="form-control" id="about" style="height: 100px">Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</textarea>
                          </div>
                        </div> -->

                    <div class="row mb-3">
                      <label for="company" class="col-md-4 col-lg-3 col-form-label">Contact</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="contact" type="text" class="form-control" id="company"
                          value="<?php echo $user['contact'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="text" class="form-control" id="Job"
                          value="<?php echo $user['email'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Country" class="col-md-4 col-lg-3 col-form-label">Gender</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="gender" type="text" class="form-control" id="Country"
                          value="<?php echo $user['gender'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="address" type="text" class="form-control" id="Address"
                          value="<?php echo $user['address2'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Phone" class="col-md-4 col-lg-3 col-form-label">City</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="city" type="text" class="form-control" id="Phone"
                          value="<?php echo $user['city'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">State</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="state" type="text" class="form-control" id="Email"
                          value="<?php echo $user['state'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Pincode</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="pincode" type="text" class="form-control" id="Email"
                          value="<?php echo $user['pincode'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="twitter" type="text" class="form-control" id="Twitter"
                          value="<?php echo $user['twitter_profile'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="facebook" type="text" class="form-control" id="Facebook"
                          value="<?php echo $user['facebook_profile'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="instagram" type="text" class="form-control" id="Instagram"
                          value="<?php echo $user['insta_profile'] ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="linkedin" type="text" class="form-control" id="Linkedin"
                          value="<?php echo $user['linkedin_profile'] ?>">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" name="edit_profile">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->
                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form action="" method="POST">

                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control" id="currentPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" name="change_pass">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->
                </div>


                <div class="tab-pane fade pt-3" id="notification">

                  <div class="customer-message-head">
                    <span class="eyebrow">CUSTOMER SUPPORT</span>
                    <h4>Messages</h4>
                    <p>Contact Plantastic Admin or an organizer from one of your bookings.</p>
                  </div>

                  <?php if (!empty($message_notice)): ?>
                    <div class="alert alert-info">
                      <?= htmlspecialchars($message_notice) ?>
                    </div>
                  <?php endif; ?>


                  <!-- =====================================================
       SEND MESSAGE
       ===================================================== -->

                  <div class="compose-grid mb-4">

                    <!-- ADMIN -->

                    <div class="compose-card">

                      <div class="compose-icon">
                        <i class="bi bi-shield-check"></i>
                      </div>

                      <h5>Message Admin</h5>

                      <p>
                        Account, payment, platform or general support.
                      </p>

                      <form method="post">

                        <textarea class="form-control" name="message" rows="4" placeholder="Write your message..."
                          required></textarea>

                        <button class="btn portal-primary mt-3" type="submit" name="send_admin_message">
                          <i class="bi bi-send"></i>
                          Send to Admin
                        </button>

                      </form>

                    </div>


                    <!-- ORGANIZER -->

                    <div class="compose-card">

                      <div class="compose-icon">
                        <i class="bi bi-calendar-event"></i>
                      </div>

                      <h5>Message Organizer</h5>

                      <p>
                        Select one of your booked events and its organizer.
                      </p>

                      <form method="post">

                        <select class="form-select mb-3" name="organizer_target" required>

                          <option value="">
                            Select booked event / organizer
                          </option>

                          <?php foreach ($organizerOptions as $option): ?>

                            <option value="<?= (int) $option['org_id'] ?>|<?= (int) $option['b_id'] ?>">
                              <?= htmlspecialchars(
                                $option['eve_type']
                                . ' — '
                                . ($option['company_name'] ?: $option['user_name'])
                              ) ?>
                            </option>

                          <?php endforeach; ?>

                        </select>


                        <textarea class="form-control" name="message" rows="4" placeholder="Write your message..."
                          required></textarea>


                        <button class="btn portal-primary mt-3" type="submit" name="send_org_message">

                          <i class="bi bi-send"></i>
                          Send to Organizer

                        </button>

                      </form>

                    </div>

                  </div>


                  <!-- =====================================================
       CONVERSATIONS
       ===================================================== -->

                  <h5 class="conversation-title">
                    Conversations
                  </h5>


                  <div class="accordion" id="customerMessageAccordion">


                    <!-- ===================================================
         ADMIN CONVERSATION
         =================================================== -->

                    <div class="accordion-item">

                      <h2 class="accordion-header" id="adminConversationHeading">

                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                          data-bs-target="#adminConversation" aria-expanded="false" aria-controls="adminConversation">

                          <i class="bi bi-shield-check me-2"></i>
                          Admin Conversation

                        </button>

                      </h2>


                      <div id="adminConversation" class="accordion-collapse collapse"
                        aria-labelledby="adminConversationHeading" data-bs-parent="#customerMessageAccordion">

                        <div class="accordion-body">


                          <?php if ($done1 && $done1->num_rows > 0): ?>


                            <?php while ($row = $done1->fetch_assoc()): ?>


                              <?php if (!empty($row['admin_msg'])): ?>

                                <div class="message-thread-item mb-3">

                                  <div class="fw-bold mb-1">
                                    Admin
                                  </div>

                                  <div>
                                    <?= nl2br(
                                      htmlspecialchars(
                                        $row['admin_msg']
                                      )
                                    ) ?>
                                  </div>


                                  <!-- REPLY -->

                                  <button type="button" class="btn btn-sm btn-outline-primary reply-toggle mt-2"
                                    data-target="adminReplyForm<?= (int) $row['cust_admin_msg_id'] ?>">
                                    Reply
                                  </button>


                                  <div id="adminReplyForm<?= (int) $row['cust_admin_msg_id'] ?>" class="reply-form mt-2"
                                    style="display:none">

                                    <form method="post">

                                      <input type="hidden" name="cust_admin_msg_id"
                                        value="<?= (int) $row['cust_admin_msg_id'] ?>">


                                      <textarea class="form-control mb-2" name="reply" rows="3"
                                        placeholder="Write your reply..." required></textarea>


                                      <div class="d-flex gap-2">

                                        <button class="btn btn-success btn-sm" type="submit" name="reply_cust">
                                          Send
                                        </button>


                                        <button class="btn btn-secondary btn-sm cancel-reply" type="button">
                                          Cancel
                                        </button>

                                      </div>

                                    </form>

                                  </div>

                                </div>

                              <?php endif; ?>


                            <?php endwhile; ?>


                          <?php else: ?>

                            <p class="text-muted mb-0">
                              No messages from Admin yet.
                            </p>

                          <?php endif; ?>


                        </div>

                      </div>

                    </div>



                    <!-- ===================================================
         ORGANIZER CONVERSATION
         =================================================== -->

                    <div class="accordion-item">

                      <h2 class="accordion-header" id="organizerConversationHeading">

                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                          data-bs-target="#organizerConversation" aria-expanded="false"
                          aria-controls="organizerConversation">

                          <i class="bi bi-calendar-event me-2"></i>
                          Organizer Conversations

                        </button>

                      </h2>


                      <div id="organizerConversation" class="accordion-collapse collapse"
                        aria-labelledby="organizerConversationHeading" data-bs-parent="#customerMessageAccordion">

                        <div class="accordion-body">


                          <?php if ($done && $done->num_rows > 0): ?>


                            <?php while ($row = $done->fetch_assoc()): ?>


                              <?php if (!empty($row['org_msg'])): ?>

                                <div class="message-thread-item mb-3">

                                  <div class="fw-bold mb-1">

                                    <?= htmlspecialchars(
                                      $row['user_name']
                                    ) ?>

                                  </div>


                                  <div>

                                    <?= nl2br(
                                      htmlspecialchars(
                                        $row['org_msg']
                                      )
                                    ) ?>

                                  </div>


                                  <!-- REPLY -->

                                  <button type="button" class="btn btn-sm btn-outline-primary reply-toggle mt-2"
                                    data-target="organizerReplyForm<?= (int) $row['cust_msg_id'] ?>">
                                    Reply
                                  </button>


                                  <div id="organizerReplyForm<?= (int) $row['cust_msg_id'] ?>" class="reply-form mt-2"
                                    style="display:none">

                                    <form method="post">

                                      <input type="hidden" name="cust_msg_id" value="<?= (int) $row['cust_msg_id'] ?>">


                                      <textarea class="form-control mb-2" name="reply" rows="3"
                                        placeholder="Write your reply..." required></textarea>


                                      <div class="d-flex gap-2">

                                        <button class="btn btn-success btn-sm" type="submit" name="reply_org">
                                          Send
                                        </button>


                                        <button class="btn btn-secondary btn-sm cancel-reply" type="button">
                                          Cancel
                                        </button>

                                      </div>

                                    </form>

                                  </div>

                                </div>

                              <?php endif; ?>


                            <?php endwhile; ?>


                          <?php else: ?>

                            <p class="text-muted mb-0">
                              No organizer messages yet.
                            </p>

                          <?php endif; ?>


                        </div>

                      </div>

                    </div>


                  </div>

                </div>
                <!-- End Notification -->

                <div class="tab-pane fade pt-3" id="report">
                  <!-- start report-->
                  <form method="post">
                    <!-- Organizer Name -->
                    <div class="row mb-3">
                      <label for="organizerName" class="col-md-4 col-lg-3 col-form-label">Organizer Name</label>
                      <div class="col-md-8 col-lg-9">
                        <select class="form-control" id="organizerName" name="organizerName" required>
                          <option value="">Select Organizer Name</option>
                          <?php foreach ($organizerOptions as $row): ?>
                            <option value="<?php echo $row['org_id'] . '|' . $row['b_id']; ?>">
                              <?php echo htmlspecialchars($row['user_name']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <!-- Bad Behavior Checkboxes -->
                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">Issues Observed</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="lateStart" name="issues[]"
                            value="Event started late" />
                          <label class="form-check-label" for="lateStart">Event started late</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="mismanagement" name="issues[]"
                            value="Mismanagement" />
                          <label class="form-check-label" for="mismanagement">Mismanagement</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="rudeStaff" name="issues[]"
                            value="Rude staff" />
                          <label class="form-check-label" for="rudeStaff">Rude staff</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="unsanitary" name="issues[]"
                            value="Unsanitary conditions" />
                          <label class="form-check-label" for="unsanitary">Unsanitary conditions</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="otherIssue" name="issues[]"
                            value="Other" />
                          <label class="form-check-label" for="otherIssue">Other</label>
                        </div>
                      </div>
                    </div>

                    <!-- Additional Comments -->
                    <div class="row mb-3">
                      <label for="reportDetails" class="col-md-4 col-lg-3 col-form-label">Additional Comments</label>
                      <div class="col-md-8 col-lg-9">
                        <textarea class="form-control" id="reportDetails" name="reportDetails" rows="4"
                          placeholder="Describe the issue in detail..."></textarea>
                      </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                      <button type="submit" class="btn btn-danger" name="report">
                        Submit Report
                      </button>
                    </div>
                  </form>

                  <!-- End Report -->
                </div>



                <!-- Wrapper for multiple events -->
                <div class="tab-pane fade pt-3" id="your-events">

                  <div class="card mb-3">
                    <?php if ($events->num_rows > 0) { ?>
                      <?php while ($img = $events->fetch_assoc()) {
                        // Profile picture logic per event
                        $imageData = $img['profile_pic'];

                        if (!empty($imageData)) {
                          $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);
                        } else {
                          $base64Image = 'assets/images/default.jpg'; // change to your actual default path
                        }
                        ?>

                        <div class="row g-0 align-items-center p-3">
                          <!-- Profile Photo -->
                          <div class="col-auto">
                            <img src="<?php echo $base64Image ?>" class="rounded-circle" width="64" height="64"
                              alt="Organizer" />
                          </div>

                          <!-- Organizer Info -->
                          <div class="col">
                            <h5 class="mb-1"><?php echo $img['user_name'] ?></h5>
                            <p class="mb-0 TEXT-CENTER">Event Date: <?php echo $img['booking_date'] ?></p>
                            <p class="mb-0 text-muted">Event Type: <?php echo $img['eve_type'] ?></p>
                          </div>

                          <!-- Buttons -->

                          <div class="col-auto">
                            <form action="" method="post">
                              <button class="btn btn-success me-2" name="Receipt">Receipt</button>
                              <input type="hidden" name="bid" id="" value="<?php echo $img['b_id'] ?>">
                              <!-- <input type="button" name="Receipt" id="" value="Receipt"> -->
                              <!-- <button class="btn btn-primary" onclick="toggleMessage(this)">
                        Message
                      </button> -->
                            </form>
                          </div>
                        </div>

                        <!-- Message Section -->
                      <?php } ?>
                    <?php } ?>
                  </div>

                </div>




              </div><!-- End Tab Content -->
            </div><!-- End Card Body -->
          </div><!-- End Card -->
        </div><!-- End Column -->
      </div><!-- End Row -->
    </section>

    </main><!-- End #main -->
  </div>
  <!-- ======= Footer ======= -->
  <!-- <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      All the links in the footer should remain intact.
      You can delete the links only if you purchased the pro version.
      Licensing information: https://bootstrapmade.com/license/
      Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer>End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="Others/"></script>
  <script src="Others/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="Others/assets/vendor/chart.js/chart.umd.js"></script>
  <script src="Others/assets/vendor/echarts/echarts.min.js"></script>
  <script src="Others/assets/vendor/quill/quill.js"></script>
  <script src="Others/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="Others/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="Others/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="others/assets/js/main.js"></script>

  <script>
    // Toggle specific reply form
    document.querySelectorAll(".reply-toggle").forEach((btn) => {
      btn.addEventListener("click", function () {
        const targetId = this.getAttribute("data-target");

        // Hide all open reply forms before opening new one
        document
          .querySelectorAll(".reply-form")
          .forEach((form) => (form.style.display = "none"));

        // Show selected reply form
        const targetForm = document.getElementById(targetId);
        if (targetForm) targetForm.style.display = "block";
      });
    });

    // Cancel button inside reply form
    document.querySelectorAll(".cancel-reply").forEach((btn) => {
      btn.addEventListener("click", function () {
        this.closest(".reply-form").style.display = "none";
      });
    });
  </script>

  <!-- report other textarea js -->
  <!-- JavaScript to toggle textarea -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const otherCheckbox = document.getElementById("otherIssue");
      const textarea = document.getElementById("reportDetails");

      otherCheckbox.addEventListener("change", function () {
        textarea.disabled = !this.checked;
      });
    });
  </script>


  <!-- Other Template js -->

  <div class="scroll-to-top scroll-to-target" data-target="html">
    <span class="fa fa-angle-up"></span>
  </div>

  <script src="js/jquery.js"></script>
  <script src="js/appear.js"></script>
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
  <script src="js/customer-navbar-dropdown-final.js?v=20260728-sticky2"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {

      document.querySelectorAll('.reply-toggle').forEach(function (button) {

        button.addEventListener('click', function () {

          const targetId = this.getAttribute('data-target');
          const form = document.getElementById(targetId);

          if (!form) return;

          document.querySelectorAll('.reply-form').forEach(function (other) {

            if (other !== form) {
              other.style.display = 'none';
            }

          });

          form.style.display =
            form.style.display === 'block'
              ? 'none'
              : 'block';

        });

      });


      document.querySelectorAll('.cancel-reply').forEach(function (button) {

        button.addEventListener('click', function () {

          const form = this.closest('.reply-form');

          if (form) {
            form.style.display = 'none';
          }

        });

      });

    });
  </script>

</body>
<script>
  function toggleMessage(button) {
    const card = button.closest(".card");
    const section = card.querySelector(".message-section");
    section.classList.toggle("d-none");
  }

  function showTab(btn, tabName) {
    const section = btn.closest(".message-section");

    // Toggle nav-link active
    section
      .querySelectorAll(".nav-link")
      .forEach((link) => link.classList.remove("active"));
    btn.classList.add("active");

    // Show/hide tab content
    section
      .querySelector(".send-tab")
      .classList.toggle("d-none", tabName !== "send");
    section
      .querySelector(".notification-tab")
      .classList.toggle("d-none", tabName !== "notification");
  }
</script>

<script>
  function sendReply(receiverName) {
    alert("Reply sent to " + receiverName + "!");
    // You can replace this with your actual backend call using fetch() or AJAX
  }
</script>
<script>
  function closeCollapse(id) {
    const el = document.getElementById(id);
    const bsCollapse = new bootstrap.Collapse(el, {
      toggle: false, // prevent it from auto opening
    });
    bsCollapse.hide();
  }
</script>

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

</html>