<?php session_start();
	$userid = $_SESSION['userid'];
	$link = $_POST['inputurl'];
	$username = $_SESSION['username'];
	$title = $_POST['inputtitle'];
	$description = $_POST['inputdescription'];
	
	$host = "localhost";
	$user = "jhansel1";
	$pass = "jhansel1";
	$db_name = masterdb;
	
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
	echo $userid . "<br>";
	echo $username . "<br>";
	echo $link . "<br>";
	echo $title . "<br>";
	echo $description . "<br>";
	
	$date = date('Y-m-d H:i:s');
	echo $date . "<br>";
	$query = "INSERT INTO datagrams VALUES('', '$title', '$description', '$userid', 'photo', '$link', '$date')";
	echo $query;
	mySQLQuery("INSERT INTO datagrams VALUES('', '$title', '$description', '$userid', 'photo', '$link', '$date')");
	
	header('location:home.php');
?>