<?php
// error_reporting(E_ERROR);

namespace utils;

require_once (realpath(dirname(__FILE__) . '/../../vendor/autoload.php'));

use Dotenv;

class EnvironmentVariables {
    public function get_variable ($id) {
        $dotenv = Dotenv\Dotenv::createImmutable(realpath(dirname(__FILE__) . '/../'));
        $dotenv->load();
        return $_ENV[$id];
    }
}