<?php 
	session_start();
	if (isset($_FILES['inputimage'])) {
		$errors = array();

		$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
		
		$file_name = $_FILES['inputimage']['name'];
		$file_ext = strtolower(end(explode('.', $file_name)));
		$file_size = $_FILES['inputimage']['size'];
		$file_tmp = $_FILES['inputimage']['tmp_name'];

		if (in_array($file_ext, $allowed_ext) == false) {
			$errors[] = 'Extension not allowed';
		}
		
		if ($file_size > 2097152) {
			$errors[] = 'File size must be under 2mb';
		}

		if (empty($errors)) {
			// upload the file
			$target_path = "uploads/" . $file_name;
			if(move_uploaded_file($file_tmp, $target_path)) {
				echo "Uploaded!";
			} else {
				echo "Failed to upload the file";
			}
		} else {
			foreach($errors as $errors) {
				echo $errors . "<br>";
				echo $file_ext;
			}
		}
	}
	$userid = $_SESSION['userid'];
	$username = $_SESSION['username'];
	$path = "uploads/" . $_FILES['inputimage']['name'];
	$link = mysql_real_escape_string($path);
	$title = $_POST['inputtitle'];
	$description = $_POST['inputdescription'];
	
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
	
	header('location:home.php'); */
?>