<?php

require 'dbconnect.php';

$conn = createConnection();

if ($result = $conn -> query("SELECT * FROM board")) {
    echo "Returned rows are: " . $result -> num_rows;
    // Free result set
    $result -> free_result();
  }
  

header("HTTP/1.1 500 Internal Server Error");
header('Content-Type: application/json;');
echo '{"Response":"Internal Server Error", "StatusCode":500}';

?>