<?php 
require 'db.php';
session_start();
$_SESSION['classClicked'] = '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
  <title>uniNotes</title>
  <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
      <link rel="stylesheet" href="css/login_page_style.css">
</head>
<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (isset($_POST['login'])) { //user logging in
        require 'phpFunctions/login.php';   
    }  
    elseif (isset($_POST['register'])) { //user registering
        require 'phpFunctions/register.php';
    }
  }
?>
<body>
      <div class="form">
      <div class="login">
    <form action="index.php" method="post" autocomplete="off">
            <div class="field-wrap">
            <img src="css/images&shit/logo.png" class="logo">
            <label>
              Email<span class="req">*</span>
            </label>
            <input type="email"required name="email" autocomplete="off" id="email"/>
            <img src="css/images&shit/user.png" class="usericon">
          </div>          
          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off" id="pwdlogin" name="password"/>
             <img src="css/images&shit/lock.png" class="lockicon">
          </div>       
                <p class="register"><a href="#">Register Here!</a></p>
                <p class="forgot"><a href="#">Forgot password?</a></p>                   
          <button type="submit" class="button button-block" name = "login" class="button button-block"/>Log in</button>          
          </form>
  </div>
  <div class="signup">
    <h1>Sign Up for free!</h1>          
          <form action="index.php" method="post" autocomplete="off">         
            <div class="field-wrap">
              <label>
                Set A Username<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name="username"/>
            </div>
          <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email"required autocomplete="off" name="email"/>
          </div>          
          <div class="field-wrap">
            <label>
              Set A Password<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off" id="pwdsignup" name="password"/>           
          </div>
          <div class="field-wrap">
            <label>
              Confirm Password<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off" name="password_confirm"/>          
          </div>
           <p class="signin"><a href="#">Already have an account?</a></p>          
          <button type="submit" class="button button-block" name="register"/>Get Started</button>          
          </form>
  </div>
  <div class="forgotpwd">
    <h2>Well thats just too bad for you</h2>
     <p class="return"><a href="#">Return back you moron</a></p>
  </div>
    </div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="js/index.js"></script>
</body>
</html>
