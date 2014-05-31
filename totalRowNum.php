<?php
include_once("util.php");
include_once("configdb.php");

header('Content-Type: text/html; charset=utf-8');
$conn->query("SET NAMES 'utf8'");
$conn->query("SET CHARACTER_SET_CLIENT='utf8'");
$conn->query("SET CHARACTER_SET_RESULTS='utf8'");

$sql = "SELECT count(id) as total FROM Service.main";

if (!$rs = $conn->query($sql)) {
	errClose($rs, $conn);
	echo "0";
	exit();
}

if($rs->num_rows == 0) { 
	echo "0";
	$rs->close(); exit();
}
$row = $rs->fetch_array(MYSQLI_ASSOC);
echo $row['total']; 

//close db connection
okClose($rs, $conn);
?>

