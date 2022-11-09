<?php
// error_reporting(E_ERROR);

require_once (realpath(dirname(__FILE__) . '/../utils/Sessions.php'));

use utils\Sessions;

$sessions = new Sessions();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>CloudVar - Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/fonts.css">
    <link rel="stylesheet" href="../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="row align-items-start">
                <div class="col">
                    <div class="title">
                        <h2>CloudVar</h2>
                        <p>Securely store variables in our cloud database servers.</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="nav">
                        <ul>
                            <?php if ($sessions->get("login_data") != null): ?>
                                <li><a href="panel/">Panel</a></li>
                                <li><a href="panel/settings/">Settings</a></li>
                                <li><a href="">Sign out</a></li>
                            <?php else: ?>
                                <li><a href="login/">Login</a></li>
                                <li><a href="signup/">Sign Up</a></li>
                                <li><a href="contact/">Contact</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>