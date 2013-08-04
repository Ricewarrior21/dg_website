<?php 
	session_start();
	if (isset($_GET['ref'])) {
		$ref = $_GET['ref'];
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

	$friend = $_POST['addfriend'];
	$fquery = "SELECT id FROM users WHERE username = '$friend'";;
	mySQLQuery($fquery);
	$result = mySQLQUery($fquery);

	$userid = $_SESSION['userid'];
	$friendid = mysql_fetch_row($result)[0];
	$status = 0;

	if (empty($friendid)) {
		echo "Friend not found!";
		header('Refresh: 1; url=home.php?home-friends');
	} else {
		echo $friend . "<br>";
		echo "$userid <br>";
		echo "$friendid <br>";
		echo "$status <br>";
		$query = "INSERT INTO friends VALUES('', '$userid', '$friendid', '$status')";
		echo $query;
		mySQLQuery($query);
		header('location:home.php');
	}
?>