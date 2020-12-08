<?php
session_start(); //start session.

$tag = $_SESSION['tag'];
if(!isset($_SESSION['user']))
{
    // not logged in
    header('Location: ecalc_login.php');
    exit();
}
?>

<?php 
if($tag == 'rpt'){ goto rpt;}
if($tag == 'addExp') { goto addExp;}
?>

<?php rpt: ?>
<!DOCTYPE html>
<html>
<head>
 <link href="dhtml/css/ecalc_main.css" type="text/css" rel="stylesheet">
 <title> سافتویرمحاسب</title>
 <link href="dhtml/css/ecalc.login.css" type="text/css" rel="stylesheet">
 <script src="jQuery/js/jquery-1.11.2.min.js"></script>
 <script src="jQuery/js/jquery-ui-1.11.2.min.js"></script>
 <script src="dhtml/js/ecalc_main.js"></script>
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
	 });
 });
 </script>
</head>

<body>
	 
<div class="addExp">   
  <div>
  <form method="post" action="ecalc_main.php" onsubmit="return execAddRec();">
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
	  <td><input type="text" id="txtDate" value="" size="10"></td>
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
  </form>
 </div>
</div>

</body>
</html>
<?php exit(); ?>

<?php 
addExp: 
 $insQ = "insert into homespend(ID_NO,THINGS_NAME,DESCRIPTION,CATEGORY,PRICE,NUMBER_OF,TOTAL_PRICE,SPEND_BY,INSERTED_BY,INSERT_DATE)" .
         "values(1,'".$_POST['aname']."','".$_POST['desc']."','".$_POST['type']."',".$_POST['price'].",".$_POST['numof'].",".
		 $_POST['total'].",'".$_POST['spendby']."','".$_POST['sdate']."');";
 $dbclas = new dbHandle();
 $execQ = $dbclas -> excuteQuery($insQ);
?>
<div id="messages"></div>

