<?php
// error_reporting(E_ERROR);

namespace backend;

require_once (realpath(dirname(__FILE__) . '/../utils/Database.php'));
require_once (realpath(dirname(__FILE__) . '/../utils/Cryptography.php'));
require_once (realpath(dirname(__FILE__) . '/../utils/StringTools.php.php'));

use utils\Database;
use utils\Cryptography;
use utils\StringTools;

class Variables {
    private Database $database;
    private Cryptography $crypto;
    private StringTools $string_tools;

    public function __construct () {
        $this->database     = new Database();
        $this->crypto       = new Cryptography();
        $this->string_tools = new StringTools();
    }

    public function get_variable (string $name): string {
        $name = $this->string_tools->sanitize_string($name);


    }

    public function set_variable (string $name, string $value): bool {
        $name = $this->string_tools->sanitize_string($name);

    }

    // Todo: Add delete_variable() function
}
