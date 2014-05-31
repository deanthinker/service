<?php 
function errClose($thisrs, $thisconn){
	die('There was an error running the query [' . $thisconn->error . ']');
	$thisrs->close();
	$thisconn->close();
}

function okClose($thisrs, $thisconn){
	$thisrs->close();
	$thisconn->close();
}
?>