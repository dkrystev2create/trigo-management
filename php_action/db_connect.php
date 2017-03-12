<?php 	

$localhost = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "trigo";

// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
// check connection
if($connect->connect_error) {
	die("Грещка при свързване: " . $connect->connect_error);
} else {
  // echo "Successfully connected";
}

?>