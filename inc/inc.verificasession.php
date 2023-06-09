<?php
session_start();
if (!isset($_SESSION['usercod'])) {
	header('location: login.php?msg=456');
}
?>