<?php

require_once 'secrets.php';
require 'dbconnect.php';
require_once 'dbcalls.php';

$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

$conn = createConnection($servername, $username, $password, $database);

$currentPlayer = getBearerToken();

// TODO

// 1. Make "reset" endpoint that resets whole game
// 2. Make "status" enpoint that shows game status (Player1 move, Player2 move, Player1 won, Player2 won)

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

  case "status":
    get_status($conn);
    break;

  case "reset":
    reset_game($conn);
    break;

  default:
    header("HTTP/1.1 404 Endpoint not found");
    header('Content-Type: application/json;');
    echo '{"Response":"Endpoint not found", "StatusCode":404}';
}


// Found on StackOverflow: https://stackoverflow.com/questions/40582161/how-to-properly-use-bearer-tokens
function getAuthorizationHeader(){
  $headers = null;
  if (isset($_SERVER['Authorization'])) {
      $headers = trim($_SERVER["Authorization"]);
  }
  else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
      $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
  } elseif (function_exists('apache_request_headers')) {
      $requestHeaders = apache_request_headers();
      // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
      $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
      //print_r($requestHeaders);
      if (isset($requestHeaders['Authorization'])) {
          $headers = trim($requestHeaders['Authorization']);
      }
  }
  return $headers;
}

/**
* get access token from header
* */
function getBearerToken() {
  $headers = getAuthorizationHeader();
  // HEADER: Get the access token from the header
  if (!empty($headers)) {
      if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
          return $matches[1];
      }
  }
  return null;
}

?>

