<?php
session_start();
require_once('./inc/class.TemplatePower.php');

$tpl = new TemplatePower('./tpl/login.htm');
$tpl->prepare();

include_once('./inc/inc.mensagens.php');

if (isset($_SESSION['usercod'])) {
	header('location: ./dashboard');
}


$tpl->printToScreen();
?>