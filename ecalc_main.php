<?php
session_start(); //start session.

if(!isset($_SESSION['user']))
{
    // not logged in
    header('Location: ecalc_login.php');
    exit();
}
?>

<?php 
$tag;
if(!isset($_POST['tag'])){
	$tag = $_SESSION['user'];
}
else{
	$tag = $_POST['tag'];
}
if( $tag == 'rpt'){ goto rpt;}
else if( $tag == 'addExp') { goto addExp;}
else if( $tag == 'addNGrp') { goto addNGrp;}
else if( $tag == 'insCtg') { goto insCtg;}
?>

<?php rpt: ?>
<!DOCTYPE html>
<html>
<head>
<?php include 'headFile.php'; ?>
 <script>
 $(document).ready(function() {
	 $("#LogOut").click(function() {
		 document.location = 'ecalc_logout.php';
		 
	 });
	 $('#eclear').click(function() {
		 clearTxtBoxs();
	 });
	 $('#txtNumOf').keyup(function() {
		 $('#txtTotal').val($('#txtPrice').val() * $('#txtNumOf').val());
	 });
	 $('#txtPrice').keyup(function() {
		 $('#txtTotal').val($('#txtPrice').val() * $('#txtNumOf').val());
	 });
	 //handle the click of add records
	 $('#eSave').click(function() {
		
		 execAddRec();
		 clearTxtBoxs();
	 });
	 $( "#txtDate" ).datepicker({ minDate: -20, maxDate: "+1M +15D", dateFormat: "yy/m/d" });
	 
	 //on changed of selType
	 $('#selType').change(function() {
		 
		 if($('#selType').val() == '999'){
			 showPrompt();
		 }
		 else{return false;}
		 
	 });
 });
 
 </script>
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 
</head>

<body>
		 
<div class="container" style = "border:1px solid red;">
	<div id="messages"></div>
		<h2>ازدیاد جنس</h2>
		<div id="buttons" class="top left">
			<div id="LogOut">خروج</div>
			<div id="eclear">پاک</div>
			<div id="eSave">ذخیره</div>
		</div>
        <form>
		<div class="form-group">
   			<label for="txtAName">اسم جنس</label>
    		<input type="text" class="form-control" id="txtAName" placeholder="اسم جنس را وارد کنید">
    		
  		</div>
  		<div class="form-group">
   			 <label for="arDesc">توضیحات</label>
    		<input type="text" class="form-control" id="arDesc" placeholder="توضیحات را در مود جنس بنویسید">
  		</div>
  		<div class="form-group">
		     <label for="selType">نوع جنس</label>
			 <select class="form-control" id="selType">
				<option value="-1">لطفاَ یک نوع جنس را انتخاب کنید</option>
				<option value="999">ازدیاد نوع جنس....</option>
			</select>
  		</div>
		  <div class="form-group">
   			 <label for="txtPrice">قیمت</label>
    		<input type="text" class="form-control" id="txtPrice" >
		  </div>
		  <div class="form-group">
   			 <label for="txtNumOf">تعداد</label>
    		<input type="text" class="form-control" id="txtNumOf" >
		  </div>
		  <div class="form-group">
   			 <label for="txtTotal">قیمت مجموعی</label>
    		<input type="text" class="form-control" id="txtTotal" >
		  </div>
		  <div class="form-group">
   			 <label for="txtSpendBy">مصرف توسط</label>
    		<input type="text" class="form-control" id="txtSpendBy" >
		  </div>
		  <div class="form-group">
   			 <label for="txtDate">تاریخ</label>
    		<input type="text" class="form-control" id="txtDate" value="<?php echo date('yy/m/d'); ?>" >
		  </div>
		  
		  <button id="eSave"  class="btn btn-primary">‌ذخیره</button>
		</form>

		
</div>
	<div class="container" style = "border:1px solid red;">

	

	<?php

		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "ecalc";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT * FROM homespend";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			echo "<table class='table'><tr><th>ID</th><th>Name</th><th>Description</th><th>Category</th><th>Price</th><th>Number of</th><th>Total Price</th><th>Spend By</th></tr>";
			// output data of each row
			while($row = $result->fetch_assoc()) {
			echo "<tr><td>".$row["ID_NO"]."</td><td>". $row["THINGS_NAME"]."</td><td>". $row["DESCRIPTION"]."</td><td>". $row["CATEGORY"]."</td><td>". $row["PRICE"]."</td><td>". $row["NUMBER_OF"]."</td><td>". $row["TOTAL_PRICE"]. "</td><td>".$row["SPEND_BY"]."</td></tr>";
			}
			echo "</table>";
		} else {
			echo "0 results";
		}
		

	?>


</div>
</body>
</html>
<?php exit(); ?>

<?php 
addExp: 
include "ecalc_db.php";
// connect to database
 $dbclas = new dbHandle();
 $execQ = $dbclas -> dbConnect();
//get maximum id
 $maxQuery = "SELECT MAX(ID_NO) as ID_NO FROM HOMESPEND";
 //Connect to database
 $execQ = $dbclas -> selectFreeRun($maxQuery);
 $nextID=0;
 while ($row = $execQ->fetch_assoc()) {
    $nextID = $row['ID_NO'] + 1;
}

 // query to insert the data from from
 $tblName = 'HOMESPEND';
 $tblFields = 'ID_NO, THINGS_NAME, DESCRIPTION, CATEGORY, PRICE, NUMBER_OF, TOTAL_PRICE, SPEND_BY, INSERTED_BY, INSERT_DATE';
 $valOfInsrt = "(".$nextID.",'".$_POST['aname']."','".$_POST['desc']."','".$_POST['type']."',".$_POST['price'].",".$_POST['numof'].",".
		 $_POST['total'].",'".$_POST['spendby']."','".$_SESSION['user']."','".$_POST['sdate']."')";
		 
 //$execQ = $dbclas -> dbConnect();
 $execQ = $dbclas -> insertInto($tblName, $tblFields, $valOfInsrt);
 echo $execQ;
 $execQ = $dbclas -> dbDisconnect();
 exit();
?>

<?php addNGrp: ?>
<script>
  $(document).ready(function() {
	  $( "#txtDateCtg" ).datepicker({ minDate: -20, maxDate: "+1M +15D", dateFormat: "yy/m/d" });
	  //handle the click of add records
	  $('#eSaveCtg').click(function() { addCtg(); });
  });
</script>
<div dir="rtl">
 <table>
  <tr>
   <td>نام کتگوری:</td><td><input type="text" id="txtCtg" value=""></td>
  </tr>
  <tr>
   <td>تاریخ:</td><td><input type="text" id="txtDateCtg" value="<?php echo date('yy/m/d'); ?>" size="10"></td>
  </tr>
  <tr><td colspan="2"><div id="buttons" dir="ltr"><div id="eSaveCtg">ذخیره</div></div></td></tr>
 </table>
</div>
<?php 
exit(); 

//
insCtg:
include "ecalc_db.php";
// connect to database
 $dbclas = new dbHandle();
 $execQ = $dbclas -> dbConnect();
 // query to insert the data from from
 $tblName = 'CATEGORY';
 $tblFields = 'CATEGORY, INSERTED_BY, INSERTED_DATE';
 $valOfInsrt = "('".$_POST['ctgName']."','".$_SESSION['user']."','".$_POST['sdate']."')";
		 
 //$execQ = $dbclas -> dbConnect();
 $execQ = $dbclas -> insertInto($tblName, $tblFields, $valOfInsrt);
 echo $execQ;
 $execQ = $dbclas -> dbDisconnect();
 exit();
?>




