<?php

require_once 'secrets.php';
require 'dbconnect.php';

$conn = createConnection($servername, $username, $password, $database);

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
  



?>

