<?php
ini_set('max_execution_time', 0);
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');


$tpl = new TemplatePower('./tpl/default.htm');
$tpl->assignInclude('content', './tpl/cha.htm');
$tpl->prepare();

$tpl->assignGlobal('ativaEventos', 'menu-ativo');
include_once('./inc/inc.mensagens.php'); //mensagens e alerts


$sql = "SELECT * FROM dedstudio13_cha where status = 1";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i = 0; $i < $qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('cha');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('noivo', stripslashes($vet['noivo']));
		$tpl->assign('noiva', stripslashes($vet['noiva']));
		$tpl->assign('loja', stripslashes($vet['loja']));
		$tpl->assign('datafim', stripslashes(dataBR($vet['data_fim'])));
		$tpl->assign('desconto', stripslashes($vet['desconto']));

		$status = $vet['status'];
		$tpl->assign('status_visualizar', stripslashes($vet['status']));
		if ($status == 0) { // Verifica status
			$tpl->assign('ativa', '<a href="javascript:void(0)" onclick="ativarCuppom(' . $vet['id'] . ',\'ativar_cuppom\', \'' . $vet['noivo'] . '\')" data-toggle="tooltip" title="cha ativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>');
		} else {
			$tpl->assign('ativa', '<a href="javascript:void(0)" onclick="desativarCuppom(' . $vet['id'] . ',\'desativar_cuppom\', \'' . $vet['noivo'] . '\')" data-toggle="tooltip" title="cha inativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>');
		}
	}
}

$sql = "SELECT * FROM dedstudio13_cha where status = 0";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i = 0; $i < $qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('cha2');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('noivo', stripslashes($vet['noivo']));
		$tpl->assign('noiva', stripslashes($vet['noiva']));
		$tpl->assign('loja', stripslashes($vet['loja']));
		$tpl->assign('datafim', stripslashes(dataBR($vet['data_fim'])));
		$tpl->assign('desconto', stripslashes($vet['desconto']));

		$status = $vet['status'];
		$tpl->assign('status_visualizar', stripslashes($vet['status']));
		if ($status == 0) { // Verifica status
			$tpl->assign('ativa', '<a href="javascript:void(0)" onclick="ativarCuppom(' . $vet['id'] . ',\'ativar_cuppom\', \'' . $vet['noivo'] . '\')" data-toggle="tooltip" title="cha ativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>');
		} else {
			$tpl->assign('ativa', '<a href="javascript:void(0)" onclick="desativarCuppom(' . $vet['id'] . ',\'desativar_cuppom\', \'' . $vet['noivo'] . '\')" data-toggle="tooltip" title="cha inativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>');
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
		$tpl->assign('id', $vet[1]);
	}
}

$lastid=0;

$sql = "SELECT * FROM dedstudio13_cha";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i = 0; $i < $qntd; $i++) {
		$vet = $dba->fetch($query);
		$lastid = $vet['id'];
	}
}
$lastid += 1;
$tpl->assignGlobal('idcha', $lastid);

$tpl->printToScreen();
