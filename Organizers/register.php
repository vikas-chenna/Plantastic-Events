<?php

require_once __DIR__ . '/../includes/bootstrap.php';


/* =========================================================
   ALREADY LOGGED IN
   ========================================================= */

if (!empty($_SESSION['organizer'])) {

  redirect('index.php');

}


/* =========================================================
   DEFAULT VALUES
   ========================================================= */

$error = '';
$success = '';

$form = [

  'uname' => '',
  'mobile' => '',
  'email' => '',
  'gender' => 'male',
  'address' => '',
  'city' => '',
  'state' => '',
  'pincode' => '',
  'country' => 'India',
  'cname' => '',
  'establish' => '',
  'experience' => ''

];


/* =========================================================
   REGISTRATION
   ========================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {


  /* -------------------------
     CSRF
     ------------------------- */

  csrf_verify();


  /* -------------------------
     FORM VALUES
     ------------------------- */

  foreach ($form as $key => $_) {

    if (isset($_POST[$key])) {

      $form[$key] = post_string($key, 250);

    }

  }


  $password = (string) ($_POST['password'] ?? '');
  $confirm = (string) ($_POST['cpassword'] ?? '');


  /* -------------------------
     VALIDATION
     ------------------------- */

  if (
    $form['uname'] === '' ||
    $form['email'] === '' ||
    $form['mobile'] === '' ||
    $password === ''
  ) {

    $error = 'Please fill all required fields.';

  } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {

    $error = 'Enter a valid email address.';

  } elseif (strlen($password) < 6) {

    $error = 'Password must be at least 6 characters.';

  } elseif ($password !== $confirm) {

    $error = 'Password and confirm password do not match.';

  } else {


    /* =================================================
       CHECK DUPLICATE EMAIL
       ================================================= */

    $check = $conn->prepare(
      'SELECT org_id
             FROM tbl_organizer
             WHERE email = ?
             LIMIT 1'
    );


    if (!$check) {

      $error = 'Unable to process registration. Please try again.';

    } else {

      $check->bind_param(
        's',
        $form['email']
      );

      $check->execute();

      $result = $check->get_result();

      $exists = $result && $result->num_rows > 0;

      $check->close();


      if ($exists) {

        $error = 'An organizer with this email already exists.';

      } else {


        /* =========================================
           PREPARE ORGANIZER DATA
           ========================================= */

        $hash = ems_hash_password($password);

        $code = random_token(16);

        $current_date = date('Y-m-d');


        /*
         * Organizer starts as:
         *
         * v_status = 0
         * approve  = ''
         *
         * Flow:
         *
         * Register
         * → Email verification
         * → v_status = 1
         * → Admin approval
         */

        $status = '';
        $approve = '';
        $block = '';
        $empty = '';
        $v_status = '0';


        $name = $form['uname'];
        $phone = $form['mobile'];
        $email = $form['email'];
        $gender = $form['gender'];
        $cname = $form['cname'];
        $city = $form['city'];
        $pincode = $form['pincode'];
        $state = $form['state'];
        $country = $form['country'];
        $establish = $form['establish'];
        $experience = $form['experience'];
        $address = $form['address'];


        /* =========================================
           INSERT ORGANIZER
           ========================================= */

        $sql = '

                    INSERT INTO tbl_organizer

                    (
                        user_name,
                        mobile_no,
                        email,
                        gender,
                        company_name,
                        city,
                        pincode,
                        state,
                        country,
                        since_establish,
                        experience,
                        address,
                        password,
                        created_at,
                        status,
                        approve,
                        block,
                        verification_code,
                        v_status,
                        profile_pic,
                        insta_profile,
                        twitter_profile,
                        facebook_profile,
                        linkedin_profile
                    )

                    VALUES
                    (
                        ?,?,?,?,?,?,?,?,?,?,
                        ?,?,?,?,?,?,?,?,?,
                        NULL,?,?,?,?
                    )

                ';


        $stmt = $conn->prepare($sql);


        if (!$stmt) {

          $error = 'Registration failed. Please try again.';

        } else {


          $stmt->bind_param(

            'sssssssssssssssssssssss',

            $name,
            $phone,
            $email,
            $gender,
            $cname,
            $city,
            $pincode,
            $state,
            $country,
            $establish,
            $experience,
            $address,
            $hash,
            $current_date,
            $status,
            $approve,
            $block,
            $code,
            $v_status,
            $empty,
            $empty,
            $empty,
            $empty

          );


          /* =====================================
             INSERT SUCCESS
             ===================================== */

          if ($stmt->execute()) {


            /* ---------------------------------
               VERIFICATION URL
               --------------------------------- */

            $verifyUrl = app_url(

              'org_verify.php?code=' .
              urlencode($code)

            );


            /* ---------------------------------
               VERIFICATION EMAIL
               --------------------------------- */

            $body =

              '<h2>Verify your organizer email</h2>'

              . '<p>Hi '
              . e($name)
              . ',</p>'

              . '<p>Thank you for registering with Plantastic Events.</p>'

              . '<p>Please verify your email address using the link below:</p>'

              . '<p>'

              . '<a href="'
              . e($verifyUrl)
              . '">Verify Organizer Email</a>'

              . '</p>'

              . '<p>After email verification, your account will be sent for admin approval.</p>'

              . '<p>You can login after the administrator approves your account.</p>';


            [$sent, $msg] = ems_send_mail(

              $email,

              'Organizer Email Verification',

              $body

            );


            /* ---------------------------------
               RESULT
               --------------------------------- */

            if ($sent) {

              $success =
                'Registration successful. '
                . 'Please check your email and verify your account. '
                . 'After verification, wait for admin approval.';

            } else {

              /*
               * Account is already inserted.
               * Don't show "registration failed".
               *
               * On localhost/debug mode we keep
               * verification URL available.
               */

              $success =
                'Registration successful, but the verification email could not be sent. '
                . $msg
                . ' Verification link: '
                . $verifyUrl;

            }


          } else {

            $error =
              'Registration failed. Please try again.';

          }


          $stmt->close();

        }

      }

    }

  }

}

?>

<!doctype html>

<html lang="en">


<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>
    Organizer Register | Plantastic Events
  </title>


  <link rel="stylesheet" href="assets/css/org-theme.css">


  <script>

    (function () {

      try {

        var theme =
          localStorage.getItem(
            'ems_org_theme'
          );

        if (theme === 'dark') {

          document.documentElement
            .classList
            .add('dark');

        }

      } catch (e) { }

    })();

  </script>


  <style>
    body.org-login {

      min-height: 100vh;

      margin: 0;

      display: grid;

      place-items: center;

      padding: 24px 12px;

      font-family: var(--font);

      color: var(--text);

      background:

        radial-gradient(900px 500px at 10% -10%,
          rgba(139, 92, 246, .18),
          transparent 60%),

        radial-gradient(800px 420px at 100% 0%,
          rgba(236, 72, 153, .13),
          transparent 55%),

        var(--bg);

    }


    .wrap {

      width: min(720px, 100%);

    }


    .cardx {

      background: var(--bg-elevated);

      border: 1px solid var(--border);

      border-radius: 22px;

      box-shadow: var(--shadow);

      padding: 28px;

    }
  </style>

</head>


<body class="org-login">


  <div class="wrap">


    <div class="cardx">


      <!-- ================================================
             HEADER
             ================================================ -->

      <div style="
                display:flex;
                justify-content:space-between;
                gap:10px;
                align-items:start;
                margin-bottom:14px
            ">


        <div>


          <div style="
                        display:flex;
                        align-items:center;
                        gap:10px;
                        margin-bottom:8px
                    ">


            <div style="
                            width:42px;
                            height:42px;
                            border-radius:14px;
                            display:grid;
                            place-items:center;
                            background:
                                linear-gradient(
                                    135deg,
                                    #8b5cf6,
                                    #a855f7 55%,
                                    #ec4899
                                );
                            color:#fff;
                            font-weight:900
                        ">
              PE
            </div>


            <strong>
              Organizer registration
            </strong>


          </div>


          <div style="
                        color:var(--text-muted);
                        font-size:.92rem
                    ">
            Create your seller account.
            Email verification + admin approval required.
          </div>


        </div>


        <button class="icon-btn" type="button" data-theme-toggle>
          ◐
        </button>


      </div>


      <!-- ================================================
             ERROR
             ================================================ -->

      <?php if ($error): ?>

        <div class="alert err" style="margin-bottom:12px">
          <?= e($error) ?>
        </div>

      <?php endif; ?>


      <!-- ================================================
             SUCCESS
             ================================================ -->

      <?php if ($success): ?>

        <div class="alert ok" style="margin-bottom:12px">
          <?= e($success) ?>
        </div>

      <?php endif; ?>


      <!-- ================================================
             FORM
             ================================================ -->

      <?php if (!$success): ?>


        <form method="post" class="form-grid">


          <?= csrf_field() ?>


          <div class="form-row">


            <label class="field">

              Full name *

              <input type="text" name="uname" value="<?= e($form['uname']) ?>" required>

            </label>


            <label class="field">

              Company

              <input type="text" name="cname" value="<?= e($form['cname']) ?>">

            </label>


          </div>


          <div class="form-row">


            <label class="field">

              Email *

              <input type="email" name="email" value="<?= e($form['email']) ?>" required>

            </label>


            <label class="field">

              Mobile *

              <input type="text" name="mobile" value="<?= e($form['mobile']) ?>" required>

            </label>


          </div>


          <div class="form-row">


            <label class="field">

              Gender

              <select name="gender">

                <?php foreach (
                  ['male', 'female', 'other']
                  as $g
                ): ?>

                  <option value="<?= $g ?>" <?= $form['gender'] === $g
                      ? 'selected'
                      : '' ?>>
                    <?= ucfirst($g) ?>
                  </option>

                <?php endforeach; ?>

              </select>

            </label>


            <label class="field">

              Experience

              <input type="text" name="experience" value="<?= e($form['experience']) ?>" placeholder="e.g. 3 years">

            </label>


          </div>


          <label class="field">

            Since established

            <input type="text" name="establish" value="<?= e($form['establish']) ?>">

          </label>


          <label class="field">

            Address

            <input type="text" name="address" value="<?= e($form['address']) ?>">

          </label>


          <div class="form-row">


            <label class="field">

              City

              <input type="text" name="city" value="<?= e($form['city']) ?>">

            </label>


            <label class="field">

              State

              <input type="text" name="state" value="<?= e($form['state']) ?>">

            </label>


          </div>


          <div class="form-row">


            <label class="field">

              Pincode

              <input type="text" name="pincode" value="<?= e($form['pincode']) ?>">

            </label>


            <label class="field">

              Country

              <input type="text" name="country" value="<?= e($form['country']) ?>">

            </label>


          </div>


          <div class="form-row">


            <label class="field">

              Password *

              <input type="password" name="password" minlength="6" required>

            </label>


            <label class="field">

              Confirm password *

              <input type="password" name="cpassword" minlength="6" required>

            </label>


          </div>


          <button class="btn btn-primary btn-block" type="submit" name="submit" value="1">
            Create organizer account
          </button>


        </form>


      <?php endif; ?>


      <div style="
                margin-top:16px;
                text-align:center
            ">

        Already registered?

        <a href="login.php">

          <strong>
            Sign in
          </strong>

        </a>

      </div>


    </div>


  </div>


  <script src="assets/js/org-ui.js"></script>


</body>

</html>