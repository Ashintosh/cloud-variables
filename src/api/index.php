<?php
header('Access-Control-Allow-Origin: *');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET") {
    if (!isset($_GET['action']) || !isset($_GET['name'])) {
        $response = json_encode(array("status" => "invalid-inputs"));
        die ($response);
    }
    $action = $_GET['action'];
    $name   = $_GET['name'];

    if ($action == "variable") {
        
    }
}

if ($method == "POST") {

}

if ($method == "PUT") {

}

if ($method == "DELETE") {

}