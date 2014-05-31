<?php
require_once("configdb.php");
require('lock.php');

header('Content-Type: text/html; charset=utf-8');
$conn->query("SET NAMES 'utf8'");
$conn->query("SET CHARACTER_SET_CLIENT='utf8'");
$conn->query("SET CHARACTER_SET_RESULTS='utf8'");

//checking action and content
if(isset($_POST["a"]) && strlen($_POST["a"])>0 ) {
	$action = filter_var($_POST["a"], FILTER_SANITIZE_STRING);
	$id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);
	$name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
	$tel1 = filter_var($_POST["tel1"], FILTER_SANITIZE_STRING);
	$tel2 = filter_var($_POST["tel2"], FILTER_SANITIZE_STRING);
	$mobile = filter_var($_POST["mobile"], FILTER_SANITIZE_STRING);
	$stat = filter_var($_POST["stat"], FILTER_SANITIZE_STRING);
	$zip = filter_var($_POST["zip"], FILTER_SANITIZE_STRING);
	$addr = filter_var($_POST["addr"], FILTER_SANITIZE_STRING);
	$type = filter_var($_POST["type"], FILTER_SANITIZE_STRING);
	$fm1 = filter_var($_POST["fm1"], FILTER_SANITIZE_STRING);
	$fm2 = filter_var($_POST["fm2"], FILTER_SANITIZE_STRING);
	$fm3 = filter_var($_POST["fm3"], FILTER_SANITIZE_STRING);
	$fm4 = filter_var($_POST["fm4"], FILTER_SANITIZE_STRING);
	$fm5 = filter_var($_POST["fm5"], FILTER_SANITIZE_STRING);
	$fm6 = filter_var($_POST["fm6"], FILTER_SANITIZE_STRING);
	$note = filter_var($_POST["note"], FILTER_SANITIZE_STRING);
	$plp = filter_var($_POST["plp"], FILTER_SANITIZE_STRING);
	
}else{
	echo "HTTP 1.1 500 Error Occur: No action is defined.";
	exit();
}


if ($action == "update"){
	$sql = "UPDATE Service.main SET 
			name = '$name',
			htel1 = '$tel1', htel2 = '$tel2',
			mobile = '$mobile',
			zip = '$zip', addr = '$addr',
			stat = '$stat', type = '$type',
			fm1 = '$fm1', fm2 = '$fm2', fm3 = '$fm3',
			fm4 = '$fm4', fm5 = '$fm5', fm6 = '$fm6',
			note = '$note',
			plp = '$plp'
			WHERE id = $id";

	if($conn->query($sql)){
		echo "<p>資料更新成功</p>";
	}
	else{
		echo "<p>Fail</p>";
	}
	
}
else if ($action == "add"){
	$sql = "INSERT INTO Service.main VALUES 
			(NULL, 
			 '$name',
			 '$tel1',  '$tel2',
			 '$mobile',
			 '$zip',  '$addr',
			 '$stat',  '$type',
			 '$fm1',  '$fm2', '$fm3',
			 '$fm4',  '$fm5', '$fm6',
			 '$note',
			 '$plp')";

	if($conn->query($sql)){
		echo "<p>資料更新成功</p>";
	}
	else{
		echo "<p>Fail</p>" ;
	}

}
else if ($action == "del"){
	$sql = "DELETE from Service.main WHERE id = $id";
	if($conn->query($sql)){
		echo "<p>資料刪除成功</p>";
	}
	else{
		echo "<p>Fail</p>" ;
	}	
}

?>

