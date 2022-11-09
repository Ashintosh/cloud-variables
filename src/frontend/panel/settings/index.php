<?php
// error_reporting(E_ERROR);

require_once (realpath(dirname(__FILE__) . '/../../../utils/Sessions.php'));
require_once (realpath(dirname(__FILE__) . '/../../../utils/Cookies.php'));
require_once (realpath(dirname(__FILE__) . '/../../../utils/StringTools.php'));
require_once (realpath(dirname(__FILE__) . '/../../../backend/Account.php'));

use utils\Sessions;
use utils\Cookies;
use utils\StringTools;
use backend\Account;

$sessions     = new Sessions();
$cookies      = new Cookies();
$string_tools = new StringTools();
$account      = new Account();

if (!$account->validate_login_status()) {
    header("Location: ../../login/");
    exit;
}

$user_data = json_decode($sessions->get("login_data"), true);

$username = $string_tools->sanitize_string($user_data['username']);
$email = $string_tools->sanitize_string($user_data['email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CloudVar - Settings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/fonts.css">
    <link rel="stylesheet" href="../../assets/css/ohsnap!.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
<div id="ohsnap"></div>
<div class="container">
    <div class="header">
        <div class="row align-items-start">
            <div class="col">
                <div class="title">
                    <h2><a href="../../">CloudVar</a></h2>
                    <p>Manage account settings.</p>
                </div>
            </div>
            <div class="col-4">
                <div class="nav">
                    <ul>
                        <li><a href="../">Panel</a></li>
                        <li><a class="active">Settings</a></li>
                        <li><a href="settings.php?action=sign-out">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="page">
        <div class="page-header">
            <h2>API Settings</h2>
        </div>
        <div class="row align-items-start">
            <div class="secret-info">
                <div class="col">
                    <p>API Key ID</p>
                    <div class="secret-key">
                        <p>This is a test id</p>
                    </div>

                    <p>API Key Secret</p>
                    <div class="secret-key">
                        <p>This is a test secret</p>
                    </div>
                </div>
                <div class="col">
                    <button class="btn-primary">Show Key</button>
                    <button class="btn-primary">Copy Key</button>
                    <button class="btn-primary">Regenerate Key</button>
                </div>
            </div>
            <div class="col">
                <div class="api-calls">
                    <p>
                        API Calls Left: <span>[Beta] Unlimited</span>
                        <br>
                        <a href="order/">Get more API calls.</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="page-header">
            <h2>Account Settings</h2>
        </div>
        <div class="form-wrapper">
            <form action="settings.php" method="post">
                <div class="row align-items-start">
                    <input type="hidden" name="action" value="save_account_changes">
                    <div class="col">
                        <label>Username</label>
                        <input type="text" placeholder="<?php echo $username; ?>" readonly>

                        <label>Email</label>
                        <input type="email" name="email" placeholder="<?php echo $email; ?>" value="<?php echo $email; ?>">
                    </div>
                    <div class="col">
                        <label>New Password</label>
                        <input type="password" name="new_password">

                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password">
                    </div>
                    <div class="col">
                        <label>Current Password <span>*</span></label>
                        <input type="password" name="current_password" required>
                        <br>
                        <div class="save-changes">
                            <input type="submit" value="Save Changes">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript" src="../../assets/js/main.js"></script>
<script type="text/javascript" src="../../assets/js/OhSnap!.js"></script>
</body>
</html>