<?php
// error_reporting(E_ERROR);

require_once (realpath(dirname(__FILE__) . '/../../../backend/Account.php'));
require_once (realpath(dirname(__FILE__) . '/../../../utils/Sessions.php'));

use backend\Account;
use utils\Sessions;

$account  = new Account();
$sessions = new Sessions();

if (!$account->validate_login_status()) {
    header("Location: ../../login/");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] == "save_account_changes") {
    $new_email            = $_POST['email'];
    $new_password         = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_password'];
    $current_password     = $_POST['current_password'];

    $user_data = json_decode($sessions->get("login_data"), true);

    if (strtolower($new_email) != $user_data['email']) {
        $account->change_email($new_email, $current_password);
    }

    if (strlen($new_password) > 0 || strlen($confirm_new_password) > 0) {
        $account->change_password($new_password, $confirm_new_password, $current_password);
    }

    header("Location: ../settings/");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == "sign-out") {
    $account->logout_account();

    header("Location: ../../login/");
    exit;
}