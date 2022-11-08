<?php
include "../src/utils/Database.php";

use \utils\Database;

$database = new Database();
$database->connect();

echo "Done.";