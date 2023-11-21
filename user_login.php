<?php
session_start();
include_once 'dbconnect.php';
$error = false;

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
    echo "YES";
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
?>
<!--<html>

    <head>
        <title>Crypt Chat &nbsp;&nbsp;💬</title>
        <link rel="stylesheet" type="text/css" href="..\css\login.css?v=1.2">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome/4.7.0/css/font-awesome.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap" rel="stylesheet">
        <script src="http://code.jquery.com/jquery-latest.min.js"></script>
         <link rel="stylesheet" type="text/css" href="..\css\header.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="..\css\footer.css"> -->
<!-- <script src="..\javascript\home.js"></script> -->
<!--</head>

     <body>

        <div class="main-container">
            <div class="inner-container-join">
                <h1 class="app-name">Web&nbspLounge</h1>
                <h3 class="app-label">Login</h3>
                <div class="h-line"></div>
                <br><br>

                <div class="joining-form">
                    <center>
                        <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>">

                            <input type="text" name="usrid" placeholder="Enter Username" id="userID">
                            &nbsp;&nbsp;&nbsp;
                            <input type="password" name="pass" placeholder="Enter Password">
                            <br><br>
                            <input type="text" name="roomid" placeholder="Enter Room" id="roomID">
                            <br>
                            <br>
                            <br>
                            <input type="submit" name="log" class="send-btn join-btn" style="background-color: #0e0007; border: none; width: 130px" value="Sign In">

                            <p><pre style="font-family: 'IBM Plex Sans', sans-serif; font-size: 1.2rem; color: white;">Don't have an account?    <a href="signup.php" style="text-decoration: none; color: #0e0007;">Sign up here</a></pre></p>

                            <?php if (isset($errMSG1)) { ?>
                            <span style="color: red; font-family: 'IBM Plex Sans', sans-serif;"><?php echo $errMSG1; ?></span><br><br>
                            <?php } ?>

                        </form>

                    </center>
                </div>
            </div>
        </div>


        <br>
    </body>

    </html>