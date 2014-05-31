<?php 

/*	
$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "1234";
$mysql_database = "Service";
*/

$mysql_hostname = "23.229.154.169";
$mysql_user = "happy";
$mysql_password = "Ab1234";
$mysql_database = "Service";


$conn = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}
else{
	//echo "DB Connection Okay!";
}


?>