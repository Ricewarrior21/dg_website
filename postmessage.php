<?php 
	session_start();

	$host = "localhost";
	$user = "jhansel1";
	$pass = "jhansel1";
	$db_name = "masterdb";
	
	$con = mysql_connect($host, $user, $pass);
	if (!$con) {
		echo "Could not connect to server!";
		echo "<br>";
		trigger_error(mysql_error(), E_USER_ERROR);
	}

	$result1 = mysql_select_db($db_name);
	
	if (!$result1) {
		echo "Could not select database!";
		echo "<br>";
		trigger_error(mysql_error(), E_USER_ERROR);
	}	

	// Standard SQL Query function
	function mySQLQuery($input) {
		$result2 = mysql_query($input);
		
		if (!$result2) {
			echo "Could not execute query: $input";
			echo "<br>";
			trigger_error(mysql_error(), E_USER_ERROR);
		} else {
			return $result2;
		}
	}
	$r = mysql_real_escape_string($_POST['receiver_list']);
	
	$rquery = "SELECT id FROM users WHERE username='$r'";
	$result = mySQLQuery($rquery);
	$receiver = mysql_fetch_row($result)[0];
	$sender = $_SESSION['userid'];
	$title = mysql_real_escape_string($_POST['messagetitle']);
	$message = mysql_real_escape_string($_POST['message']);
	$sent = date('Y-m-d H:i:s');
	
	$query = "INSERT INTO messages VALUES('', '$sender', '$receiver', '$title', '$message', '$sent')";
	echo $query . "<br>";
	mySQLQuery($query);
	
	header('location:home.php');
?>