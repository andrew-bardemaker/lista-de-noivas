<?php
ini_set('max_execution_time', 0);
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');


$tpl = new TemplatePower('./tpl/default.htm');
$tpl->assignInclude('content', './tpl/adicionar-produtos-cha.htm');
$tpl->prepare();

include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$id = $_GET['id'];
if(empty($_GET['status'])){
	$status_visualizar = 1;
}else{

	$status_visualizar = $_GET['status'];
}

$limit = 25;
$page = isset($_GET['page']) ?  $_GET['page'] : 1;
$busca = '';
if (!empty($_GET['busca'])) {

    $busca = isset($_GET['busca']) ?  $_GET['busca'] : '';

    if ($busca == 0) {
        $busca = 'where nome like "%' . $_GET['busca'] . '%"';
    } else if ($busca > 0) {
        $busca = 'where codigo_sistema like "%' . $_GET['busca'] . '%"';
    }
}


if ($page == 0) {
    $page = 1;
}

$tpl->assignGlobal('page', $page);
$start = ($page - 1) * $limit;

$previous = $page - 1;
$tpl->assignGlobal('previous', $previous);

$next = $page + 1;
$tpl->assignGlobal('next', $next);



$tpl->assignGlobal('idcha', $id);
$desconto = 0;

$sql = "SELECT * FROM dedstudio13_cha where id=$id";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i = 0; $i < $qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('cha');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('noivo', stripslashes($vet['noivo']));
		$tpl->assign('noiva', stripslashes($vet['noiva']));
		$tpl->assign('tell1', stripslashes($vet['telefone1']));
		$tpl->assign('tell2', stripslashes($vet['telefone2']));
		$tpl->assign('email1', stripslashes($vet['email1']));
		$tpl->assign('email2', stripslashes($vet['email2']));
		$tpl->assign('loja', stripslashes($vet['loja']));
		$tpl->assign('data_fim', dataBR($vet['data_fim']));
		$tpl->assign('unidade', stripslashes($vet['loja']));
		$valor_de_desconto = $vet['desconto'] / 100;

		$status = $vet['status'];
		$tpl->assign('status_visualizar', stripslashes($vet['status']));
		if ($status == 0) { // Verifica status


		} else {
			$tpl->assign('ativa', '<a href="javascript:void(0)" onclick="desativarCuppom(' . $vet['id'] . ',\'desativar_cuppom\', \'' . $vet['noivo'] . '\')" data-toggle="tooltip" title="fechar evento" class="btn btn-default mr5 mb10"><img src="images/listas_fechadas.png" style="width: 20px; margin-right: 5px;" alt="">Fechar Evento</a>');
		}
	}
}

$sql = "SELECT * FROM dedstudio13_produtos  $busca LIMIT $start, $limit";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i = 0; $i < $qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('produtos');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('codigo_sistema', $vet[1]);
		$tpl->assign('nome', stripslashes($vet[2]));
		$tpl->assign('preco', moeda(intval($vet[3])));
		$codigo = $vet[0];
	}
}


$tpl->assignGlobal('id_cha', $_GET['id']);
$tpl->printToScreen();
