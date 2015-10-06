<?php
$servername = "localhost";
$username = "vintelliWPDB";
$password = "Saka@1813";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
?>