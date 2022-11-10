<?php
include "../src/utils/Cryptography.php";
include "../src/utils/StringTools.php";
include "../src/utils/Database.php";

use \utils\Cryptography;
use \utils\StringTools;
use \utils\Database;

$crypto = new Cryptography();
$string_tools = new StringTools();
$database = new Database();

$count = 0;
while ($count < 100) {
    $secure_string = $crypto->create_secure_random_string(64, true);
    $secure_string_2 = $crypto->create_secure_random_string(32);

    $secure_string_json = json_encode(array(
        "secure_string"   => $secure_string,
        "secure_string_2" => $secure_string_2
    ));

    $cipher_password = "Lh64MrZz5d49RhSYMoeoWbf8dLw95JDD6oojS8ZQauYq2ynAA5";
    $secure_string_json_encrypted = $crypto->aes256($secure_string_json, $cipher_password);
    $secure_string_json_decrypted = $crypto->aes256($secure_string_json_encrypted, $cipher_password, true);

    $secure_string_decrypted = json_decode($secure_string_json_decrypted, true);
    $secure_string_result = $secure_string_decrypted['secure_string'];

    echo $secure_string_result;
    echo "\n\n\n";
    $count++;
}

// Todo: Fix issue with API Key Secret showing key shorter than 65 chars in settings page