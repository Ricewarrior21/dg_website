<?php
session_start();
session_destroy();
echo "Logged out!";
header('Refresh: 1; url=index.php');
?>