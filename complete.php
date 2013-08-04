<html>
<body>

<?php
$host = "localhost";
$user = "jhansel1";
$pass = "jhansel1";
$con = mysql_connect($host, $user, $pass);
if (!$con) {
	echo "Could not connect to server! <br>";
	trigger_error(mysql_error(), E_USER_ERROR);
}

$r2 = mysql_select_db(masterdb);

if (!$r2) {
    echo "Cannot select database";
    echo "<br>";
    trigger_error(mysql_error(), E_USER_ERROR); 
}

// Standard SQL Query function
function mySQLQuery($input) {
	$result2 = mysql_query($input);
	
	if (!$result2) {
		echo "The username is already registered!";
		echo "<br>";
		header('Refresh: 1; url=register.php');
		trigger_error(mysql_error(), E_USER_ERROR);
	} else {
		return $result2;
	}
}

$user = $_POST["name"];
$pass = $_POST["password"];
$joined = date('Y-m-d H:i:s');
$query = "INSERT INTO users VALUES('', '$user', '$pass', '', '$joined')";

mySQLQuery($query);

mysql_close();

$name = $_POST['name'];
echo "You have registered as $name!<br>";
header('Refresh: 1; url=index.php');
?>
</body>
</html>