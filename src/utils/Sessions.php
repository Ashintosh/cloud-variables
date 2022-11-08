<?php
// error_reporting(E_ERROR);

namespace utils;

session_start();
session_regenerate_id();

class Sessions {
    public function validate_all (): void {

    }

    public function set_security_sessions (): void {
        $_SESSION['user_agent']  = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ip_address']  = $_SERVER['REMOTE_ADDR'];
        $_SESSION['last_access'] = time();
    }

    public function set ($name, $value, $set_last_access = true): void {
        if ($set_last_access) {
            $_SESSION['last_access'] = time();
        }
        $_SESSION[$name] = $value;
    }

    public function get ($name, $set_last_access = true) {
        if ($set_last_access)         $_SESSION['last_access'] = time();
        if (!isset($_SESSION[$name])) return null;

        return $_SESSION[$name];
    }

    public function unset ($name): void {
        unset($_SESSION[$name]);
    }

    public function validate_user_agent (): void {
        if (isset($_SESSION['user_agent']) && $_SERVER['HTTP_USER_AGENT'] != $_SESSION['user_agent']) {
            session_unset();
            session_destroy();
        }
    }

    public function validate_remote_address (): void {
        if (isset($_SESSION['ip_address']) && $_SERVER['REMOTE_ADDR'] != $_SESSION['ip_address']) {
            session_unset();
            session_destroy();
        }
    }

    public function validate_access_time (): void {
        if (isset($_SESSION['last_access']) && time() > $_SESSION['last_access'] + 3600) {
            session_unset();
            session_destroy();
        }
    }
}