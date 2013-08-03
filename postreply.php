<?php 
	session_start();
	if (isset($_GET['message'])) {
		$ref = $_GET['message'];
	}

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

	$sender = $_SESSION['userid'];
	$rquery = "SELECT sender FROM messages WHERE ref ='$ref'";
	$result = mySQLQuery($rquery);
	$reciever = mysql_fetch_row($result)[0];
	$tquery = "SELECT title FROM messages where ref = '$ref'";
	$result2 = mySQLQuery($tquery);
	$title = "RE: " . mysql_fetch_row($result2)[0];
	$message = mysql_real_escape_string($_POST['inputreply']);
	$sent = date('Y-m-d H:i:s');
	
	echo $ref . "<br>";
	echo $sender . "<br>";
	echo $reciever . "<br>";
	echo $title . "<br>";
	echo $message . "<br>";
	echo $sent . "<br>";
	
	$query = "INSERT INTO messages VALUES('', '$sender', '$reciever', '$title', '$message', '$sent')";
	echo $query . "<br>";
	mySQLQuery($query);
	
	header('location:home.php');
?>