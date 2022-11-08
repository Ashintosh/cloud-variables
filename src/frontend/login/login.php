<?php
// error_reporting(E_ERROR);

require_once (realpath(dirname(__FILE__) . '/../../backend/Account.php'));

use backend\Account;

$account = new Account();

if ($account->validate_login_status()) {
    header("Location: ../panel/");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] == "login_account") {
    $login_name = $_POST['username'];
    $password   = $_POST['password'];

    if (!$account->login_account($login_name, $password)) {
        header("Location: ../login/");
        exit;
    }

    header("Location: ../panel/");
    exit;
}