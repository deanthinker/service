<?php 
include_once("util.php");
include_once("configdb.php");
$error = "";
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // username and password sent from Form
    $myusername=addslashes($_POST['txfun']);
    $mypassword=addslashes($_POST['txfpw']);
    $husername = hash("sha1",$myusername);
	$hpassword = hash("sha1",$mypassword); 	
	 
    $sql="SELECT id, name FROM Service.user WHERE un='$husername' and pw='$hpassword'";

	if (!$rs = $conn->query($sql)) {
		errClose($rs, $conn);
	}

    if($rs->num_rows >0)
    {
		/*
		if( isset($_SESSION['login_user']) )
    		echo "session id:'$active' is exist"	;

		*/
		$_SESSION['login_user'] = $husername;
			 
        header("location: viewMain.php");
    
    }
    else
    {
		echo $sql . "<BR>";
		echo "count: " . $rs->num_rows . "<BR>";
		echo $myusername . " = " . $husername . "<BR>";
		echo $mypassword . " = " . $hpassword . "<BR>";
        $error="Your user name or password is incorrect!";
		
    }

}



?>
<script type="text/javascript" charset="utf8" src="js/jquery-1.11.1.js"></script>
<script>

$(document).ready(function() {
	$('#txfun').focus();
});

</script>

<!doctype html>
<html>
<link rel="stylesheet" type="text/css" href="css/button.orange.css">

<head>
<meta charset="utf-8">
<title>Service Center Login</title>
<style type="text/css">
body {
	margin-left: 20px;
	margin-top: 60px;
	margin-right: 10px;
	margin-bottom: 10px;
	-webkit-box-shadow: 0px 0px #6D6D6D;
	box-shadow: 0px 0px #6D6D6D;
	font-family: Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-size: large;
}
.login {
	padding: 0px;
	height: 280px;
	width: 400px;
	margin-top: 10px;
	margin-right: auto;
	margin-bottom: 10px;
	margin-left: auto;
	text-align: center;
	border-radius: 20px;
	-webkit-box-shadow: 0px 0px 5px 3px #828282;
	box-shadow: 0px 0px 5px 3px #828282;
	background-color: #CFA5EF;
}
.errormessage {
	color: #FF0004;
}

input {
	border: 0;
	border-radius: 3px;
	-webkit-appearance: none;
	font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;
	font-size: large;
}


</style>
</head>

<body>
<div class="login">
  <form id="form1" name="form1" method="post">
    <table id="logintable" width="380" border="0" cellpadding="10" cellspacing="0">
      <tr style="font-weight: normal; font-size: large;">
        <td height="40" colspan="2" align="center" valign="bottom" >User Authentication</td>
      </tr>
      <tr>
        <td width="148" style="text-align: right"><label for="txfuname">User Name:</label></td>
        <td width="177" align="left"><input type="test" name="txfun" id="txfun" required></td>
      </tr>
      <tr >
        <td style="text-align: right"><label for="password">Password:</label></td>
        <td align="left"><input type="password" name="txfpw" id="txfun" required></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center"><span class="errormessage"><?php echo $error; ?></span>          </td>
      </tr>
      <tr style="text-align: center">
        <td colspan="2">
        <input class="buttonOrange" type="submit" name="buttonOrange" id="buttonOrange" value="Login">  </td>
      </tr>
    </table>
  </form>
</div>
</body>



</html>
