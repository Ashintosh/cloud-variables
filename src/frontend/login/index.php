<?php
// error_reporting(E_ERROR);

require_once (realpath(dirname(__FILE__) . '/../../utils/Sessions.php'));
require_once (realpath(dirname(__FILE__) . '/../../utils/Cookies.php'));
require_once (realpath(dirname(__FILE__) . '/../../backend/Account.php'));

use utils\Sessions;
use utils\Cookies;
use backend\Account;

$sessions = new Sessions();
$cookies  = new Cookies();
$account  = new Account();

if ($account->validate_login_status()) {
    header("Location: ../panel/");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CloudVar - Log In</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/fonts.css">
    <link rel="stylesheet" href="../assets/css/ohsnap!.css">
    <!-- <link rel="stylesheet" href="../../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>
<body>
<div id="ohsnap"></div>
<div class="container">
   <div class="header">
       <div class="row align-items-start">
           <div class="col">
               <div class="title">
                   <h2><a href="../">CloudVar</a></h2>
                   <p>Log in to your CloudVar account.</p>
               </div>
           </div>
           <div class="col-4">
               <div class="nav">
                   <ul>
                       <li><a class="active">Login</a></li>
                       <li><a href="../signup/">Sign Up</a></li>
                       <li><a href="../contact/">Contact</a></li>
                   </ul>
               </div>
           </div>
       </div>
   </div>
   <div class="page">
       <div class="page-header">
           <h2>Log in</h2>
       </div>
       <div class="form-wrapper">
           <form action="login.php" method="post">
               <input type="hidden" name="action" value="login_account">
               <div class="row align-items-start">
                   <div class="col">
                       <label>Username/Email</label>
                       <input type="text" name="username" required>
                   </div>
                   <div class="col">
                       <label>Password</label>
                       <input type="password" name="password" required>
                   </div>
               </div>
               <input type="submit" value="Log In">
           </form>
       </div>
   </div>
</div>
<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript" src="../assets/js/main.js"></script>
<script type="text/javascript" src="../assets/js/OhSnap!.js"></script>
</body>
</html>