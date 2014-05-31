<?php
require_once("util.php");
require_once("configdb.php");
include('lock.php');
include('ChromePhp.php');

	ChromePhp::log('~Hello console!');
	ChromePhp::log($_SERVER);
	ChromePhp::warn('something went wrong!');


header('Content-Type: text/html; charset=utf8');
$conn->query("SET NAMES 'utf8'");
$conn->query("SET CHARACTER_SET_CLIENT='utf8'");
$conn->query("SET CHARACTER_SET_RESULTS='utf8'");

//check if there are parameters, show the search results
if(isset($_POST["a"]) && strlen($_POST["a"])>0
&& isset($_POST["f"])  && strlen($_POST["f"])>0
&& isset($_POST["v"])  && strlen($_POST["v"])>0) {
	$action = filter_var($_POST["a"], FILTER_SANITIZE_STRING);
	$field  = filter_var($_POST["f"] , FILTER_SANITIZE_STRING);
	$value  = filter_var($_POST["v"] , FILTER_SANITIZE_STRING);
	$sql = "select * from Service.main where $field like '$value%' order by id desc limit 10";
}
//if no parameters then select the latest 10 records
else{
	$sql = "select * from Service.main order by id desc limit 10";

}

$sqlFields = "SHOW COLUMNS FROM Service.main"; //return Field, Type
$sqlType = "select distinct type from Service.main";
$sqlStat = "select distinct stat from Service.main";
$sqlRowNum = "SELECT count(id) as total FROM Service.main";

if (!$rsAll = $conn->query($sql)) {
	errClose($rsAll, $conn);
}
if (!$rsFields = $conn->query($sqlFields)) {
	errClose($rsFields, $conn);
}
if (!$rsType = $conn->query($sqlType)) {
	errClose($rsType, $conn);
}
if (!$rsStat = $conn->query($sqlStat)) {
	errClose($rsStat, $conn);
}

if (!$rsRowNum = $conn->query($sqlRowNum)) {
	errClose($rsRowNum, $conn);
}


?>


<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/sunny/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="css/button.orange.css">


<script type="text/javascript" charset="utf8" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="js/jquery-ui-1.10.4.js"></script>
<script type="text/javascript" charset="utf8" src="js/servicecenter.js"></script>

<script >

var VIEWMODE = 1;
var EDITMODE = 2;
var ADDMODE = 3;

var mode = VIEWMODE;



$(document).ready(function() {
	var content = {
		a : 'all', //show all
		f : 'all',
		v : 'all',
		l : $('#txfLimit').val()
	}

	var table = $('#searchResultTable').DataTable( {
		processing: true,
		//serverSide: true, //remove / add won't work if enabled
	
		ajax: {
		url: 'getContactTable.php',
		type: 'POST',
		data: content
		},
		columns:[
		{"data": "id"},
		{"data": "name"},
		{"data": "tel1"},
		{"data": "tel2"},
		{"data": "mobile"},
		{"data": "stat"},
		{"data": "zip"},
		{"data": "addr"},
		{"data": "type"},
		{"data": "fm1"},
		{"data": "fm2"},
		{"data": "fm3"},
		{"data": "fm4"},
		{"data": "fm5"},
		{"data": "fm6"},
		{"data": "note"},
		{"data": "plp"}
		],
	
		scrollY: 150,
		scrollX: true,
		paging:   false,
		ordering: false,
		info:     false,
		jQueryUI: false
	} );
		

	$('#txfKeyword').bind("enterKey",function(e){
		fxnUpdateView(table,"keyword",$('#selField').find(":selected").text(), $(this).val(), $("#txfLimit").val());
	} );
	$('#txfKeyword').keyup(function(e){ if(e.keyCode == 13){$(this).trigger("enterKey");}	});

	$('#txfLimit').bind("enterKey",function(e){
		fxnUpdateView(table,"keyword",$('#selField').find(":selected").text(), $(this).val(), $("#txfLimit").val());
	} );
	$('#txfLimit').keyup(function(e){ if(e.keyCode == 13){$(this).trigger("enterKey");}	});


	$("#btnShowAll").click( function () {

	});

	setViewMode();

	$('#btnDel').hide();
	$('#btnEdit').hide();
	$('#btnSave').hide();
	$('#btnCancel').hide();

	$('#searchResultTable tbody').on( 'click', 'tr', function () {

		//show button when available records are clicked
		$('#btnEdit').show();
		$('#btnDel').show();
		$('#btnView').hide();
	
		table.$('tr.selected').removeClass('selected');
		$(this).addClass('selected');
		var p = table.row( this ).data();
			
		//jQuery
		$("#txfID").val(p['id']);
		$("#txfName").val(p['name']);
		$("#txfTel1").val(p['tel1']);
		$("#txfTel2").val(p['tel2']);
		$("#txfMobile").val(p['mobile']);
		$("#txfStat").val(p['stat']);
		$("#txfZIP").val(p['zip']);
		$("#txfAddr").val(p['addr']);
		$("#txfType").val(p['type']);
		$("#txfFm1").val(p['fm1']);
		$("#txfFm2").val(p['fm2']);
		$("#txfFm3").val(p['fm3']);
		$("#txfFm4").val(p['fm4']);
		$("#txfFm5").val(p['fm5']);
		$("#txfFm6").val(p['fm6']);
		$("#txfNote").val(p['note']);
		$("#selPlp").val(p['plp']);
	
		/*
		//for dataTable 1.9
		$("#txfID").val(table.fnGetData(this,0));
		$("#txfName").val(table.fnGetData(this,1));
		$("#txfTel1").val(table.fnGetData(this,2));
		$("#txfTel2").val(table.fnGetData(this,3));
		$("#txfMobile").val(table.fnGetData(this,4));
		$("#selStat").val(table.fnGetData(this,5));
		$("#txfZIP").val(table.fnGetData(this,6));
		$("#txfAddr").val(table.fnGetData(this,7));
		$("#txfType").val(table.fnGetData(this,8));
		$("#txfFm1").val(table.fnGetData(this,9));
		$("#txfFm2").val(table.fnGetData(this,10));
		$("#txfFm3").val(table.fnGetData(this,11));
		$("#txfFm4").val(table.fnGetData(this,12));
		$("#txfFm5").val(table.fnGetData(this,13));
		$("#txfFm6").val(table.fnGetData(this,14));
		$("#txfNote").val(table.fnGetData(this,15));
		$("#selPlp").val(table.fnGetData(this,16));
		*/
	} );

	$("#selType").change(function() {
  		$("#txfType").val($(this).find(":selected").text());
	});
	
	$("#selStat").change(function() {
  		$("#txfStat").val($(this).find(":selected").text());
	});
		
    $("#btnAdd").click( function () {
		setAddMode();
    } );	

    $("#btnEdit").click( function () {
		setEditMode();
    } );	

	$("#btnDel").click( function () {
		$("#confirmDel").html("<p>是否確定刪除此筆資料?</p>" + "<BR>ID: " + $("#txfID").val() + "<BR>" + "姓名: " + $("#txfName").val());
		$("#confirmDel").dialog("open");
	});
	
    $("#btnSave").click( function () {
		if (mode == EDITMODE){
			updateContact("update");
			setViewMode();
		}
		else if (mode == ADDMODE){
			$('#btnView').show(); //must be placed here 
			updateContact("add");
			setAddMode();
		}

    } );
	
	$("#btnView").click( function () {
		setViewMode();
    } );		

    $("#btnCancel").click( function () {
		setViewMode();
    } );


	//trigger Enter to search existing data
	$('#txfName').bind("enterKey",function(e){ 	fxnSearchField("find","name",$("#txfName").val());  });
	$('#txfName').keyup(function(e){ if(e.keyCode == 13){$(this).trigger("enterKey");}	});

	$('#txfTel1').bind("enterKey",function(e){ 	fxnSearchField("find","htel1",$("#txfTel1").val());  });
	$('#txfTel1').keyup(function(e){ if(e.keyCode == 13){$(this).trigger("enterKey");}	});
  
	$('#txfTel2').bind("enterKey",function(e){ 	fxnSearchField("find","htel2",$("#txfTel2").val());  });
	$('#txfTel2').keyup(function(e){ if(e.keyCode == 13){$(this).trigger("enterKey");}	});

	$('#txfMobile').bind("enterKey",function(e){ 	fxnSearchField("find","mobile",$("#txfMobile").val());  });
	$('#txfMobile').keyup(function(e){ if(e.keyCode == 13){$(this).trigger("enterKey");}	});

    $( "#message" ).dialog({
      autoOpen: false,
      height: 350,
	  width: 700,
      modal: true,
	  buttons: {
      "OK": function () {
        location.reload();
        return true;
      }}  
    });

    $( "#confirmDel" ).dialog({
      autoOpen: false,
      height: 300,
	  width: 400,
      modal: true,
	  buttons: {
		  "確認": function () {
			updateContact("del");
			return true;
		  },  
		  "取消": function () {
			$(this).dialog("close");
			return false;
		  }
	  }
    });
	
} );



function cleanContactFormFields(){
	$("#contactForm input[type=text]").each( function () {                
		$(this).prop("readonly",false);
		$(this).prop("value","");
	});
	$("#txfNote").prop("readonly",false);
	$("#txfNote").prop("value","");	
}

function setViewMode(){
	mode = VIEWMODE;
	cleanContactFormFields();
	//set readonly to fields of class fieldClass
	$("#searchResultDiv").slideDown();
	
	$("#contactForm input").each( function () {                
		$(this).prop("readonly",true);
	});
	 
	 
	$('#selType').hide();
	$('#selStat').hide();
	$('#btnSave').hide();
	$('#btnCancel').hide();	
	
	$('#btnView').hide();
	$('#btnAdd').show();

	$('#txfID').show();
	
}

function setEditMode(){
	mode = EDITMODE;
	$("#searchResultDiv").slideUp();
	//set readonly to fields of class fieldClass
	$('.fieldClass').each(function(i, obj) {
		obj.readOnly=false;
	});
	
	$('#selType').show();
	$('#selStat').show();
	$('#btnSave').show();
	$('#btnCancel').show();	
	$('#btnAdd').hide();
	$('#btnEdit').hide();
	$('#btnDel').hide();	
	$('#btnView').hide();
	
	$('#txfID').hide();
	$('#txfName').focus();
	
}

function setAddMode(){
	mode = ADDMODE;
	$("#searchResultDiv").slideUp();
	//set readonly to fields of class fieldClass
	
	cleanContactFormFields();
		
	$('#selType').show();
	$('#selStat').show();
	$('#btnSave').show();
	$('#btnCancel').show();	
	$('#btnAdd').hide();
	$('#btnEdit').hide();
	$('#btnDel').hide();	
	
	$('#txfID').hide();
	$('#txfName').focus();
	
}

function setFieldNormal(){

	//set readonly to fields of class fieldClass
	$('.fieldClass').each(function(i, obj) {
		obj.readOnly=false;
	});
	$('#selType').show();
	$('#selStat').show();
 	
}


function fxnUpdateView(table, action, field, value, limit){

		if (value.length == 0)
			return;

		var content = {
			"a" : action,
			"f" : field,
			"v" : value,
			"l" : limit
		}
		jQuery.ajax({
			type: "POST",
			url: "getContactTable.php",
			contenttype :"application/x-www-form-urlencoded;charset=utf-8", 
			dataType:"html", 
			data: content, 
			success: function(resp){
				table.clear().draw();
				var obj = JSON.parse (resp);
				var dataArr = obj['data']; //extract the array
				var contact;
					//CAN'T USE   var x in dataArr
					//for(var i=0; i< arr.length; i++){
					dataArr.forEach(function(arr){
					contact = new Contact(arr.id, arr.name, arr.tel1, arr.tel2, arr.mobile, arr.stat, 
									arr.zip, arr.addr, arr.type, 
									arr.fm1, arr.fm2, arr.fm3, arr.fm4, arr.fm5, arr.fm6, 
									arr.note, arr.plp);
					table.row.add(contact);
					table.draw();
					/*
					table.row.add(dataArr[i].id,
								  dataArr[i].name,
								  dataArr[i].tel1,
								  dataArr[i].tel2,
								  dataArr[i].mobile,
								  dataArr[i].stat,
								  dataArr[i].zip,
								  dataArr[i].addr,
								  dataArr[i].type,
								  dataArr[i].fm1,
								  dataArr[i].fm2,
								  dataArr[i].fm3,
								  dataArr[i].fm4,
								  dataArr[i].fm5,
								  dataArr[i].fm6,
								  dataArr[i].note,
								  dataArr[i].plp);
					*/			  
				});
				//table.draw();
				
			},
			error: function(xhr, ajaxOptions, error){
				alert(error);
				return false;
			}
		});	
				
			
}
function fxnSearchField(action, field, value){
		if (value.length == 0)
			return;
			
		var content = {
			"a" : action,
			"f" : field,
			"v" : value
		}	
		jQuery.ajax({
			type: "POST",
			url: "searchField.php",
			contenttype :"application/x-www-form-urlencoded;charset=utf-8", 
			dataType:"html", 
			data: content, 
			success: function(resp){
			  $("#message").html(resp);
			  $("#message").dialog("open");
			},
			error: function(xhr, ajaxOptions, error){
				alert(error);
			}
		});			
}

function readContactForm(){
	var content = {
		"id": $("#txfID").val(),
		"name": $("#txfName").val(),
		"tel1": $("#txfTel1").val(),
		"tel2": $("#txfTel2").val(),
		"mobile" : $("#txfMobile").val(),
		"stat" : $("#txfStat").val(),
		"addr" : $("#txfAddr").val(),
		"zip" : $("#txfZIP").val(),
		"type" : $("#txfType").val(),
		"fm1" : $("#txfFm1").val(),
		"fm2" : $("#txfFm2").val(),
		"fm3" : $("#txfFm3").val(),
		"fm4" : $("#txfFm4").val(),
		"fm5" : $("#txfFm5").val(),
		"fm6" : $("#txfFm6").val(),
		"note" :$("#txfNote").val(),
		"plp": $("#selPlp").val()
	}
		
	return content;
}

function updateContact(action){
		var content = readContactForm();
		content["a"] = action;
		
		  jQuery.ajax({
			  type: "POST",
			  url: "updateContact.php",
			  contenttype :"application/x-www-form-urlencoded;charset=utf-8", 
			  dataType:"html", 
			  data: content, 
			  success: function(resp){
				  $("#message").html(resp);
				  $("#message").dialog("open");
			  },
			  error: function(xhr, ajaxOptions, error){
				  $("#message").dialog("open");
			  }
		  });
			
}
	

</script>


<style type="text/css">
.searchDiv {
	width: 800px;
	height: 30px;
	border-radius: 10px;
	background-color: #CFA5EF;
	
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	float: left;
}
.searchResultDiv {
	height: 200px;
	width: 800px;
}

th, td {
	white-space: nowrap;
	text-align: left;
}

div.dataTables_wrapper {
	margin: 0 auto;
}

.contentView {
	width: 800px;
	margin-top: 50px;
	margin-bottom: 20px;
	border-radius: 10px;
	background-color: #CFA5EF;
	
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	float: left;
}

input.fieldClass, textarea.fieldClass {
	border: 0;
	border-radius: 3px;
	-webkit-appearance: none;
	font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;

}

body {
	margin-left: 30px;
	margin-right: 30px;
}

</style>
<body>
<p>Welcome User:<?php echo $login_session; ?></p>

<div id="searchDiv" class="searchDiv">
    <table width="100%" border="0" >
    <tr height="30px">
    <td valign="middle">搜尋欄位
          <select name="selField"  class="fieldClassSearch" id="selField">
          <?php
            while ( $row = $rsFields->fetch_array(MYSQLI_ASSOC) ) {
                echo "<option value="  . $row['Field']. ">"  . $row['Field']. "</option>";
            }
          ?>
        </select> 
        <script>$("#selField").val("htel1");</script>
        &nbsp;&nbsp;&nbsp;關鍵字 
        <input class="fieldClassSearch" name="txfKeyword" type="text"  id="txfKeyword">
        &nbsp;&nbsp;
      	顯示<input class="fieldClassSearch" name="txfLimit" type="text"  id="txfLimit" value="20" size="3">筆 
        &nbsp;&nbsp;&nbsp;
        <input type="button" id="btnShowAll" value="顯示全部" >
        &nbsp;&nbsp;&nbsp;
        <label class="fieldClassSearch" >資料總筆數:</label>
        <label class="fieldClassSearch" id="lblTotalRowNum">
			<?php
	            while ( $row = $rsRowNum->fetch_array(MYSQLI_ASSOC) ) {
	                echo $row['total'];
	            }
			?>
        </label>
    </td>
    </tr>
	</table>
</div>
<br>

<div id="searchResultDiv" class="searchResultDiv">
<table width="100%" border="1" cellspacing="0" class="display" id="searchResultTable">
    <thead>
      <tr>
          <th>ID</th>
          <th>姓名</th>
          <th>電話1</th>
          <th>電話2</th>
          <th>行動</th>
          <th>狀態</th>
          <th>區碼</th>
          <th>地址</th>
          <th>群組</th>
          <th>成員1</th>
          <th>成員2</th>
          <th>成員3</th>
          <th>成員4</th>
          <th>成員5</th>
          <th>成員6</th>
          <th>備註</th>
          <th>人口</th>
      </tr>
    </thead>
<tbody>

<?php
/*
		echo "<tbody>";
		while ( $row = $rsAll->fetch_array(MYSQLI_ASSOC) ) {
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
        }
		echo "</tbody>";
		
*/
?>

</tbody>
	</table>
	   
</div>


<p></p>
<div class="contentView">
  <form id="contactForm">
  <table width="300" border="0" cellpadding="5" >

  <tr>
  <td >&nbsp;</td>
  <td >
  	<input class="buttonOrange" type="button" name="btnView" id="btnView" value="回瀏覽" >
    <input class="buttonOrange" type="button" name="btnAdd" id="btnAdd" value="新增一筆" >
    <input class="buttonOrange" type="button" name="btnEdit" id="btnEdit" value="修改此筆" >
    <input class="buttonOrange" type="button" name="btnDel" id="btnDel" value="刪除" >
    <input class="buttonOrange" type="button" name="btnSave" id="btnSave" value="儲存" >
    <input class="buttonOrange" type="button" name="btnCancel" id="btnCancel" value="取消" >
   </td>

  </tr>
  
    <tr>
      <td ><label for="txfID">ID:</label></td>
      <td><input class="fieldClass" name="txfID" type="text"  id="txfID"></td>
    </tr>
    <tr>
      <td >姓名</td>
      <td><input name="txfName" type="text" required="required" class="fieldClass" id="txfName"></td>
    </tr>
    <tr>
      <td >電話1</td>
      <td><input class="fieldClass" type="text" name="txfTel1" id="txfTel1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;電話2
      <input class="fieldClass" type="text" name="txfTel2" id="txfTel2"> </td>
    </tr>
    <tr>
      <td >手機</td>
      <td><input class="fieldClass" type="text" name="txfMobile" id="txfMobile"></td>
    </tr>
    <tr>
      <td >地址</td>
      <td>
      <input name="txfAddr" type="text" class="fieldClass" id="txfAddr" size="80"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;區碼<input class="fieldClass" name="txfZIP" type="text" id="txfZIP" size="6" maxlength="4"></td>
    </tr>
    <tr>
      <td >群組</td>
      <td><input class="fieldClass" type="text" name="txfType" id="txfType">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select  class="fieldClass" name="selType" id="selType">
		<?php
        while ( $row = $rsType->fetch_array(MYSQLI_ASSOC) ) {
            echo "<option value="  . $row['type']. ">"  . $row['type']. "</option>";
        }
        ?>
      </select></td>
    </tr>
    <tr>
      <td >狀態</td>
      <td><input class="fieldClass" type="text" name="txfStat" id="txfStat">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select class="fieldClass" name="selStat" id="selStat">
		<?php
        while ( $row = $rsStat->fetch_array(MYSQLI_ASSOC) ) {
            echo "<option value="  . $row['stat']. ">"  . $row['stat']. "</option>";
        }
        ?>
      </select></td>
    </tr>
    <tr>
      <td >家庭成員</td>
      <td><p>
        <input class="fieldClass" type="text" name="txfFm1" id="txfFm1"> 
        <input class="fieldClass" type="text" name="txfFm2" id="txfFm2">
        <input class="fieldClass" type="text" name="txfFm3" id="txfFm3">
      </p>
      <p>
        <input class="fieldClass" type="text" name="txfFm4" id="txfFm4">
        <input class="fieldClass" type="text" name="txfFm5" id="txfFm5">
        <input class="fieldClass" type="text" name="txfFm6" id="txfFm6">
      </p></td>
    </tr>
    <tr>
      <td >備註</td>
      <td><textarea class="fieldClass" name="txfNote" id="txfNote" cols="60" rows="4"></textarea></td>
    </tr>
    <tr>
      <td >人口</td>
      <td><label for="select">Select:</label>
        <select class="fieldClass" name="selPlp" id="selPlp">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
      </select></td>
    </tr>
  </table>
  </form>
  <p></p>
</div>


<div id="message" title="訊息">

</div>

<div id="confirmDel" title="確認">

</div>


</body>
</html>
