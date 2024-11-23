<?php

require_once 'secrets.php';
require 'dbconnect.php';

$conn = createConnection($servername, $username, $password, $database);

if ($result = $conn -> query("SELECT * FROM board")) {
    if ($result->num_rows > 0) {
      
      // output data of each row
      while($row = $result->fetch_assoc()) {
        echo $row["stili"];
      }
      
  }
  

header("HTTP/1.1 500 Internal Server Error");
header('Content-Type: application/json;');
echo '{"Response":"Internal Server Error", "StatusCode":500r}';

?>