<?php

require_once 'secrets.php';
require 'dbconnect.php';
require_once 'dbcalls.php';

$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

$conn = createConnection($servername, $username, $password, $database);

$currentPlayer = $_SERVER['Authorization'];

switch ($request[0]) {
  case "board":
    get_board($conn);
    break;
  
  case "move":
    if ($currentPlayer != "Player1" && $currentPlayer != "Player2") {
      header("HTTP/1.1 403 Unauthorized");
      header('Content-Type: application/json;');
      echo '{"Response":"Unauthorized", "StatusCode":403}';
      die();
    }

    get_move($conn, $request[1], $request[2], $currentPlayer);
    break;

  default:
    header("HTTP/1.1 404 Endpoint not found");
    header('Content-Type: application/json;');
    echo '{"Response":"Endpoint not found", "StatusCode":404}';
}


?>

