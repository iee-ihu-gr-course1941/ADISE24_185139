<?php
require_once 'secrets';

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  header("HTTP/1.1 503 Service Unavailable");
  header('Content-Type: application/json;');
  echo '{"Response":"Could not connect to database", "StatusCode":503}';
  die("Connection failed: " . $conn->connect_error);
}
?> 