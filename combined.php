<?php
include_once 'dbconnect.php';
$error = false;

if (isset($_POST['sign'])) {
  $name = trim($_POST['name']);
  $name = strip_tags($name);
  $name = htmlspecialchars($name);

  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);

  $phone = trim($_POST['phone']);
  $phone = strip_tags($phone);
  $phone = htmlspecialchars($phone);

  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);

  $usr = trim($_POST['usr']);
  $usr = strip_tags($usr);
  $usr = htmlspecialchars($usr);

  if (empty($pass)) {
    $error = true;
    $errMSG = "Please Enter a Password";
  }
  if (empty($usr)) {
    $error = true;
    $errMSG = "Please Enter a Username";
  }

  if ((strlen($pass) < 6) && ($error == false)) {
    $error = true;
    $errMSG = "Password Must have atleast 6 Characters";
  }


  $password = hash('sha512', $pass);

  $query = "SELECT email FROM chat_users WHERE email='$email'";
  $result = mysqli_query($conn, $query);
  $count = mysqli_num_rows($result);
  if ($count != 0) {
    $error = true;
    $errMSG = "Provided Email already Exists";
  }


  $query = "SELECT username FROM login WHERE username='$usr'";
  $result = mysqli_query($conn, $query);
  $count = mysqli_num_rows($result);
  if ($count != 0) {
    $error = true;
    $errMSG = "Provided Username already Exists";
  }


  if (!$error) {
    try {
      $query = "INSERT INTO `chat_users` (`name`, `email`, `phone`) VALUES('$name','$email','$phone')";
      $query2 = "INSERT INTO `login` (`username`, `password`, `email`) VALUES ('$usr', '$password', '$email')";

      $res = mysqli_query($conn, $query);
      $res1 = mysqli_query($conn, $query2);

      if ($res && $res1) {
        $success = "Account Created Successfully";
      } else {
        $errMSG = "Something went Wrong, Try Again Later";
      }
    } catch (Exception $e) {
      $errMSG = "An error occurred: " . $e->getMessage();
    }
  }
}

if (isset($_POST['log'])) {
  $error = false;

  $usrid = trim($_POST['usrid']);
  $usrid = strip_tags($usrid);
  $usrid = htmlspecialchars($usrid);

  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);

  $roomid = $_POST['roomid'];

  if (empty($pass)) {
    $error = true;
    $errMSG1 = "Please Enter your Password";
  } else {
    $pass = hash('sha512', $pass);
  }
  if (empty($usrid)) {
    $error = true;
    $errMSG1 = "Please Enter your Username";
  }
  if (!$error) {
    try {
      $res = mysqli_query($conn, "SELECT username, password, email FROM login WHERE username='$usrid'");
      $row = mysqli_fetch_array($res);
      $count = mysqli_num_rows($res);
      if ($count == 1 && $row['password'] == $pass) {
        $_SESSION['chat'] = $row['email'];

        if ($roomid == 3000) {
          header("Location: http://localhost:3000?user=$usrid");
        } elseif ($roomid == 3001) {
          header("Location: http://localhost:3001?user=$usrid");
        } elseif ($roomid == 3002) {
          header("Location: http://localhost:3002?user=$usrid");
        } elseif ($roomid == 3003) {
          header("Location: http://localhost:3003?user=$usrid");
        } elseif ($roomid == 3004) {
          header("Location: http://localhost:3004?user=$usrid");
        } else {
          $errMSG1 = "Incorrect room ID, Try Again!";
        }
      } else {
        $errMSG1 = "Incorrect Credentials, Try Again ☹️";
        echo "<script>alert('$errMSG1')</script>";
        header("Location: http://localhost/CryptChat/public/combined.php");
      }
    } catch (Exception $e) {
      $errMSG = "An error occurred: " . $e->getMessage();
    }
  }
}

if (isset($_POST['sendopt'])) {

  $numbers = ($_POST['phone']);
  $name = ($_POST['name']);
  $email = ($_POST['email']);

  $otp = mt_rand(10000, 99999);
  $no = ($_POST['phone']);


  if (preg_match("/^\d+\.?\d*$/", $no) && strlen($no) == 10) {

    $fields = array(
      "variables_values" => "$otp",
      "route" => "otp",
      "numbers" => "$numbers",
    );

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($fields),
      CURLOPT_HTTPHEADER => array(
        "authorization: yp2CxjSA9h6sw0rEqHoRJ4MNc5dGegTBPD3XbZtfiV1U78uQWvmYXiCcPArhq5fuBwosjEWdNn13IkQv",
        "accept: */*",
        "cache-control: no-cache",
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "CURL Error #:" . $err;
    } else {
      $data = json_decode($response);
      $sts = $data->return;
      if ($sts == false) {
        $err = "OTP Not Sent";
      } else {
        setcookie('verotp', $otp);
        $ses = "Your OTP is Sent";
      }
    }
  } else {
    $err = "Invalid Mobile Number";
  }
}



if (isset($_POST['verifyotp'])) {
  $otp = $_POST['otp'];
  $numbers = ($_POST['phone']);
  $name = ($_POST['name']);
  $email = ($_POST['email']);
  if ($_COOKIE['verotp'] == $otp) {

    $verif = "Congratulation, Your Mobile is Verified";
  } else {
    $notverif = "Please Enter Correct OTP";
  }
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="..\CSS\newlogin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css">
    <title>Web Lounge</title>
</head>

<body>
    <div class="loginContent">
        <div class="nameWebLounge">
            <!-- <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                <path d="M0 0h24v24H0z" fill="none" />
                <path id="machine1" d="M9.17 16.83c1.56 1.56 4.1 1.56 5.66 0 1.56-1.56 1.56-4.1 0-5.66l-5.66 5.66zM18 2.01L6 2c-1.11 0-2 .89-2 2v16c0 1.11.89 2 2 2h12c1.11 0 2-.89 2-2V4c0-1.11-.89-1.99-2-1.99zM10 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM7 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm5 16c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z" />
            </svg> -->

            <span>Web Lounge</span>
        </div>
        <div class="leftWelcome">
            <h2>Welcome Back</h2>
            <p>To keep connected with us please login with your personal info.</p>
            <label for="changePage">Sign In</label>
        </div>
        <div class="rightWelcome">
            <h2>Create a New Account!</h2>
            <p>Enter your personal details and start your journey with us.</p>
            <label for="changePage">Sign Up</label>
        </div>
        <form class="loginPage" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
            <h1>Log In</h1>
            <div class="group">
                <i class="fas fa-envelope"></i>
                <input type="text" id="userID" name="usrid" placeholder="Enter Username">
                <!--     -->
            </div>
            <div class="group">
                <label class="fas fa-key" for="password"></label>
                <input type="password" name="pass" placeholder="Enter Password" />
            </div>
            <div class="group">
                <i class="fas fa-envelope"></i>
                <input type="text" id="roomID" name="roomid" placeholder="Enter Room">
            </div>
            <div class="group">
                <button name="log" id="login" type="submit">Login</button>
            </div>
            <?php if (isset($errMSG1)) { ?>
            <span style="color: red; font-family: 'IBM Plex Sans', sans-serif;"><?php echo $errMSG1; ?></span><br><br>
            <?php } ?>
        </form>
        <form class="registerPage" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h1>Create Account</h1>
            <div class="group">
                <label class="fas fa-id-badge" for="name"></label>
                <input type="text" id="reg" name="name" placeholder="Enter Name"
                    value="<?php echo (isset($name) ? htmlspecialchars($name) : ''); ?>" />
            </div>

            <div class="group">
                <label class="far fa-envelope" for="email"></label>
                <input type="email" id="email" name="email" placeholder="Enter Email"
                    value="<?php echo (isset($email) ? htmlspecialchars($email) : ''); ?>" />
            </div>

            <div class="group">
                <label class="fa fa-phone" for="phone"></label>
                <input type="phone" id="phone" name="phone" pattern="[0-9]{10}" placeholder="Phone Number"
                    value="<?php echo (isset($numbers) ? htmlspecialchars($numbers) : ''); ?>">
            </div>

            <div class="group">
                <button type="submit" id="btn-sendotp" formnovalidate name="sendopt"
                    class="btn btn-lg btn-success btn-block send-btn">Send OTP</button>
            </div>

            <div class="group">
                <label class="far fa-envelope" for="otp"></label>
                <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP" maxlength="5">
            </div>
            <div class="group">
                <button id="btn-verify" type="submit" name="verifyotp">Verify</button>
            </div>
            <?php if (!isset($verif) && !isset($notverif)) { ?>
            <?php } ?>
            <?php if (isset($verif)) { ?>
            <span style="color:red; font-family: 'Ubuntu', sans-serif;"><?php echo $verif; ?></span><br><br>
            <?php } ?>

            <?php if (isset($notverif)) { ?>
            <span style="color:red; font-family: 'Ubuntu', sans-serif;"><?php echo $notverif; ?></span><br><br>
            <?php } ?>
            <?php if (!isset($verif) && !isset($notverif)) { ?>
            <br>
            <?php } ?>

            <div class="group">
                <label class="fas fa-key" for="usr"></label>
                <input type="text" name="usr" placeholder="Enter Username" />
            </div>
            <div class="group">
                <label class="fas fa-key" for="password"></label>
                <input type="password" name="pass" placeholder="Enter Password" />
            </div>

            <button id="login" name="sign" type="submit">Register</button>
            <?php if (isset($errMSG)) { ?>
            <span style="color:red; font-family: 'Ubuntu', sans-serif;"><?php echo $errMSG; ?></span><br><br>
            <?php } ?>
            <?php if (isset($success)) { ?>
            <span style="color: red; font-family: 'Ubuntu', sans-serif;"><?php echo $success; ?></span><br><br>
            <?php } ?>

            <?php if (isset($err)) { ?>
            <span style="color:red; font-family: 'Ubuntu', sans-serif;"><?php echo $err; ?></span><br><br>
            <?php
      } ?>
        </form>
        <div class="cover">
            <button id="changePage" name="changePage"></button>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.1.0/lib/anime.js"></script>

    <script src="..\JAVASCRIPT\main.js"></script>
</body>

</html>