<?php
include "../src/utils/Cryptography.php";
include "../src/utils/StringTools.php";

use \utils\Cryptography;
use \utils\StringTools;

$crypto = new Cryptography();
$string_tools = new StringTools();

echo $crypto->create_secure_random_string(64, true);