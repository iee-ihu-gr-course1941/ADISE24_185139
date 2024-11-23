<?php

require_once 'secrets.php';
require 'dbconnect.php';
require_once 'dbcalls.php';

$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

$conn = createConnection($servername, $username, $password, $database);

switch ($request[0]) {
  case "board":
    get_board($conn);
    break;

  default:
    header("HTTP/1.1 404 Endpoint not found");
    header('Content-Type: application/json;');
    echo '{"Response":"Endpoint not found", "StatusCode":404}';
}


?>

