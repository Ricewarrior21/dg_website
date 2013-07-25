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
} else {
	echo mysql_get_server_info() . "\n";
}

$r2 = mysql_select_db(masterdb);

if (!$r2) {
    echo "Cannot select database";
    echo "<br>";
    trigger_error(mysql_error(), E_USER_ERROR); 
} else {
    echo "Database selected";
    echo "<br>";
}

$user = $_POST["name"];
$pass = $_POST["password"];
$query = "INSERT INTO user VALUES('NULL', '$user', '$pass','NULL')";
$rs = mysql_query($query);

if (!$rs) {
    echo "Could not execute query: $query";
    echo "<br>";
    echo "The username you have chosen is already taken.";
    trigger_error(mysql_error(), E_USER_ERROR);
} else {
	$count_query = mysql_query("SELECT * FROM user",$con);
	$count = mysql_num_rows($count_query);
	$id_query = "UPDATE user set id = '$count' WHERE username = '$user'";
	$id = mysql_query($id_query);
	if (!$id) {
		echo "Could not execute query: $id_query";
		echo "<br>";
		echo "Failed to set user id";
		trigger_error(mysql_error(), E_USER_ERROR);
	} else {
		echo "User id successfully created";
		echo "<br>";
	}
    echo "Query: $query executed"; 
}

mysql_close();
?>
<br>
You have registered as <?php echo $_POST["name"]; ?>!<br>
Your password is <?php echo $_POST["password"]; ?>.<br>
</body>
</html>