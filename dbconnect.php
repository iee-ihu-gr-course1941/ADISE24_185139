<?php
require_once 'secrets.php';

// Creates and returns a connection to MySQL
function createConnection() {
    // Create connection new mysqli
    $conn = new mysqli("server=$server ;dbname=$database", $username, $password);
    
    // Check connection and send error response if fail 
    if ($conn->connect_error) {
        header("HTTP/1.1 503 Service Unavailable");
        header('Content-Type: application/json;');
        echo '{"Response":"Could not connect to database", "StatusCode":503}';
        die("Connection failed: " . $conn-> connect_error);
    }
    echo "Connected successfully";
    return $conn;
    
}

// Closes connection to MySQL
function closeConnection($conn) {
    $conn->close();
}
?> 
