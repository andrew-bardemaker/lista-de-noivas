<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');


$tpl = new TemplatePower('./tpl/default.htm');
$tpl->assignInclude('content', './tpl/usuarios.htm');
$tpl->prepare();
$tpl -> assignGlobal('ativaUsuario','menu-ativo');
include_once('./inc/inc.mensagens.php'); //mensagens e alerts


$sql = "SELECT * FROM dedstudio13_usuarios order by nome asc";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('usuarios');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('nome', stripslashes($vet[3]));
        $tpl->assign('email', stripslashes($vet[5]));
        $tpl->assign('telefone', stripslashes($vet[6]));
		$tpl->assign('unidade', stripslashes($vet[8]));
        
        
	

		$status = $vet[7];
		if ($status == 0) {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarFotografia('.$vet[0].',\'ativar_fotografia\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: status inativo" class="tooltips"><i class="fa fa-long-arrow-down"></i></a>');
		}else{
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarFotografia('.$vet[0].',\'desativar_fotografia\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: status ativo" class="tooltips"><i class="fa fa-check"></i></a>');
		}
	}
} 
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
?>