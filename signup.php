<?php
include_once 'dbconnect.php';
$error = false;

if ( isset($_POST['sign']) ) {
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

  if(empty($pass)){
      $error = true;
      $errMSG = "Please Enter a Password";
    }
   if(empty($usr)){
      $error = true;
      $errMSG = "Please Enter a Username";
    } 

  if((strlen($pass) < 6) &&($error == false)) {
    $error = true;
    $errMSG = "Password Must have atleast 6 Characters";
  }
  

  $password = hash('sha512', $pass);

    $query = "SELECT email FROM chat_users WHERE email='$email'";
    $result = mysqli_query($conn,$query);
    $count = mysqli_num_rows($result);
    if($count!=0){
      $error = true;
      $errMSG = "Provided EMail already Exists";
    }

 
  $query = "SELECT username FROM login WHERE username='$usr'";
    $result = mysqli_query($conn,$query);
    $count = mysqli_num_rows($result);
    if($count!=0){
      $error = true;
      $errMSG = "Provided Username already Exists";
    }

  
if(!$error){
    $query = "INSERT INTO chat_users(name,email,phone) VALUES('$name','$email','$phone')";
    $query2 = "INSERT INTO login(username,password, email) VALUES('$usr', '$password', '$email')";
    $res = mysqli_query($conn,$query);
    $res1 = mysqli_query($conn,$query2);
    if(($res)&&($res1)) {
          $success = "Account Created Successfully";
    } else {
      $errMSG = "Something went Wrong, Try Again Later ☹️";
    }
} 
}

if(isset($_POST['sendopt'])) {



          $numbers = ($_POST['phone']);
          $name = ($_POST['name']);
          $email = ($_POST['email']);

          $otp = mt_rand(10000, 99999);
          $no = ($_POST['phone']);


          if(preg_match("/^\d+\.?\d*$/",$no) && strlen($no)==10){

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
          echo "cURL Error #:" . $err;
          } else {
          $data = json_decode($response);
          // $sts = $data->return;

          if (empty($data->return)) {
          // if ($sts == false) {
          $err = "OTP Not Send";
          }else{
           setcookie('verotp', $otp);
          $ses = "Your OTP is Send";
          }
          }
          
        }else{
          $err = "Invalid Mobile Number";
          }
          
          }
          


if(isset($_POST['verifyotp'])) { 
  $otp = $_POST['otp'];
  $numbers = ($_POST['phone']);
          $name = ($_POST['name']);
          $email = ($_POST['email']);
  if($_COOKIE['verotp'] == $otp) {
    
    $verif = "Congratulation, Your Mobile is Verified";
      
  } else {
    $notverif = "Please Enter Correct OTP";
  }
}

?>


    <!--<html>

    <head>
        <title>Web Lounge &nbsp;&nbsp;💬</title>
        <link rel="stylesheet" type="text/css" href="..\css\create_personnel.css?v=2.2">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="..\css\header.css">
        <link rel="stylesheet" type="text/css" href="..\css\footer.css"> -->
        <!--<link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap" rel="stylesheet">
        <script src="..\javascript\home.js"></script> -->
    <!--</head>

    <body>

        <div class="main-container">
            <div class="inner-container-join">
                <h1 class="app-name">Web&nbspLounge</h1>
                <h3 class="app-label">Register</h3>
                <div class="h-line"></div>
                <div class = "joining-form">
                <center>
                <form method="post" action="<?php $_SERVER[ 'PHP_SELF'] ?>">

                    <input type="text" id ="name" name="name" placeholder="Enter Name" value="<?php echo (isset($name) ? htmlspecialchars($name) : ''); ?>">
                    <input type="email" name="email" placeholder="Enter Email" value="<?php echo (isset($email) ? htmlspecialchars($email) : ''); ?>">

                    <input type="phone" id = "phone" name="phone" pattern="[0-9]{10}" placeholder="Phone Number" value="<?php echo (isset($numbers) ? htmlspecialchars($numbers) : ''); ?>">
                    <br>
                    <button type="submit" formnovalidate name="sendopt" class="btn btn-lg btn-success btn-block send-btn">Send OTP</button>
                    <br>
                    <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP" maxlength="5">
                    <br>
                    <button type="submit" name="verifyotp" class="btn btn-lg btn-info btn-block send-btn">Verify</button>
                    <?php if(!isset($verif) && !isset($notverif) ) {?>
                    <br>
                    <?php } ?>  
                    <?php if(isset($verif)) {?>
                    <span style="color:red; font-family: 'IBM Plex Sans', sans-serif;"><?php echo $verif; ?></span><br><br>
                    <?php } ?>

                    <?php if(isset($notverif)) {?>
                    <span style="color:red; font-family: 'IBM Plex Sans', sans-serif;"><?php echo $notverif; ?></span><br><br>
                    <?php } ?>  
                    <?php if(!isset($verif) && !isset($notverif) ) {?>
                    <br>
                    <?php } ?>  

                    <input type="text" name="usr" placeholder="Enter Username">
                    <input type="password" name="pass" placeholder="Enter Password">

                    <br>
                    <br>
                    <br>
                    <input type="submit" name="sign" class="send-btn join-btn" style="background-color: #0e0007; border: none; width: 320px" value="Sign up">

                    
                    <pre style="font-family: 'IBM Plex Sans', sans-serif; font-size: 1.2rem; color: white;">Already have an account?    <a href="user_login.php" style="text-decoration: none; color: #0e0007;">Login Now</a></pre>
                    
                    <?php if(isset($errMSG)) {?>
                    <span style="color:red; font-family: 'IBM Plex Sans', sans-serif;"><?php echo $errMSG; ?></span><br><br>
                    <?php } ?>
                    <?php if(isset($success)) {?>
                    <span style="color: red; font-family: 'IBM Plex Sans', sans-serif;"><?php echo $success; ?></span><br><br>
                    <?php } ?>
                    
                    <?php if(isset($err)) {?>
                    <span style="color:red; font-family: 'IBM Plex Sans', sans-serif;"><?php echo $err; ?></span><br><br>
                    <?php 
                  } ?>
                </form>
                  </center>
                </div>
            </div>
        </div>
        </div>
    </body>
    </html>-->