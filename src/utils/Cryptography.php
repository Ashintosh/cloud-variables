<?php
// error_reporting(E_ERROR);

namespace utils;

require_once (realpath(dirname(__FILE__) . '/../../vendor/autoload.php'));

use \Defuse\Crypto\Crypto;
use \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use \Defuse\Crypto\Exception\EnvironmentIsBrokenException;

class Cryptography {
    public function create_random_string ($length = 128): string {
        if ($length > 128) $length = 128;
        return substr(hash('sha256', openssl_random_pseudo_bytes(128)), 0, $length);
    }

    public function create_password_hash ($password, $salt): string {
        // Change these values for production server
        return password_hash($salt . $password . $salt, PASSWORD_ARGON2ID,
            [
                'memory_cost' => 2048,
                'time_cost'   => 4,
                'threads'     => 3
            ]
        );
    }

    public function verify_password_hash ($password, $hash, $salt): bool {
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

    private function create_pbkdf2_key ($password, $salt): string {
        return hash_pbkdf2("sha256", $password, $salt, 1000, 128);
    }
}