<!DOCTYPE html>
<html>
<head>
<script src="jquery-1.10.2.min.js">
</script>
<style>
div#test {
	background-color:black;
	width:500px;
	height:600px;
}
</style>
<script>
$(document).ready(function(){
	if $('#test').height() > $('#img').height() {
		$('#img').height('100px');
	}
});
</script>
</head>

<body>
<div id="test">
	<img id="img" src="http://l2.yimg.com/bt/api/res/1.2/JP9aTpASyLTC2AOIHHdStA--/YXBwaWQ9eW5ld3M7Zmk9aW5zZXQ7aD00MjA7cT04NTt3PTYzMA--/http://l.yimg.com/os/152/2013/02/25/0-CATERS-Diver-Takes-A-School-Photo-01-jpg_215006.jpg"></img>
</div>
</body>
</html>
