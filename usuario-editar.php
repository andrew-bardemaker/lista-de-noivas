<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.htm');
$tpl->assignInclude('content', './tpl/usuario-editar.htm');
$tpl->prepare();

// Verificação de acesso página e permissões
// $id_pagina_permissao = 21;
// $act_permissao_editar = true;
// require_once('./inc/inc.verificaacessopermissao.php');
 
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

$sql2   = "SELECT * FROM dedstudio13_usuarios WHERE idusuarios = $id";
$query2 = $dba->query($sql2);
$qntd2  = $dba->rows($query2);
if ($qntd2 > 0) {
	$vet2 = $dba->fetch($query2);
	$tpl->assign('id', $vet2['idusuarios']);
	$tpl->assign('nome', stripslashes($vet2['nome']));
	$tpl->assign('cpf', $vet2['cpf']);
	$tpl->assign('email', stripslashes($vet2['email']));  
	$tpl->assign('telefone', $vet2['telefone']); 
    $tpl->assign('unidade_pessoa', $vet2['unidade']); 
	
}

//--------------------------
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