<?php
$servername = "localhost";
$database = "qltoanha";
$username = "root";
$password = "";

// Tạo kết nối
$conn = new mysqli('localhost', 'root', 'your_password', 'database_name');



// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";

if (!defined("WEB_URL")) {
    define("WEB_URL", "http://localhost:8888/qltoanha/");
}

if (!defined("ROOT_PATH")) {
    define("ROOT_PATH", "C:/dev/laragon/www/qltoanha/");
}
if (!defined("CURRENCY")) {
    define("CURRENCY", 1);
}

?>
