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

if (!$account->validate_login_status()) {
    header("Location: ../login/");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CloudVar - Panel</title>
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
                    <p>Create and manage variables.</p>
                </div>
            </div>
            <div class="col-4">
                <div class="nav">
                    <ul>
                        <li><a class="active">Panel</a></li>
                        <li><a href="../signup/">Settings</a></li>
                        <li><a href="../contact/">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="page">
        <div class="row align-items-start">
            <div class="secret-info">
                <div class="col">
                    <p>API Secret Key: ********************************************</p>
                </div>
                <div class="col">
                    <button class="btn-primary">Show Key</button>
                </div>
            </div>
            <div class="col">
                <div class="api-calls">
                    <p>API Calls Left: <span>50</span></p>
                    <p>Click <a href="order/">here</a> to buy more.</p>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Variable Name</th>
                        <th>Variable Value</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Var1</td>
                        <td>This is a test value.</td>
                        <td>
                            <a href="">Show</a>
                            <a href="">Edit</a>
                            <a href="">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Var2</td>
                        <td>Desc 5</td>
                        <td>Desc 6</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="../assets/js/main.js"></script>
<script type="text/javascript" src="../assets/js/OhSnap!.js"></script>
</body>
</html>