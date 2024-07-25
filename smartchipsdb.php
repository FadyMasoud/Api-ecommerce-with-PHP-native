<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");




$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = "77220011fady"; // Your database password
$dbname = "Smartchips"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// else
// {
//     echo "Connected successfully";
// }
// Close connection
// $conn->close();
?>
