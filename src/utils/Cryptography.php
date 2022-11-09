<?php
// error_reporting(E_ERROR);

namespace utils;

require_once (realpath(dirname(__FILE__) . '/../../vendor/autoload.php'));

use \Defuse\Crypto\Crypto;
use \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use \Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Exception;

class Cryptography {
    public function create_random_string (int $length = 128): string {
        if ($length > 128) $length = 128;
        return substr(hash('sha256', openssl_random_pseudo_bytes(128) . uniqid()), 0, $length);
    }

    /**
     * @throws Exception
     */
    public function create_secure_random_string (int $length = 128, $symbols = false): string {
        if ($length < 1)    $length = 1;
        if ($length > 1024) $length = 1024;

        $unique_id = uniqid() . uniqid() . uniqid() . uniqid();
        $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        // if ($symbols) $keyspace .= "~!@#$%^&*()_+{}[]:;<,>.?/|";
        if ($symbols) $keyspace .= "()_{}[]:;<,>./|@$";

        $keyspace = str_shuffle($keyspace . $unique_id);

        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i) {
                $pieces []= $keyspace[random_int(0, $max)];
        }

        return implode('', $pieces);
    }

    public function create_password_hash (string $password, string $salt): string {
        // Change these values for production server
        return password_hash($salt . $password . $salt, PASSWORD_ARGON2ID,
            [
                'memory_cost' => 2048,
                'time_cost'   => 4,
                'threads'     => 3
            ]
        );
    }

    public function verify_password_hash (string $password, string $hash, string $salt): bool {
        return password_verify($salt . $password . $salt, $hash);
    }

    public function aes256 ($str, $cipher_key, $decrypt_data = false): string {
        if (!$decrypt_data) {
            $salt = $this->create_random_string(15);
            $derived_key = $this->create_pbkdf2_key($cipher_key, $salt);
            $cipher_text = Crypto::encryptWithPassword($str, $derived_key) . '$' . $salt;
            return base64_encode($cipher_text);
        }

        $cipher_text = base64_decode($str);
        list($s_cipher_text, $s_salt) = explode('$', $cipher_text);
        $derived_key = $this->create_pbkdf2_key($cipher_key, $s_salt);

        try {
            $deciphered_text = Crypto::decryptWithPassword($s_cipher_text, $derived_key);
        } catch (WrongKeyOrModifiedCiphertextException | EnvironmentIsBrokenException $e) {
            return false;
        }

        return $deciphered_text;
    }

    private function create_pbkdf2_key (string $password, string $salt): string {
        return hash_pbkdf2("sha256", $password, $salt, 1000, 128);
    }
}