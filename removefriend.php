<?php 
	session_start();
	if (isset($_GET['remove'])) {
		$ref = $_GET['remove'];
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

	$friend = $ref;
	
	$fquery = "SELECT id FROM users WHERE username = '$friend'";
	$result = mySQLQuery($fquery);
	$friendid = mysql_fetch_row($result)[0];
	
	$userid = $_SESSION['userid'];
	
	$query = "DELETE FROM friends WHERE userid = '$userid' AND friendid = '$friendid'";
	
	mySQLQuery($query);
	echo "Removed friend! <br>";
	header('Refresh: 1; url=home.php?home=friends');

?>