<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');


$tpl = new TemplatePower('./tpl/default.htm');
$tpl->assignInclude('content', './tpl/produtos.htm');
$tpl->prepare();

$tpl->assignGlobal('ativaProdutos', 'menu-ativo');
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

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



$tpl->printToScreen();
