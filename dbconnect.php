<?php
require_once 'index.php';

// Creates and returns a connection to MySQL
function createConnection($servername, $username, $password, $database) {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    
    // Check connection and send error response if fail 
    if ($conn->connect_error) {
        header("HTTP/1.1 503 Service Unavailable");
        header('Content-Type: application/json;');
        echo '{"Response":"Could not connect to database", "StatusCode":503}';
        die();
    }
    return $conn;
    
}

// Closes connection to MySQL
function closeConnection($conn) {
    $conn->close();
}
?> 
