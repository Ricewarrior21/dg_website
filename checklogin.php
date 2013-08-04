<html>
<body>

<?php
$host = "localhost";
$user = "jhansel1";
$pass = "jhansel1";
$con = mysql_connect($host, $user, $pass);
if (!$con) {
	echo "Could not connect to server!\n";
	trigger_error(mysql_error(), E_USER_ERROR);
}

$db_name = masterdb;

$r1 = mysql_select_db($db_name);

$tbl_name = "users";

$inputuser = $_POST["name"];
$inputpass = $_POST["password"];

$sql = "SELECT * FROM $tbl_name WHERE username = '$inputuser' and password = '$inputpass'";
$r2 = mysql_query($sql);

$count = mysql_num_rows($r2);

if ($count == 1) {
session_start();
$_SESSION['username'] = $inputuser;
$_SESSION['password'] = $inputpass;
$_SESSION['home_status'] = "home";
header("location:home.php?home=home");
} else {
echo "Wrong Username or Password";
header('Refresh: 1; url=index.php');
}

mysql_close();
?>
</body>
</html>
