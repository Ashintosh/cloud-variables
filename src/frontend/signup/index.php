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
    <title>CloudVar - Sign Up</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/fonts.css">
    <link rel="stylesheet" href="../../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
<div id="ohsnap"></div>
<div class="container">
    <div class="header">
        <div class="row align-items-start">
            <div class="col">
                <div class="title">
                    <h2><a href="../">CloudVar</a></h2>
                    <p>Create an account with CloudVar.</p>
                </div>
            </div>
            <div class="col-4">
                <div class="nav">
                    <ul>
                        <li><a href="../login/">Login</a></li>
                        <li><a class="active">Sign Up</a></li>
                        <li><a href="../contact/">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="page">
        <div class="form-wrapper">
            <form action="signup.php" method="post">
                <input type="hidden" name="action" value="create_account">
                <div class="row align-items-start">
                    <div class="col">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="col">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                </div>
                <div class="row align-items-start">
                    <div class="col">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="col">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                </div>
                <input type="submit" value="Create Account">
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="../assets/js/main.js"></script>
<script type="text/javascript" src="../assets/js/OhSnap!.js"></script>
</body>
</html>