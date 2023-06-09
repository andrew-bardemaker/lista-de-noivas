<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');

$tpl = new TemplatePower('./tpl/default.htm');
$tpl->assignInclude('content', './tpl/dashboard.htm');
$tpl->prepare();
$tpl -> assignGlobal('ativaPainel','menu-ativo');


+
//--------------------------
include_once('./inc/inc.mensagens.php');

//--------------------------

$tpl->printToScreen();
?>