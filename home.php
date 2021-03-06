<?php 
	session_start();
	if(empty ($_SESSION['home_status'])) {
		echo "An error occurred!" . "<br>";
		echo "Please login first!" . "<br>";
		header("Refresh: 1.5; url=index.php");
		exit();
	}
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
	
	function getProfilePic($input_userid) {
		$query = "SELECT profile_pic FROM users WHERE id='$input_userid'";
		$result = mySQLQuery($query);
		$username = mysql_fetch_row($result);
		return $username[0];
	}
	
	function get_userid($username) {
		$query = "SELECT id FROM users WHERE username = '$username'";
		$result = mySQLQuery($query);
		$userid = mysql_fetch_row($result)[0];
		return $userid;
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
	
	function get_dg_column($input_refs, $inputColumn) {
		$dg_column = array();
		for ($i = 0; $i < sizeof($input_refs); $i++) {
			$query = "SELECT $inputColumn FROM datagrams WHERE ref = '$input_refs[$i]' ORDER BY created DESC";
			$result = mySQLQuery($query);
			$row = mysql_fetch_row($result);
			$dg_column[$i] = $row[0];
		}
		return $dg_column;
	}
	
	function get_dg_profile($input_userids) {
		$dg_profile = array();
		for ($i = 0; $i < sizeof($input_userids); $i++) {
			$query = "SELECT profile_pic FROM users WHERE id='$input_userids[$i]'";
			$result = mySQLQuery($query);
			$row = mysql_fetch_row($result);
			$dg_profile[$i] = $row[0];
		}
		return $dg_profile;
	}

	function print_dg($username, $title, $description, $content, $type, $date, $profile_pic, $ref) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\" style=\"background-image:url($profile_pic)\">"; // Profile pic container
				echo "<div id=\"dg_profile_text\">"; // Profile text container
					echo "<a href=\"home.php?home=$username\">$username</a>";
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
	
	function print_dg_link($username, $title, $description, $content, $type, $date, $profile_pic, $ref) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\" style=\"background-image:url($profile_pic)\">"; // Profile pic container
				echo "<div id=\"dg_profile_text\">"; // Profile text container
					echo "<a href=\"home.php?home=$username\">$username</a>";
				echo "</div>"; // End profile text container
			echo "</div>"; // End profile pic container
			echo "<div id=\"dg_info\">";
				echo "<h1>" . $title . "</h1>"; // Title
				echo "<h2>" . $description . "</h2>"; // Description
				echo "<h3><a href=" . $content . ">" . $content . "</a></h3>"; // Description
				print_comments($ref);
				echo "<h4>Type of Datagram: <b>" . $type . "</b><br>"; // Tell the type of datagram
				echo "Created: <i>" . $date . "</i></h4><br>";
			echo "</div>"; // End info container
		echo "</div>"; // End datagram container
		echo "<br>"; // Line breaks for next datagram
	}
	
	function getNumberOfComments($ref) {
		$query = "SELECT ref FROM comments WHERE datagram = '$ref' ORDER BY created DESC";
		$result = mySQLQuery($query);
		$temp = array();
		while($row = mysql_fetch_row($result)) {
			$temp[] = $row;
		}
		return sizeof($temp);
	}
	
	function print_dg_pic($username, $title, $description, $content, $type, $date, $profile_pic, $ref) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\" style=\"background-image:url($profile_pic)\">"; // Profile pic container
				echo "<div id=\"dg_profile_text\">"; // Profile text container
					echo "<a href=\"home.php?home=$username\">$username</a>";
				echo "</div>"; // End profile text container
			echo "</div>"; // End profile pic container
			echo "<div id=\"dg_info\">";
				echo "<h1>" . $title . "</h1>"; // Title
				echo "<h2>" . $description . "</h2>"; // Description
				echo "<h3><a href=\"$content\"><div id=\"dg_info_pic\" style=\"background-image:url($content);\"></div></a></h3>"; // Content
				print_comments($ref);
				echo "<h4>Type of Datagram: <b>" . $type . "</b><br>"; // Tell the type of datagram
				echo "Created: <i>" . $date . "</i></h4>";
			echo "</div>"; // End info container
		echo "</div>"; // End datagram container
		echo "<br>"; // Line breaks for next datagram
	}
	
	function print_dg_status($username, $content, $type, $date, $profile_pic, $ref) {
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\" style=\"background-image:url($profile_pic)\">"; // Profile pic container
				echo "<div id=\"dg_profile_text\">"; // Profile text container
					echo "<a href=\"home.php?home=$username\">$username</a>";
				echo "</div>"; // End profile text container
			echo "</div>"; // End profile pic container
			echo "<div id=\"dg_info\">"; // Info container
				echo "<div id=\"dg_status\">"; // Status container
					echo "<h1>" . $content . "</h1>"; // Title
					echo "<div id=\"dg_status_after\">"; // Side speech bubble pointer thing
					echo "</div>"; // End after status bubble
				echo "</div>"; // End status container
				print_comments($ref);
				echo "<h4>Type of Datagram: <b>" . $type . "</b><br>"; // Tell the type of datagram
				echo "Created: <i>" . $date . "</i></h4>";
			echo "</div>"; // End info container
		echo "</div>"; // End datagram container
		echo "<br>"; // Line breaks for next datagram
	}
	
	function print_dg_video($username, $title, $description, $content, $type, $date, $profile_pic, $ref) {
		$yt_code = end(explode('=', $content));
		$yt_embed = "//www.youtube.com/embed/" . $yt_code . "?rel=0";
		echo "<div id=\"datagram\">";// Datagram container
			echo "<div id=\"dg_profile_pic\" style=\"background-image:url($profile_pic)\">"; // Profile pic container
				echo "<div id=\"dg_profile_text\">"; // Profile text container
					echo "<a href=\"home.php?home=$username\">$username</a>";
				echo "</div>"; // End profile text container
			echo "</div>"; // End profile pic container
			echo "<div id=\"dg_info\">";
				echo "<h1>" . $title . "</h1>"; // Title
				echo "<h2>" . $description . "</h2>"; // Description
				echo "<h3><iframe width=\"560\" height=\"315\" src=\"$yt_embed\" frameborder=\"0\" allowfullscreen></iframe></h3>"; // Content
				print_comments($ref);
				echo "<h4>Type of Datagram: <b>" . $type . "</b><br>"; // Tell the type of datagram
				echo "Created: <i>" . $date . "</i></h4>";
			echo "</div>"; // End info container
		echo "</div>"; // End datagram container
		echo "<br>"; // Line breaks for next datagram
	}

	// Get the profile url of current user
	function getCurrentProfilePic() {
		global $userid;
		$query = "SELECT profile_pic FROM users WHERE id='$userid[0]'";
		$result = mySQLQuery($query);
		$profile = mysql_fetch_row($result)[0];
		return $profile;
	}
	
	function printDatagrams($dg_refs) {
		$dg_title = get_dg_column($dg_refs, "title");
		$dg_description = get_dg_column($dg_refs, "description");
		$dg_userid = get_dg_column($dg_refs, "userid");
		$dg_type = get_dg_column($dg_refs, "type");
		$dg_content = get_dg_column($dg_refs, "content");
		$dg_created = get_dg_column($dg_refs, "created");
		$dg_profile_pic = get_dg_profile($dg_userid);
		for ($i = 0; $i < sizeof($dg_title); $i++) {
			if ($dg_userid[$i] == $_SESSION['userid']) {
				echo "<button class=\"dg_remove_button\" style=\"color:black\">
							<a href=\"removedatagram.php?remove=$dg_refs[$i]\" class=\"friends_remove_button_text\" style=\"color:black\">Delete</a>
						</button>";
			}
			if ($dg_type[$i] == "link") {
				print_dg_link(getUsername($dg_userid[$i]), $dg_title[$i], $dg_description[$i], $dg_content[$i], $dg_type[$i], $dg_created[$i], $dg_profile_pic[$i], $dg_refs[$i]);
			} else if ($dg_type[$i] == "photo") {
				print_dg_pic(getUsername($dg_userid[$i]), $dg_title[$i], $dg_description[$i], $dg_content[$i], $dg_type[$i], $dg_created[$i], $dg_profile_pic[$i], $dg_refs[$i]);
			} else if ($dg_type[$i] == "status") {
				print_dg_status(getUsername($dg_userid[$i]), $dg_content[$i], $dg_type[$i], $dg_created[$i], $dg_profile_pic[$i], $dg_refs[$i]);
			} else if ($dg_type[$i] == "video") {
				print_dg_video(getUsername($dg_userid[$i]), $dg_title[$i], $dg_description[$i], $dg_content[$i], $dg_type[$i], $dg_created[$i], $dg_profile_pic[$i], $dg_refs[$i]);
			} else {
				print_dg(getUsername($dg_userid[$i]), $dg_title[$i], $dg_description[$i], $dg_content[$i], $dg_type[$i], $dg_created[$i], $dg_profile_pic[$i], $dg_refs[$i]);
			}
		}
	}
	
	function printUserDatagrams($username) {
		$userid = get_userid($username);
		$dg_query = "SELECT ref FROM datagrams WHERE userid = '$userid' ORDER BY created DESC";
		$result = mySQLQuery($dg_query);
		$temp = array();
		while($row = mysql_fetch_row($result)) {
			$temp[] = $row;
		}
		$dg_refs = array();
		for($i = 0; $i < sizeof($temp); $i++) {
			$dg_refs[$i] = $temp[$i][0];
		}
		printDatagrams($dg_refs);
	}
	
	function get_comments_refs($ref) {
		$query = "SELECT ref FROM comments WHERE datagram='$ref' ORDER BY created DESC";
		$result = mySQLQuery($query);
		$temp = array();
		$comments_refs = array();
		while($row = mysql_fetch_row($result)) {
			$temp[] = $row;
		}
		for($i = 0; $i < sizeof($temp); $i++) {
			$comments_refs[$i] = $temp[$i][0];
		}
		return $comments_refs;
	}

	function get_comment_column($comments_refs, $column) {
		$comments_column = array();
		for($i = 0; $i < sizeof($comments_refs); $i++) {
			$query = "SELECT $column FROM comments WHERE ref = '$comments_refs[$i]' ORDER BY created DESC";
			$result = mySQLQuery($query);
			$row = mysql_fetch_row($result);
			$comments_column[$i] = $row[0];
		}
		return $comments_column;
	}

	function print_comments($ref) {
		$refs = get_comments_refs($ref);
		
		if (empty($refs)) {
			global $profile;
			echo "<div class=\"post_comment_container\">"; // Start post comment container
				echo "<p class=\"commentbox\" style=\"color:#c5ecc5\">Post a comment</p>";
				echo "<div id=\"post_comment_icon\" style=\"background-image:url('$profile')\">";
					echo "<div id=\"post_comment_icon_text\">Post a comment!</div>";
				echo "</div>";
				echo "<div id=\"post_comment\">";
					echo "<form method=\"post\" action=\"comment.php?ref=$ref\">"; // ref says what datagram to create comment for
						echo "<textarea class=\"comment_textbox\" name=\"inputcomment\">Enter a comment here.</textarea>";
						echo "<input type=\"submit\" value=\"Submit\" class=\"status_share\" style=\"margin-left:465px;\" />";
					echo "</form>";
				echo "</div>";
			echo "</div>"; // End post comment container
			return false;
		} else {
			$creator = get_comment_column($refs, "creator");
			$datagram = get_comment_column($refs, "datagram");
			$content = get_comment_column($refs, "content");
			$created = get_comment_column($refs, "created");
			
			echo "<div class=\"dg_comment_container\">"; // Start comment container
				echo "<p class=\"comments_header\" style=\"font-size:14px; font-weight:bold; color:#c5ecc5 \">";
					echo "Comments";
				echo "</p>";
			
			// Printing center
			for ($i = 0; $i < sizeof($refs); $i++) {
				$profile = getProfilePic($creator[$i]);
				$username = getUsername($creator[$i]);
				echo "<div class=\"dg_comment\">"; // Start comment
					echo "<div class=\"dg_comments_bubble\">"; // Start bubble thing
					echo "<div id=\"dg_comments_profile\" style=\"background-image:url('$profile')\">";
						echo "<div id=\"dg_comments_profile_text\">";
							echo "$username";
						echo "</div>";
					echo "</div>";
					echo "<p>$content[$i]</p>";
					echo "<p style=\"font-size:80%;\">Posted on: $created[$i]</p>";
					echo "</div>";
					if ($creator[$i] == $_SESSION['userid']) {
						echo "<button class=\"comment_remove_button\" style=\"color:black\">
							<a href=\"removecomment.php?remove=$refs[$i]\" class=\"friends_remove_button_text\" style=\"color:black\">Delete</a>
						</button>";
					}
				echo "</div>"; // End comment;
			}
			echo "</div>"; // End comment container
		}
		
		global $profile;
		echo "<div class=\"post_comment_container\">"; // Start post comment container
			echo "<p class=\"commentbox\" style=\"color:#c5ecc5\">Post a comment</p>";
			echo "<div id=\"post_comment_icon\" style=\"background-image:url('$profile')\">";
				echo "<div id=\"post_comment_icon_text\">Post a comment!</div>";
			echo "</div>";
			echo "<div id=\"post_comment\">";
				echo "<form method=\"post\" action=\"comment.php?ref=$ref\">"; // ref says what datagram to create comment for
					echo "<textarea class=\"comment_textbox\" name=\"inputcomment\">Enter a comment here.</textarea>";
					echo "<input type=\"submit\" value=\"Submit\" class=\"status_share\" style=\"margin-left:465px;\" />";
				echo "</form>";
			echo "</div>";
		echo "</div>"; // End post comment container
	}
	
		function get_message_refs() {
			global $userid;
			$user = $userid[0][0];
			$query = "SELECT ref FROM messages WHERE reciever = '$user' ORDER BY sent DESC";
			$result = mySQLQuery($query);
			$temp = array();
			while($row = mysql_fetch_row($result)) {
				$temp[] = $row;
			}
			$message_refs = array();
			for($i = 0; $i < sizeof($temp); $i++) {
				$message_refs[$i] = $temp[$i][0];
			}
			return $message_refs;
		}
		
		function get_message_column($message_refs, $column) {
			$message_column = array();
			for ($i = 0; $i < sizeof($message_refs); $i++) {
				$query = "SELECT $column FROM messages WHERE ref ='$message_refs[$i]' ORDER BY sent DESC";
				$result = mySQLQuery($query);
				$row = mysql_fetch_row($result);
				$message_column[$i] = $row[0];
			}
			return $message_column;
		}

		function printMessages($msg_refs) {
			$msg_refs = get_message_refs();
			$msg_sender = get_message_column($msg_refs, "sender");
			$msg_reciever = get_message_column($msg_refs, "reciever");
			$msg_title = get_message_column($msg_refs, "title");
			$msg_message = get_message_column($msg_refs, "message");
			$msg_sent = get_message_column($msg_refs, "sent");
			$thisProfile = getCurrentProfilePic();
			echo "<div id=\"messages_container\">"; // Start Message container
			for($i = 0; $i < sizeof($msg_refs); $i++) {
				$username = getUsername($msg_sender[$i]);
				$profile = getProfilePic($msg_sender[$i]);
				echo "<div class=\"message\">"; // Start message
					echo "<div class=\"message_icon\" style=\"background-image:url('$profile')\"></div>";
					echo "<p class=\"message_title\">$msg_title[$i]</p>";
					echo "<p class=\"message_username\">$username</p>";
					echo "<p class=\"message_timestamp\">$msg_sent[$i]</p>";
					echo "<p class=\"message_content\">$msg_message[$i]</p>";
					echo "<form method=\"post\" action=\"postreply.php?message=$msg_refs[$i]\" class=\"message_reply_container\">";
					echo "<div class=\"message_reply_icon\" style=\"background-image:url('$thisProfile')\"></div>";
					echo "<textarea type=\"submit\" name=\"inputreply\" class=\"message_reply_textarea\"></textarea>";
					echo "<input type=\"submit\" value=\"Reply\" class=\"message_reply\" />";
					echo "</form>";
					echo "<button class=\"message_remove_button\">
						<a href=\"removemessage.php?remove=$msg_refs[$i]\" class=\"friends_remove_button_text\" style=\"color:black\">Delete</a>
					</button>";
				echo "</div>"; // End message
			}
			echo "</div>"; // End message container
		}
		
		function printFriends() {
			global $friends;
			global $friends_id;
			for ($i = 0; $i < sizeof($friends); $i++) {
				$fquery = "SELECT joined FROM users WHERE username = '$friends[$i]'";
				$result = mySQLQuery($fquery);
				$joined = mysql_fetch_row($result)[0];
				$profile = getProfilePic($friends_id[$i]);
				echo "
				<div id=\"datagram\" style=\"border:5px solid #e2e2e2; width:80%; margin-left:10px\">
					<div id=\"dg_profile_pic\" style=\"background-image:url('$profile')\">
						<div id=\"dg_profile_text\">
							<a href=\"home.php?home=$friends[$i]\">$friends[$i]</a>
						</div>
					</div>
					<div id=\"dg_info\">
					<br><br>
					<h1 style=\"margin-top:0px; margin-left:20px; text-align:center\"><a href=\"home.php?home=$friends[$i]\">$friends[$i]</a></h1>
					<h2 style=\"text-align:center\">Joined on: $joined</h2>
					</div>
				</div>
				<button class=\"friend_remove_button\">
					<a href=\"removefriend.php?remove=$friends[$i]\" class=\"friends_remove_button_text\">Remove</a>
				</button>
				<br>
				";
			}
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

<!-- Popup video start-->
<div id="popup_container_video" style="display:none;">
	<div id="popup">
		<div id="popup_icon" style="background-image:url(images/create_link_icon.png)">
			<div id="popup_icon_text">
				Post a video!
			</div>
		</div>
		<h1>Video</h1><br><br><br>
		<!-- Start popup input -->
		
		<!-- Button to close at top right -->
		<div id="popup_close"><a href="" onclick="popup('popup_container_video')">X</a></div>
		
		<!-- Background of textbars/box -->
		<div id="popup_bar_bg"></div>
		
		<!-- Start form for input of link datagram -->
		<form action="postvideo.php" method="post">
			<div id="popup_bar">
				<div id="popup_text_container">
					<h1>Title</h1>
					<h2>Give your video a title.</h2>
				</div>
				<input type="text" class="popup_textbox" name="inputtitle"></input>
			</div>
			<div id="popup_bar">
				<div id="popup_text_container">
					<h1>URL</h1>
					<h2>Copy and paste a Youtube URL.</h2>
				</div>
				<input type="text" class="popup_textbox" name="inputurl"></input>
			</div>
			<div id="popup_bar">
				<div id="popup_text_container">
					<h1>Description</h1>
					<h2>Give your video a description.</h2>
				</div>
				<textarea class="popup_textarea" name="inputdescription">Write a description here.</textarea>
			</div>
			<input type="submit" value="Share" class="popup_submit"/><br>
		</form>
		<!-- End form -->
		
		<!-- End popup input -->
	</div>
</div>
<!-- Popup video end-->


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
		<div id="profile_container" style="background-image:url(<?php $profile = getCurrentProfilePic(); echo $profile ?>)">
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
					<a href="home.php?home=home">
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
					<a href="#" onclick="popup('popup_container_video')">
					<div id="mh_bar_icon" style="background-image:url(images/video_icon.png);">
						<div id="mh_bar_icon_text">Video</div>
					</div>
					</a>
				</div>
				<!-- Messages -->
				<div id="mh_bar_icon_container">
					<a href="home.php?home=messages">
					<div id="mh_bar_icon" style="background-image:url(images/message_icon.png);">
						<div id="mh_bar_icon_text">Messages</div>
					</div>
					</a>
				</div>
				<!-- Friends -->
				<div id="mh_bar_icon_container">
					<a href="home.php?home=friends">
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
					<?php echo "<a href=\"home.php?home=$username\">$username</a>" ?>
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
					echo "<h2><a href=home.php?home=" . $friends[$i] . ">" . $friends[$i] . "</a></h2><br>";
				}
			?>
		</div>
		<!-- Friends container end -->
		
		<!-- Datagrams start -->
		<?php

		function printMessageOptions() {
			global $friends;
			for ($i = 0; $i < sizeof($friends); $i++) {
				echo "<option>$friends[$i]</option>";
			}
		}
		
		$dg_refs = get_dg_refs();
		
		if(isset($_GET['home'])) {
			$_SESSION['home_status'] = $_GET['home'];
		}
		
		$status = $_SESSION['home_status'];
		
		if ($status == "home") {
			printDatagrams($dg_refs);
		} else if ($status == "friends") {
			// DO NOTHING
			echo "<h2>Friends</h2>";
			echo "<div class=\"friend_add_icon\">+<div class=\"friend_add_icon_text\">Add Friend</div></div><br>";
			echo "
				<form class=\"friends_add_container\" action=\"addfriend.php\" name=\"friend\" method=\"post\">
					<input type=\"text\" name=\"addfriend\" class=\"friend_add_textbox\" method=\"post\"></input>
					<h2>Enter in a username to add:</h2>
					<input type=\"submit\" value=\"Submit\" class=\"friend_add_button\" />
				</form>
			";
			printFriends();
		} else if ($status == "messages") {
			echo "<h2>Messages</h2>";
			echo "<div class=\"message_compose_icon\">+<div class=\"message_compose_icon_text\">Compose</div></div>";
			echo "
			<div class=\"compose_container\">
				<h1 style=\"margin-left:210px\">Create a Message</h1><br>
				<form action=\"postmessage.php\" method=\"post\" class=\"message_form\">
				<div class=\"message_form_left\">
					<h2>Recipient</h2>
					<select name=\"receiver_list\" class=\"message_textbox\">'
				";
				printMessageOptions();
			echo "
					</select>
				</div>
				<div class=\"message_form_right\">
					<h2>Title</h2>
					<h3>Give your message a title</h3>
					<input type=\"text\" name=\"messagetitle\" class=\"message_textbox\"></input>
					<h2>Message</h2>
					<h3>Type your message here</h3>
					<textarea type=\"text\" name=\"message\" class=\"message_textarea\"></textarea>
					<input type=\"submit\" value=\"Send\" class=\"message_submit\" />
				</div>
				</form>
			</div>
			";
			$message_refs = get_message_refs();
			printMessages($message_refs);
		} else {
			// print username datagrams that was clicked
			printUserDatagrams($status);
		}
		
		?>	
		
		<!-- Datagrams end -->
		
	</div>
	<!-- Main body end -->
</div>

<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="comments.js"></script>
<script type="text/javascript" src="csspopup.js"></script>
<script type="text/javascript" src="messages.js"></script>
<script type="text/javascript" src="friends.js"></script>
</body>
</html>