<?php
namespace utils;
class Cookies {
    public function set ($name, $value): void {
        $expire_time = time() + (86400 * 30); // 1 day
        setcookie($name, $value, $expire_time, '/');
    }

    public function get ($name) {
        if (!isset($_COOKIE[$name])) return null;
        return $_COOKIE[$name];
    }

    public function unset ($name): void {
        setcookie($name, "", time() - 3600);
    }
}