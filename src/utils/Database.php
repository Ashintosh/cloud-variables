<?php
// error_reporting(E_ERROR);

namespace utils;

require_once "EnvironmentVariables.php";
require_once "Cryptography.php";

use PDO;
use PDOException;

class Database {
    private EnvironmentVariables $env;
    private Cryptography $crypto;
    private string $host, $db_name, $username, $password, $charset;

    public function __construct () {
        $this->env = new EnvironmentVariables();
        $this->crypto = new Cryptography();

        $this->host = $this->env->get_variable("DB_HOST");
        $this->db_name = $this->env->get_variable("DB_NAME");
        $this->username = $this->env->get_variable("DB_USERNAME");
        $this->password = $this->env->get_variable("DB_PASSWORD");
        $this->charset = "utf8mb4";
    }

    public function connect (): ?PDO {
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // echo "Database connection failed: " . $e->getMessage() . "\n";
            return null;
        }

        // echo "Database connection successful. \n";
        return $pdo;
    }

    public function db_crypt ($str, $decrypt = false): string {
        $key = $this->env->get_variable("DB_CRYPT_KEY");
        return $this->crypto->aes256($str, $key, $decrypt);
    }
}