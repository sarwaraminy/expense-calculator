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
</head>

<body>
<div id="messages"></div>	 
<div class="addExp">   
  <div>
  <table id="addItem">
    <tr><td><h2>ازدیاد جنس</h2></td><td colspan="12"><div id="buttons" class="top left"><div id="LogOut">خروج</div></div></td></tr>
    <tr><td>اسم جنس</><td>توضیحات</td><td>نوع جنس</td><td>قیمت</td><td>تعداد جنس</td><td>قیمت مجموعی</td><td>مصرف توسط</td><td>تاریخ</td><td></td></tr>
	<tr>
	  <td><input type="text" id="txtAName" value="" size="10"></td>
	  <td><textarea id="arDesc" rows="4" cols="15"></textarea></td>
	  <td>
	   <select id="selType">
	     <option value="-1">لطفاَ یک نوع جنس را انتخاب کنید</option>
	     <option value="999">ازدیاد نوع جنس....</option>
	   </select>
	  </td>
	  <td><input type="text" id="txtPrice" value="1" size="7" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
	  <td><input type="text" id="txtNumOf" value="1" size="7" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
	  <td><input type="text" id="txtTotal" value="" size="7" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
	  <td><input type="text" id="txtSpendBy" value="" size="10"></td>
	  <td><input type="text" id="txtDate" value="<?php echo date('yy/m/d'); ?>" size="10"></td>
	  <td>
	   <div id="buttons" dir="ltr">
        <div id="eclear">پاک</div>
        <div id="eSave">ذخیره</div>
        </div>
	  </td>
	</tr>
	<tr><td colspan="10"><hr></td></tr>
	<tr><td><h2>راپور مصارف</h2></td></tr>
  </table>
 </div>
</div>
<div id="addNItem"></div>
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




