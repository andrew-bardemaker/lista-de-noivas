<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');


$tpl = new TemplatePower('./tpl/default.htm');
$tpl->assignInclude('content', './tpl/unidades.htm');
$tpl->prepare();


$tpl -> assignGlobal('ativaUnidades','menu-ativo');
include_once('./inc/inc.mensagens.php'); //mensagens e alerts


$sql = "SELECT * FROM dedstudio13_unidades";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
    for ($i = 0; $i < $qntd; $i++) {
        $vet = $dba->fetch($query);
        $tpl->newBlock('unidade');
        $tpl->assign('id', $vet[0]);
        $tpl->assign('unidade', stripslashes($vet[1]));
    }
}


$tpl->printToScreen();
