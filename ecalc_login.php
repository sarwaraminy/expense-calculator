
<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=11,IE=10;">
  <title>سافتویر محاسب</title>
  <link rel="icon" type="image/png" href="images/favicon.png?v=1">
  <link href="jQuery/css/custom-theme/jquery-ui-1.11.4.smoothness.css" type="text/css" rel="stylesheet">
  <link href="dhtml/css/ecalc.login.css" type="text/css" rel="stylesheet">
  <script src="jQuery/js/jquery-1.11.2.min.js"></script>
  <script src="jQuery/js/jquery-ui-1.11.2.min.js"></script>
  <script src="dhtml/js/ecalc.login.js"></script>
</head>
<body onload="$('#user').focus()">
<div id="login-wrapper" dir="rtl">
  <div id="logo"><img src="images/logo_ecalc.jpg"></div>
  <div id="input-wrapper">
    <div id="messages"></div>
    <div id="user-wrapper">
	<form method="post" action="do_login.php" onsubmit="return do_login();">
      <label for="user">نام استفاده کننده:</label>
      <input autocomplete="off" name="user" id="user" type="text">
    </div>
    <div id="pass-wrapper">
      <label for="pass">رمز عبور:</label>
      <input autocomplete="off" name="pass" id="pass" type="password">
    </div>

    <div id="buttons" dir="ltr">
      <div id="clear">پاک</div>
      <div id="login">ورود</div>
    </div>
  </div>
  <div id="powered-by"><span>سپانسر توسط </span><a href="#"><b>Sarwar Amini</b></a></div>
</div>
</form>
</body>
</html>

