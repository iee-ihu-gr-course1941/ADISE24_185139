<?php

require_once 'secrets.php';
require 'dbconnect.php';

$conn = createConnection($servername, $username, $password, $database);

if ($result = $conn -> query("SELECT * FROM board")) {
    echo "Returned rows are: " . $stilistili;
    // Free result set
    $result -> free_result();
  }
  

header("HTTP/1.1 500 Internal Server Error");
header('Content-Type: application/json;');
echo '{"Response":"Internal Server Error", "StatusCode":500}';

?>