<?php
if (isset($_FILES['inputimage'])) {
	$errors = array();

	$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
	$file_name = $_FILES['image']['name'];
	$file_ext = strtolower(end(explode('.', $file_name)));
	$file_size = $_FILES['image']['size'];
	$file_tmp = $_FILES['image']['tmp_name'];
	
	if (in_array($file_ext, $allowed_ext) == false) {
		$errors[] = 'Extension not allowed';
	}
	
	if ($file_size > 2097152 || $file_size == 0) {
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
		}
	}
}

?>

<form action="imgupload.php" method="POST" enctype="multipart/form-data">
	<p>
		<input type="file" name="image" />
		<input type="submit" value="Upload" />
	</p>
</form>