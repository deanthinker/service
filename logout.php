<?php
session_start();
if(session_destroy())
{
header("Location: login.php");
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>