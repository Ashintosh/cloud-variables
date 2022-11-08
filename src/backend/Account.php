<?php
// error_reporting(E_ERROR);

namespace backend;

require_once (realpath(dirname(__FILE__) . '/../utils/Database.php'));
require_once (realpath(dirname(__FILE__) . '/../utils/Sessions.php'));
require_once (realpath(dirname(__FILE__) . '/../utils/Cookies.php'));
require_once (realpath(dirname(__FILE__) . '/../utils/Cryptography.php'));
require_once (realpath(dirname(__FILE__) . '/../utils/StringTools.php'));

use utils\Database;
use utils\Sessions;
use utils\Cookies;
use utils\Cryptography;
use utils\StringTools;
use PDO;

class Account {
    private Database $database;
    private Sessions $sessions;
    private Cookies $cookies;
    private Cryptography $crypto;
    private StringTools $string_tools;
    private ?PDO $conn;

    public function __construct () {
        $this->database     = new Database();
        $this->sessions     = new Sessions();
        $this->cookies      = new Cookies();
        $this->crypto       = new Cryptography();
        $this->string_tools = new StringTools();
        $this->conn = $this->database->connect();
    }

    public function create_account ($username, $password, $confirm_password, $email): bool {
        $username = $this->string_tools->sanitize_string($username);
        $username_lower  = strtolower($username);
        $email_lower     = strtolower($email);

        if (strlen($username) < 3) {
            $this->cookies->set("res-msg", "invalid_username");
            return false;
        }

        if (!filter_var($email_lower, FILTER_VALIDATE_EMAIL)) {
            $this->cookies->set("res-msg", "invalid_email");
            return false;
        }

        $has_special_char = $this->string_tools->has_special($password);
        $has_number_char = $this->string_tools->has_number($password);
        if ($password != $confirm_password) {
            $this->cookies->set("res-msg", "password_mismatch");
            return false;
        }
        if (strlen($password) < 6 || !$has_special_char || !$has_number_char) {
            $this->cookies->set("res-msg", "invalid_password");
            return false;
        }

        $salt = $this->crypto->create_random_string(15);
        $password_hash = $this->crypto->create_password_hash($password, $salt);

        $query = "SELECT username, email FROM `cv_users` WHERE LOWER(username) = :username OR email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username_lower);
        $stmt->bindParam(":email", $email_lower);

        if (!$stmt->execute()) {
            $this->cookies->set("res-msg", "unknown_error");
            return false;
        }

        $db_data = $stmt->fetch();

        if ($stmt->rowCount() > 0) {
            if (strtolower($db_data['username']) == $username_lower) {
                $this->cookies->set("res-msg", "taken_username");
                return false;
            }
            if ($db_data['email'] == $email_lower) {
                $this->cookies->set("res-msg", "taken_email");
                return false;
            }
        }

        $query = "INSERT INTO `cv_users` (username, password, salt, email) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt->execute([$username, $password_hash, $salt, $email_lower])) {
            $this->cookies->set("res-msg", "unknown_error");
            return false;
        }

        $this->cookies->set("res-msg", "register_successful");
        return true;
    }

    public function login_account ($login_name, $password): bool {
        if ($this->sessions->get("login_data") != null) {
            return true;
        }

        $login_name = $this->string_tools->sanitize_string($login_name);
        $login_name_lower = strtolower($login_name);

        $query = "SELECT uid, username, password, salt, email FROM `cv_users` WHERE LOWER(username) = :login_name OR email = :login_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":login_name", $login_name_lower);

        if (!$stmt->execute()) {
            $this->cookies->set("res-msg", "unknown_error");
            return false;
        }

        $db_data = $stmt->fetch();
        $uid = (int) $db_data['uid'];

        if ($stmt->rowCount() < 1 || !$this->crypto->verify_password_hash($password, $db_data['password'], $db_data['salt'])) {
            $this->cookies->set("res-msg", "invalid_login");
            return false;
        }

        $login_key = $this->crypto->create_random_string(32);
        $login_key_hash = hash('sha512', $login_key);

        $query = "UPDATE `cv_users` SET login_key = :login_key WHERE uid = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":login_key", $login_key_hash);
        $stmt->bindParam(":uid", $uid);

        if (!$stmt->execute()) {
            $this->cookies->set("res-msg", "unknown_error");
            return false;
        }

        $response = json_encode(array(
            "status" => true,
            "uid" => $uid,
            "username" => $db_data['username'],
            "email" => $db_data['email'],
            "login_key" => $login_key
        ));

        $this->sessions->set_security_sessions();
        $this->sessions->set("login_data", $response);
        $this->cookies->set("res-msg", "login_successful");
        return true;
    }

    public function logout_account (): bool {
        $login_data = json_decode($this->sessions->get("login_data"), true);
        $uid = (int) $login_data['uid'];

        $query = "UPDATE `cv_users` SET login_key = null WHERE uid = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $uid);

        if (!$stmt->execute()) {
            $this->cookies->set("res-msg", "unknown_error");
            return false;
        }

        $this->sessions->unset("login_data");
        return true;
    }

    public function change_email ($new_email): bool {
        if (!$this->validate_login_status()) {
            $this->cookies->set("res-msg", "invalid_login");
            return false;
        }

        $new_email_lower = $this->string_tools->sanitize_string(strtolower($new_email));
        $user_data = json_decode($this->sessions->get("login_data"), true);
        $uid = (int) $user_data['uid'];

        $query = "UPDATE `cv_users` SET email = :email WHERE uid = :uid";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":email", $new_email_lower);
        $stmt->bindParam(":uid", $uid);

        if (!$stmt->execute()) {
            $this->cookies->set("res-msg", "unknown_error");
            return false;
        }

        $this->cookies->set("res-msg", "email_change_successful");
        return true;
    }

    public function change_password ($new_password, $confirm_new_password, $current_password): bool {
        if (!$this->validate_login_status()) {
            $this->cookies->set("res-msg", "invalid_login");
            return false;
        }

        $user_data = json_decode($this->sessions->get("login_data"), true);
        $uid = (int) $user_data['uid'];

        if ($new_password != $confirm_new_password) {
            $this->cookies->set("res-msg", "password_mismatch");
            return false;
        }

        if (!$this->verify_password($current_password, $uid)) return false;

        $new_salt = $this->crypto->create_random_string(15);
        $new_password_hash = $this->crypto->create_password_hash($new_password, $new_salt);

        $query = "UPDATE `cv_users` SET password = :password, salt = :salt WHERE uid = :uid";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":password", $new_password_hash);
        $stmt->bindParam(":salt", $new_salt);
        $stmt->bindParam(":uid", $uid);

        if (!$stmt->execute()) {
            $this->cookies->set("res-msg", "unknown_error");
            return false;
        }

        $this->logout_account();
        $this->cookies->set("res-msg", "password_change_successful");
        return true;
    }

    public function validate_login_status (): bool {
        if ($this->sessions->get("login_data") == null) {
            $this->cookies->set("res-msg", "invalid_login");
            return false;
        }

        $user_data = json_decode($this->sessions->get("login_data"), true);
        $uid = (int) $user_data['uid'];

        $query = "SELECT uid, login_key FROM `cv_users` WHERE uid = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $uid);

        if (!$stmt->execute()) {
            $this->cookies->set("res-msg", "unknown_error");
            return false;
        }

        $db_data = $stmt->fetch();
        $db_login_key = $db_data['login_key'];

        if ($db_login_key != hash('sha512', $user_data['login_key'])) {
            $this->cookies->set("res-msg", "invalid_login");
            return false;
        }

        return true;
    }

    public function delete_account ($password): bool {
        if ($this->sessions->get("login_data") == null) {
            $this->cookies->set("res-msg", "invalid_login");
            return false;
        }

        $user_data = json_decode($this->sessions->get("login_data"), true);
        $uid = (int) $user_data['uid'];

        if (!$this->verify_password($password, $uid)) return false;

        $query = "DELETE FROM `cv_users` WHERE uid = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $uid);

        if (!$stmt->execute()) {
            $this->cookies->set("res-msg", "unknown_error");
            return false;
        }

        $this->cookies->set("res-msg", "account_delete_successful");
        return true;
    }

    private function verify_password ($password, $uid): bool {
        $query = "SELECT uid, password, salt FROM `cv_users` WHERE uid = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $uid);

        if (!$stmt->execute()) {
            $this->cookies->set("res-msg", "unknown_error");
            return false;
        }

        $db_data = $stmt->fetch();

        if (!$this->crypto->verify_password_hash($password, $db_data['password'], $db_data['salt'])) {
            $this->cookies->set("res-msg", "invalid_password_login");
            return false;
        }

        return true;
    }
}