<?php
include_once("util.php");
include_once("configdb.php");

header('Content-Type: text/html; charset=utf-8');

if(isset($_POST["a"]) && strlen($_POST["a"])>0
&& isset($_POST["f"])  && strlen($_POST["f"])>0
&& isset($_POST["v"])  && strlen($_POST["v"])>0) {
	$action = filter_var($_POST["a"], FILTER_SANITIZE_STRING);
	$field  = filter_var($_POST["f"] , FILTER_SANITIZE_STRING);
	$value  = filter_var($_POST["v"] , FILTER_SANITIZE_STRING);
	//$action = $_POST["a"];
	//$field = $_POST["f"];
	//$value = $_POST["v"];

	
}else{
	echo "HTTP 1.1 500 Error Occur: no action is defined.";
	exit();
}

//MySQLi query
if ($field == "htel1" || $field == "htel2"){
	$sql = "SELECT * FROM Service.main where htel1 LIKE '"  . $value . "%' OR htel2 LIKE '"  . $value . "%' order by id limit 5";
}
else{
	$sql = "SELECT * FROM Service.main where " . $field . " LIKE '"  . $value . "%'  order by id desc limit 5";
}
	
//$sql = "SELECT * FROM Service.main where name like '何%' limit 5";

//mysql_query("SET NAMES 'UTF8'");

if (!$rs = $conn->query($sql)) {
	errClose($rs, $conn);
	exit();
}

if($rs->num_rows == 0) { 
	echo "查無類似資料";
	$rs->close(); exit();
}

echo "發現類似資料:<BR>";

echo '<table width="100%" border="1" id="searchResultTable">';
echo '<thead>';
echo '<tr>';
echo '<th>ID</th><th>姓名</th><th>電話1</th><th>電話2</th><th>行動</th><th>地址</th><th>群組</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

while ( $row = $rs->fetch_array(MYSQLI_ASSOC) ) {
	echo "<tr>";
	echo "<td>" . $row['id'] . "</td>";
	echo "<td>" . $row['name'] . "</td>";
	echo "<td>" . $row['htel1'] . "</td>";
	echo "<td>" . $row['htel2'] . "</td>";
	echo "<td>" . $row['mobile'] . "</td>";
	echo "<td>" . $row['addr'] . "</td>";
	echo "<td>" . $row['type'] . "</td>";
	echo "</tr>";
}
echo '</tbody>';
echo '</table>';
echo '<P>按下ESC關閉此訊息</P>';

//close db connection
okClose($rs, $conn);
?>

