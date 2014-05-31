<?php
include_once("util.php");
include_once("configdb.php");

header('Content-Type: text/html; charset=utf-8');
$conn->query("SET NAMES 'utf8'");
$conn->query("SET CHARACTER_SET_CLIENT='utf8'");
$conn->query("SET CHARACTER_SET_RESULTS='utf8'");

if(isset($_POST["a"]) && strlen($_POST["a"])>0
&& isset($_POST["f"])  && strlen($_POST["f"])>0
&& isset($_POST["v"])  && strlen($_POST["v"])>0
&& isset($_POST["l"])  && strlen($_POST["l"])>0
) {
	$action = filter_var($_POST["a"], FILTER_SANITIZE_STRING);
	$field  = filter_var($_POST["f"] , FILTER_SANITIZE_STRING);
	$value  = filter_var($_POST["v"] , FILTER_SANITIZE_STRING);
	$limit  = filter_var($_POST["l"] , FILTER_SANITIZE_STRING);
	
}else{
	echo "HTTP 1.1 500 Error Occur: no action is defined.";
	exit();
}

if ($action == 'keyword'){
	//MySQLi query
	if ($field == "htel1" || $field == "htel2"){
		$sql = "SELECT * FROM Service.main where htel1 LIKE '"  . $value . "%' OR htel2 LIKE '"  . $value . "%' order by id desc limit " . $limit;
	}
	else{
		$sql = "SELECT * FROM Service.main where " . $field . " LIKE '"  . $value . "%'  order by id desc limit " . $limit;
	}
}
else{ //default initial page
	$sql = "SELECT * FROM Service.main order by id desc  limit " . $limit;
}

if (!$rs = $conn->query($sql)) {
	errClose($rs, $conn);
	exit();
}

if($rs->num_rows == 0) { 
	echo "查無類似資料";
	$rs->close(); exit();
}
		while ( $row = $rs->fetch_array(MYSQLI_ASSOC) ) {
			/*
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['htel1'] . "</td>";
            echo "<td>" . $row['htel2'] . "</td>";
            echo "<td>" . $row['mobile'] . "</td>";
            echo "<td>" . $row['stat'] . "</td>";
            echo "<td>" . $row['zip'] . "</td>";
            echo "<td>" . $row['addr'] . "</td>";
            echo "<td>" . $row['type'] . "</td>";
            echo "<td>" . $row['fm1'] . "</td>";
            echo "<td>" . $row['fm2'] . "</td>";
            echo "<td>" . $row['fm3'] . "</td>";
            echo "<td>" . $row['fm4'] . "</td>";
            echo "<td>" . $row['fm5'] . "</td>";
            echo "<td>" . $row['fm6'] . "</td>";
            echo "<td>" . $row['note'] . "</td>";
            echo "<td>" . $row['plp'] . "</td>";
            echo "</tr>";
			*/
			$data[] = array(
							"id" => $row['id'],
							"name" => $row['name'],
							"tel1" => $row['htel1'],
							"tel2" => $row['htel2'],
							"mobile" => $row['mobile'],
							"stat" => $row['stat'],
							"zip" => $row['zip'],
							"addr" => $row['addr'],
							"type" => $row['type'],
							"fm1" => $row['fm1'],
							"fm2" => $row['fm2'],
							"fm3" => $row['fm3'],
							"fm4" => $row['fm4'],
							"fm5" => $row['fm5'],
							"fm6" => $row['fm6'],
							"note" => $row['note'],
							"plp" => $row['plp']);
		
			//$dataArray[] = $data;//insert each data row into an assembled array
	
        }
		$arr["data"] = $data;
		echo json_encode($arr);		

	
//close db connection
okClose($rs, $conn);
?>

