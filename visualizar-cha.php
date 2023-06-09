<?php
ini_set('max_execution_time', 0);
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');


$tpl = new TemplatePower('./tpl/default.htm');
$tpl->assignInclude('content', './tpl/visualizar-cha.htm');
$tpl->prepare();

include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$id = $_GET['id'];
$status_visualizar = $_GET['status'];


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

$sql = "SELECT * FROM dedstudio13_cha_produtos where id_cha=$id";
$query = $dba->query($sql);
$qntd = $dba->rows($query);


if ($qntd > 0) {
	for ($i = 0; $i <= $qntd; $i++) {

		$vet = $dba->fetch($query);

		$id_prod = $vet[1];


		if (!empty($id_prod)) {
			$sql2 = "SELECT * FROM dedstudio13_produtos where id=$id_prod";
			$query2 = $dba->query($sql2);
			$qntd2 = $dba->rows($query2);

			if ($qntd2 > 0) {
				for ($i = 0; $i < $qntd2; $i++) {
					$vet2 = $dba->fetch($query2);
					$tpl->newBlock('produtos');
					$tpl->assign('id', $vet2[0]);

					$tpl->assign('nome', stripslashes($vet2[2]));
					$tpl->assign('preco',  moeda(intval($vet2[3])));

					$id_item = $vet2[0];
					$preco = $vet2[3];
					
					$sql23 = "SELECT * FROM dedstudio13_codigo_de_barras where id=$vet2[0]";
					$query23 = $dba->query($sql23);
					$qntd23 = $dba->rows($query23);

					if ($qntd23 > 0) {
						$vet23 = $dba->fetch($query23);
						$tpl->assign('codigo_sistema', stripslashes($vet23['codigo']));
					}
				}

				
				$sql3 = "SELECT quantidade FROM dedstudio13_cha_produtos where id_produto=$id_prod AND id_cha=$id"; 
				$query3 = $dba->query($sql3);
				$qntd3 = $dba->rows($query3);
				if($qntd3 > 0){
					$vet3 = $dba->fetch($query3);
					$tpl->assign('quantidade',$vet3['quantidade']);
				}
			}

			if ($vet[3] != 1) {
				$tpl->assign('status', 'Disponivel');
				if ($status_visualizar == 1) {
					$tpl->assign('status_act', ' <a href="javascript:void(0)" onclick="comprarProd(' . $id_item . ',' . $id . ',' . $status_visualizar . ')"
				data-toggle="tooltip" title="baixa"
				class="btn btn-success">Comprar</a>
				<a href="javascript:void(0)" onclick="comprarProd(' . $id_item . ',' . $id . ',' . $status_visualizar . ')"
				data-toggle="tooltip" title="baixa"
				class="btn btn-danger">Excluir</a>');
				}
			} else {
				$tpl->assign('status', 'Indisponivel');
				$tpl->assign('status_act', ' <input type="hidden" name="" value="" />');
				$desconto = $desconto + ($preco * $valor_de_desconto);
				$preco = 0;
			}
		}
	}
}

if ($status_visualizar == 0) {
	$tpl->newBlock('bonifica');
	$tpl->assignGlobal('bonificado', moeda($desconto));
}
$tpl->assignGlobal('id_cha', $_GET['id']);
$tpl->printToScreen();
