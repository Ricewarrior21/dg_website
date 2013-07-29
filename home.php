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
		$result = mysql_query($input);
		
		if (!$result) {
			echo "Could not execute query: $input";
			echo "<br>";
			trigger_error(mysql_error(), E_USER_ERROR);
		} else {
			return $result;
		}
	}
	
	// Function for printing one dimensional arrays to test stuff
	function printArray($input) {
		for ($i = 0; $i < sizeof($input); $i++) {
			echo $input[$i] . "<br>";
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
	
	// Gets username from given userid
	function getUsername($input_userid) {
		$query = "SELECT username FROM users WHERE id='$input_userid'";
		$result = mySQLQuery($query);
		$username = mysql_fetch_row($result);
		return $username[0];
	}
	
	function get_dg_refs() {
		global $userid, $friends_id;
		$query = "SELECT ref FROM datagrams WHERE userid = '" . $userid[0][0] . "'";
		for ($i = 0; $i < sizeof($friends_id); $i++) {
			$query .= " OR userid = '" . $friends_id[$i] . "'";
		}
		$query .= " ORDER BY created DESC";
		$result = mySQLQuery($query);
		$temp = array();
		while($row = mysql_fetch_row($result)) {
			$temp[] = $row;
		}
		$dg_refs = array();
		for ($i = 0; $i < sizeof($temp); $i++) {
			$dg_refs[$i] = $temp[$i][0];
		}
		return $dg_refs;
	}
	
	function get_dg_column($inputColumn) {
		global $dg_refs;
		$dg_column = array();
		for ($i = 0; $i < sizeof($dg_refs); $i++) {
			$query = "SELECT $inputColumn FROM datagrams WHERE ref = '$dg_refs[$i]' ORDER BY created DESC";
			$result = mySQLQuery($query);
			$row = mysql_fetch_row($result);
			$dg_column[$i] = $row[0];
		}
		return $dg_column;
	}
	
	function get_dg_profile() {
		global $dg_userid;
		$dg_profile = array();
		for ($i = 0; $i < sizeof($dg_userid); $i++) {
			$query = "SELECT profile_pic FROM users WHERE id='$dg_userid[$i]'";
			$result = mySQLQuery($query);
			$row = mysql_fetch_row($result);
			$dg_profile[$i] = $row[0];
		}
		return $dg_profile;
	}

	function print_dg($username, $title, $description, $content, $type, $date, $profile_pic) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\" style=\"background-image:url($profile_pic)\">"; // Profile pic container
				echo "<div id=\"dg_profile_text\">"; // Profile text container
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
	
	function print_dg_link($username, $title, $description, $content, $type, $date, $profile_pic) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\" style=\"background-image:url($profile_pic)\">"; // Profile pic container
				echo "<div id=\"dg_profile_text\">"; // Profile text container
					echo "$username";
				echo "</div>"; // End profile text container
			echo "</div>"; // End profile pic container
			echo "<div id=\"dg_info\">";
				echo "<h1>" . $title . "</h1>"; // Title
				echo "<h2>" . $description . "</h2>"; // Description
				echo "<h3><a href=" . $content . ">" . $content . "</a></h3>"; // Description
				echo "<h4>Type of Datagram: <b>" . $type . "</b><br>"; // Tell the type of datagram
				echo "Created: <i>" . $date . "</i></h4><br>";
			echo "</div>"; // End info container
		echo "</div>"; // End datagram container
		echo "<br>"; // Line breaks for next datagram
	}
	
	function print_dg_pic($username, $title, $description, $content, $type, $date, $profile_pic) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\" style=\"background-image:url($profile_pic)\">"; // Profile pic container
				echo "<div id=\"dg_profile_text\">"; // Profile text container
					echo "$username";
				echo "</div>"; // End profile text container
			echo "</div>"; // End profile pic container
			echo "<div id=\"dg_info\">";
				echo "<h1>" . $title . "</h1>"; // Title
				echo "<h2>" . $description . "</h2>"; // Description
				echo "<h3><a href=\"$content\"><div id=\"dg_info_pic\" style=\"background-image:url($content);\"></div></a></h3>"; // Content
				echo "<h4>Type of Datagram: <b>" . $type . "</b><br>"; // Tell the type of datagram
				echo "Created: <i>" . $date . "</i></h4>";
			echo "</div>"; // End info container
		echo "</div>"; // End datagram container
		echo "<br>"; // Line breaks for next datagram
	}
	
	function print_dg_status($username, $content, $type, $date, $profile_pic) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\" style=\"background-image:url($profile_pic)\">"; // Profile pic container
				echo "<div id=\"dg_profile_text\">"; // Profile text container
					echo "$username";
				echo "</div>"; // End profile text container
			echo "</div>"; // End profile pic container
			echo "<div id=\"dg_info\">"; // Info container
				echo "<div id=\"dg_status\">"; // Status container
					echo "<h1>" . $content . "</h1>"; // Title
					echo "<div id=\"dg_status_after\">"; // Side speech bubble pointer thing
					echo "</div>"; // End after status bubble
				echo "</div>"; // End status container
				echo "<h4>Type of Datagram: <b>" . $type . "</b><br>"; // Tell the type of datagram
				echo "Created: <i>" . $date . "</i></h4>";
			echo "</div>"; // End info container
		echo "</div>"; // End datagram container
		echo "<br>"; // Line breaks for next datagram
	}

	// Get the profile url of current user
	function getProfilePic() {
		global $userid;
		$query = "SELECT profile_pic FROM users WHERE id='$userid[0]'";
		$result = mySQLQuery($query);
		$profile = mysql_fetch_row($result)[0];
		return $profile;
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
<div id="popup_container_link" style="display:none;">
	<div id="popup">
		<div id="popup_icon" style="background-image:url(images/create_link_icon.png)">
			<div id="popup_icon_text">
				Create a link!
			</div>
		</div>
		<h1>Link</h1><br><br><br>
		<!-- Start popup input -->
		
		<!-- Button to close at top right -->
		<div id="popup_close"><a href="" onclick="popup('popup_container_link')">X</a></div>
		
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
				<textarea class="popup_textarea" name="inputdescription">Write a description here.</textarea>
			</div>
			<input type="submit" value="Share" class="popup_submit"/><br>
		</form>
		<!-- End form -->
		
		<!-- End popup input -->
	</div>
</div>
<!-- Popup link end-->

<!-- Popup photo start-->
<div id="popup_container_photo" style="display:none;">
	<div id="popup">
		<div id="popup_icon" style="background-image:url(images/create_photo_icon.png)">
			<div id="popup_icon_text">
				Post a photo!
			</div>
		</div>
		<h1>Photo</h1><br><br><br>
		<!-- Start popup input -->
		
		<!-- Button to close at top right -->
		<div id="popup_close"><a href="" onclick="popup('popup_container_photo')">X</a></div>
		
		<!-- Background of textbars/box -->
		<div id="popup_bar_bg"></div>
		
		<!-- Start form for input of link datagram -->
		<form action="postphoto.php" method="post" enctype="multipart/form-data">
			<div id="popup_bar">
				<div id="popup_text_container">
					<h1>Title</h1>
					<h2>Give your photo a title.</h2>
				</div>
				<input type="text" class="popup_textbox" name="inputtitle"></input>
			</div>
			<div id="popup_bar">
				<div id="popup_text_container">
					<h1>Upload</h1>
					<h2>Upload an image from your computer.</h2>
				</div>
				<input type="file" class="popup_textbox" style="border:2px solid white; border-top:2px solid #9a9a9a; border-left:2px solid #9a9a9a" name="inputimage"/>
			</div>
			<div id="popup_bar">
				<div id="popup_text_container">
					<h1>Description</h1>
					<h2>Give your photo a description.</h2>
				</div>
				<textarea class="popup_textarea" name="inputdescription">Write a description here.</textarea>
			</div>
			<input type="submit" value="Share" class="popup_submit"/><br>
		</form>
		<!-- End form -->
		
		<!-- End popup input -->
	</div>
</div>
<!-- Popup photo end-->

<!-- Popup change profile pic start-->
<div id="popup_container_profile_pic" style="display:none; height:0px; margin-top:0px;">
	<div id="popup" style="height:230px; top:200px; margin-top:-127px">
		<div id="popup_icon" style="background-image:url(images/create_link_icon.png)">
			<div id="popup_icon_text">
				Change your profile picture
			</div>
		</div>
		<h1>Profile Picture</h1><br><br><br>
		<!-- Start popup input -->
		
		<!-- Button to close at top right -->
		<div id="popup_close"><a href="" onclick="popup('popup_container_profile_pic')">X</a></div>
		
		<!-- Background of textbars/box -->
		<div id="popup_bar_bg" style="height:55px; margin-top:20px"></div>
		
		<!-- Start form for input of link datagram -->
		<form action="postprofile.php" method="post" enctype="multipart/form-data">
			<div id="popup_bar">
				<div id="popup_text_container">
					<h1>Upload</h1>
					<h2>Upload an image from your computer.</h2>
				</div>
				<input type="file" class="popup_textbox" style="margin-top:32px; border:2px solid white; border-top:2px solid #9a9a9a; border-left:2px solid #9a9a9a" name="profileimage"/>
			</div>
			<input type="submit" value="Submit" class="popup_submit" style="margin-top:10px; margin-left:530px;"/><br>
		</form>
		<!-- End form -->
		
		<!-- End popup input -->
	</div>
</div>
<!-- Popup change profile pic end-->

<div id="bg_container">
	<!-- Main header start -->
	<div id="main_header">
		<!-- Main header profile picture on left -->
		<div id="profile_container" style="background-image:url(<?php $profile = getProfilePic(); echo $profile ?>)">
			<div id="profile_text">
				<a href="#profile_pic" onclick="popup('popup_container_profile_pic')">Change your profile picture.</a> <!-- Make it so this does something -->
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
					<a href="#" onclick="popup('popup_container_link')">
					<div id="mh_bar_icon" style="background-image:url(images/link_icon.png);">
						<div id="mh_bar_icon_text">Link</div>
					</div>
					</a>
				</div>
				<!-- Photo -->
				<div id="mh_bar_icon_container">
					<a href="#" onclick="popup('popup_container_photo')">
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
			<div id="profile_container" style="margin-bottom:0px; top:5px; left:0px; background-image:url(<?php echo $profile ?>)">
				<div id="profile_text">
					<?php echo $username ?>
				</div>
			</div>
			
			<!-- Status profile pic -->
			<div id="status">
				<form method="post" action="poststatus.php" class="status_form">
					<textarea class="status_textbox" name="inputstatus">Enter your status here.</textarea>
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
		$dg_refs = get_dg_refs();
		$dg_title = get_dg_column("title");
		$dg_description = get_dg_column("description");
		$dg_userid = get_dg_column("userid");
		$dg_type = get_dg_column("type");
		$dg_content = get_dg_column("content");
		$dg_created = get_dg_column("created");
		$dg_profile_pic = get_dg_profile();
		
		function printDatagrams() {
			global $dg_title, $dg_userid, $dg_description, $dg_content, $dg_type, $dg_created, $dg_profile_pic;
			for ($i = 0; $i < sizeof($dg_title); $i++) {
				if ($dg_type[$i] == "link") {
					print_dg_link(getUsername($dg_userid[$i]), $dg_title[$i], $dg_description[$i], $dg_content[$i], $dg_type[$i], $dg_created[$i], $dg_profile_pic[$i]);
				} else if ($dg_type[$i] == "photo") {
					print_dg_pic(getUsername($dg_userid[$i]), $dg_title[$i], $dg_description[$i], $dg_content[$i], $dg_type[$i], $dg_created[$i], $dg_profile_pic[$i]);
				} else if ($dg_type[$i] == "status") {
					print_dg_status(getUsername($dg_userid[$i]), $dg_content[$i], $dg_type[$i], $dg_created[$i], $dg_profile_pic[$i]);
				} else {
					print_dg(getUsername($dg_userid[$i]), $dg_title[$i], $dg_description[$i], $dg_content[$i], $dg_type[$i], $dg_created[$i], $dg_profile_pic[$i]);
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