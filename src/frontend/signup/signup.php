<?php
// error_reporting(E_ERROR);

require_once (realpath(dirname(__FILE__) . '/../../backend/Account.php'));

use backend\Account;

if (isset($_POST['action']) && $_POST['action'] == "create_account") {
    $username         = $_POST['username'];
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email            = $_POST['email'];

    $account = new Account();

    if ($account->validate_login_status()) {
        header("Location: ../panel/");
        exit;
    }

    if (!$account->create_account($username, $password, $confirm_password, $email)) {
        header("Location: ../signup/");
        exit;
    }

    header("Location: ../login/");
    exit;
}