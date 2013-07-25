<?php session_start();
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
	
	// Grab ids of friends of current user
	function getFriends_id() {
		global $userid;
		$result = mySQLQuery("SELECT friendid FROM friends WHERE userid = '$userid[0]'");
		$temp = array();
		while($row = mysql_fetch_row($result)) {
			$temp[] = $row;
		}
		$friends_id = array();
		for ($i = 0; $i < sizeof($temp); $i++) {
			$friends_id[$i] = $temp[$i][0];
		}			
		return $friends_id;
	}
	
	// Returns string username or friends
	function getFriends() {
		global $userid;
		$result = mySQLQuery("SELECT friendid FROM friends WHERE userid = '$userid[0]'");
		$temp = array();
		while($row = mysql_fetch_row($result)) {
			$temp[] = $row;
		}
		$friends_id = array();
		for ($i = 0; $i < sizeof($temp); $i++) {
			$friends_id[$i] = $temp[$i][0];
		}
		$temp2 = array();
		for ($i = 0; $i < sizeof($friends_id); $i++) {
			$result = mySQLQuery("SELECT username FROM users WHERE id = '$friends_id[$i]'");
			$temp2[] = mysql_fetch_row($result);
		}
		$friends = array();
		for ($i = 0; $i < sizeof($temp); $i++) {
			$friends[$i] = $temp2[$i][0];
		}				
		return $friends;
	}
	
	function getColOfTbl($col_name, $tbl_name, $where_col, $where_res) {
		$query = "SELECT $col_name FROM $tbl_name WHERE $where_col = '$where_res'";
		$result = mySQLQuery($query);
		
		$row = array();
		$temp = array();
		
		$i = 0;
		while($row = mysql_fetch_array($result)) {
			$temp[$i] = $row[0];
			$i++;
		}
		return $temp;
	}
	
	function getColOfTblOrder($col_name, $tbl_name, $where_col, $where_res) {
		$query = "SELECT $col_name FROM $tbl_name WHERE $where_col = '$where_res' ORDER BY created DESC";
		$result = mySQLQuery($query);
		
		$row = array();
		$temp = array();
		
		$i = 0;
		while($row = mysql_fetch_array($result)) {
			$temp[$i] = $row[0];
			$i++;
		}
		return $temp;
	}

	function get_dg_titles() {
		$temp = array();
		$dg_titles = array();
		global $friends_id;
		for($i = 0; $i < sizeof($friends_id); $i++) {
			$temp[$i] = getColOfTblOrder("title", "datagrams", "userid", "$friends_id[$i]");
		}
		$a = 0;
		for ($i = 0; $i < sizeof($temp); $i++) {
			for($j = 0; $j < sizeof($temp[$i]); $j++) {
				$dg_titles[$a] = $temp[$i][$j];
				$a++;
			}
		}
		return $dg_titles;
	}

	function get_dg_owner() {
		$temp = get_dg_column("userid");
		$temp2 = array();
		$dg_friends = array();
		for ($i = 0; $i < sizeof($temp); $i++) {
			$temp2[$i] = getColOfTbl("username", "users", "id", "$temp[$i]");
		}
		$a = 0;
		for ($i = 0; $i < sizeof($temp2); $i++) {
			for($j = 0; $j < sizeof($temp2[$i]); $j++) {
				$dg_friends[$a] = $temp2[$i][$j];
				$a++;
			}
		}
		return $dg_friends;
	}
	
	function get_dg_column($column) {
		global $dg_titles;
		$temp = array();
		$dg_column = array();
		for ($i = 0; $i < sizeof($dg_titles); $i++) {
			$title = mysql_real_escape_string($dg_titles[$i]);
			$temp[$i] = getColOfTblOrder("$column", "datagrams", "title", "$title");
		}
		echo "<br>";
		$a = 0;
		for($i = 0; $i < sizeof($temp); $i++) {
			for($j = 0; $j < sizeof($temp[$i]); $j++) {
				$dg_column[$a] = $temp[$i][$j];
				$a++;
			}
		}
		return $dg_column;
	}
	
	function print_dg($username, $title, $description, $content, $type) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\">"; // Profile pic container
				echo "<div id=\"dgprofile_text\">"; // Profile text container
					echo "$username";
				echo "</div>"; // End profile text container
			echo "</div>"; // End profile pic container
			echo "<div id=\"dg_info\">";
				echo "<h1>" . $title . "</h1>"; // Title
				echo "<h2>" . $description . "</h2>"; // Description
				echo "<h3>" . $content . "</h3>"; // Description
				echo "Type of Datagram: " . $type . "<br>"; // Tell the type of datagram
				echo "Created: " . $date;
			echo "</div>"; // End info container
		echo "</div>"; // End datagram container
		echo "<br>"; // Line breaks for next datagram
	}
	
	function print_dg_link($username, $title, $description, $content, $type, $date) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\">"; // Profile pic container
				echo "<div id=\"dgprofile_text\">"; // Profile text container
					echo "$username";
				echo "</div>"; // End profile text container
			echo "</div>"; // End profile pic container
			echo "<div id=\"dg_info\">";
				echo "<h1>" . $title . "</h1>"; // Title
				echo "<h2>" . $description . "</h2>"; // Description
				echo "<h3><a href=" . $content . ">" . $content . "</a></h3>"; // Description
				echo "Type of Datagram: " . $type . "<br>"; // Tell the type of datagram
				echo "Created: " . $date;
			echo "</div>"; // End info container
		echo "</div>"; // End datagram container
		echo "<br>"; // Line breaks for next datagram
	}
	
	function print_dg_pic($username, $title, $description, $content, $type, $date) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\">"; // Profile pic container
				echo "<div id=\"dgprofile_text\">"; // Profile text container
					echo "$username";
				echo "</div>"; // End profile text container
			echo "</div>"; // End profile pic container
			echo "<div id=\"dg_info\">";
				echo "<h1>" . $title . "</h1>"; // Title
				echo "<h2>" . $description . "</h2>"; // Description
				echo "<h3><div id=\"dg_info_pic\"><img src=\"" . $content . "\" style=\"width:100%\"></img></div></h3>"; // Content
				echo "Type of Datagram: " . $type . "<br>"; // Tell the type of datagram
				echo "Created: " . $date;
			echo "</div>"; // End info container
		echo "</div>"; // End datagram container
		echo "<br>"; // Line breaks for next datagram
	}

	$username = $_SESSION['username'];
	$r1 = mySQLQuery("SELECT id FROM users where username = '$username'");
	$userid = mysql_fetch_row($r1);
	$_SESSION['userid'] = $userid[0][0];
	$friends_id = getFriends_id();
	$friends = getFriends();
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">  
<head>
<title>Datagram</title>
</head>
<link rel="stylesheet" type="text/css" href="home.css">
<style>

</style>

<script type="text/javascript" src="csspopup.js">
</script>

<body>

<div id="header_container">
	<div id="header">
		Hello there, <?php echo "$username" ?>! Not you? Click <a href="logout.php">here</a> to logout.
	</div>
</div>

<div id="blanket" style="display:none;"></div>

<!-- Popup link start-->
<div id="popup_container" style="display:none;">
	<div id="popup">
		<div id="popup_icon">
			<div id="popup_icon_text">
				Create a link!
			</div>
		</div>
		<h1>Datagram Link</h1><br><br><br>
		<!-- Start popup input -->
		
		<!-- Button to close at top right -->
		<div id="popup_close"><a href="#" onclick="popup('popup_container')">X</a></div>
		
		<!-- Background of textbars/box -->
		<div id="popup_bar_bg"></div>
		
		<!-- Start form for input of link datagram -->
		<form action="postlink.php" method="post">
			<div id="popup_bar">
				<div id="popup_text_container">
					<h1>Title</h1>
					<h2>Give your datagram a title.</h2>
				</div>
				<input type="text" class="popup_textbox" name="inputtitle"></input>
			</div>
			<div id="popup_bar">
				<div id="popup_text_container">
					<h1>URL</h1>
					<h2>Copy and paste a URL.</h2>
				</div>
				<input type="text" class="popup_textbox" name="inputurl"></input>
			</div>
			<div id="popup_bar">
				<div id="popup_text_container">
					<h1>Description</h1>
					<h2>Give your link a description.</h2>
				</div>
				<textarea action="postlink.php" class="popup_textarea" name="inputdescription">Write a description here.</textarea>
			</div>
			<input type="submit" value="Share" class="popup_submit"/><br>
		</form>
		<!-- End form -->
		
		<!-- End popup input -->
	</div>
</div>
<!-- Popup link end-->

<div id="bg_container">
	<!-- Main header start -->
	<div id="main_header">
		<!-- Main header profile picture on left -->
		<div id="profile_container">
			<div id="profile_text">
				Change your profile picture. <!-- Make it so this does something -->
			</div>
		</div>
		<!-- End main profile header -->

		<!-- Main header bar of links / functions -->
		<div id="main_header_bar">
			<!-- Sub-header bar container to help center the icons -->
			<div class="mh_bar">
				<!-- Home -->
				<div id="mh_bar_icon_container">
					<a href="home.php">
					<div id="mh_bar_icon" style="background-image:url(images/home_icon.png);">
						<div id="mh_bar_icon_text">Home</div>
					</div>
					</a>
				</div>
				<!-- Link -->
				<div id="mh_bar_icon_container">
					<a href="#" onclick="popup('popup_container')">
					<div id="mh_bar_icon" style="background-image:url(images/link_icon.png);">
						<div id="mh_bar_icon_text">Link</div>
					</div>
					</a>
				</div>
				<!-- Photo -->
				<div id="mh_bar_icon_container">
					<a href="#">
					<div id="mh_bar_icon" style="background-image:url(images/photo_icon.png);">
						<div id="mh_bar_icon_text">Photo</div>
					</div>
					</a>
				</div>
				<!-- Video -->
				<div id="mh_bar_icon_container">
					<a href="#">
					<div id="mh_bar_icon" style="background-image:url(images/video_icon.png);">
						<div id="mh_bar_icon_text">Video</div>
					</div>
					</a>
				</div>
				<!-- Messages -->
				<div id="mh_bar_icon_container">
					<a href="#">
					<div id="mh_bar_icon" style="background-image:url(images/message_icon.png);">
						<div id="mh_bar_icon_text">Messages</div>
					</div>
					</a>
				</div>
				<!-- Friends -->
				<div id="mh_bar_icon_container">
					<a href="#">
					<div id="mh_bar_icon" style="background-image:url(images/options_icon.png);">
						<div id="mh_bar_icon_text">Friends</div>
					</div>
					</a>
				</div>
			</div>
			<!-- End sub-header container -->
		</div>
		<!-- End header bar -->
	</div>
	<!-- Main header end -->
	<br>
	<!-- Main body start -->
	<div id="main_body">
		<h1>Welcome <?php echo $username ?>!</h1>
		<!-- Divider for main body -->
		<div class="main_body_divider"></div>
		<!-- Status container start -->
		<div id="status_container">
			<!-- Status profile pic -->
			<div id="profile_container" style="margin-bottom:0px; top:5px; left:0px;">
				<div id="profile_text">
					username
				</div>
			</div>
			
			<!-- Status profile pic -->
			<div id="status">
				<form method="post" action="" class="status_form">
					<textarea class="status_textbox">Enter your status here.</textarea>
					<input type="submit" value="Share" class="status_share" />
				</form>
				<h1>Post a status update!</h1><br>
			</div>
		</div>
		<!-- Status container end -->
		
		<!-- Friends container start -->
		<div id="friends">
			<h1>Friends</h1>
			<div class="friends_divider"></div>
			<!-- Function for printing friends out -->
			<?php 
				for ($i = 0; $i < sizeof($friends); $i++) {
					echo "<h2>" . $friends[$i] . "</h2><br>";
				}
			?>
		</div>
		<!-- Friends container end -->
		
		<!-- Datagrams start -->
		<?php
			$dg_titles = get_dg_titles();
			$dg_owners = get_dg_owner();
			$dg_descriptions = get_dg_column("description");
			$dg_contents = get_dg_column("content");
			$dg_types = get_dg_column("type");
			$dg_date = get_dg_column("created");
			function printDatagrams() {
				global $dg_titles, $dg_owners, $dg_descriptions, $dg_contents, $dg_types, $dg_date;
				for ($i = 0; $i < sizeof($dg_titles); $i++) {
					if ($dg_types[$i] == "link") {
						print_dg_link($dg_owners[$i], $dg_titles[$i], $dg_descriptions[$i], $dg_contents[$i], $dg_types[$i], $dg_date[$i]);
					} else if ($dg_types[$i] == "photo") {
						print_dg_pic($dg_owners[$i], $dg_titles[$i], $dg_descriptions[$i], $dg_contents[$i], $dg_types[$i], $dg_date[$i]);
					} else {
						print_dg($dg_owners[$i], $dg_titles[$i], $dg_descriptions[$i], $dg_contents[$i], $dg_types[$i], $dg_date[$i]);
					}
				}
			}
			printDatagrams();
		?> 
		<!-- Datagrams end -->

	</div>
	<!-- Main body end -->
</div>

</body>
</html>