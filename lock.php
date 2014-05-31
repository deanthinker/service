<?php
include_once("util.php");
include_once("configdb.php");
session_start();
$user_check=$_SESSION['login_user'];

$sql = "SELECT name from Service.user where un='$user_check' ";
if (!$rs = $conn->query($sql)) {
	errClose($rs, $conn);
}
 
$row = $rs->fetch_array(MYSQLI_ASSOC);
$login_session=$row['name'];
 
if(!isset($login_session))
{
header("Location: login.php");
}


?>

<body>
</body>