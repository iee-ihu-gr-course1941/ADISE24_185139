<?php

require_once 'secrets.php';
require 'dbconnect.php';

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








































function get_board($conn) {
  if ($result = $conn -> query("SELECT * FROM board")) {
    if ($result->num_rows > 0) {
      // output data of each row
      $json = '{'; 
      while($row = $result->fetch_assoc()) {
        $json = $json . '"a' . $row["stili"] . '":"' . $row["a"] . '", "b' . $row["stili"] . '":"' . $row["b"] . '", "c' . $row["stili"] . '":"' . $row["c"] . '", "d' . $row["stili"] . '":"' . $row["d"] . '","e' . $row["stili"] . '":"' . $row["e"] . '", "f' . $row["stili"] . '":"' . $row["f"] . '", "g' . $row["stili"] . '":"' . $row["g"] . '",';
      }
      $json = substr_replace($json, '', -1);
      $json = $json . '}';
      header("HTTP/1.1 200 Ok");
      header('Content-Type: application/json;');
      echo $json;
    }
  }
}
  



?>

