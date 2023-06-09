<?php
require_once('../inc/inc.configdb.php');


$sql = "SELECT id, titulo, categoria, destaque FROM design WHERE categoria = 'Website' ORDER BY titulo";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		/*
		$tpl->newBlock('design');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('titulo', stripslashes($vet[1]));
		$tpl->assign('categoria', stripslashes($vet[2]));

		$destaque = $vet[3];
		if ($destaque == 0) {
			$tpl->assign('destaque', '<a href="javascript:void(0)" onclick="ativarDesign('.$vet[0].',\'ativar_design\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: Destaque inativo" class="tooltips"><i class="fa fa-long-arrow-down"></i></a>');
		}else{
			$tpl->assign('destaque', '<a href="javascript:void(0)" onclick="desativarDesign('.$vet[0].',\'desativar_design\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: Destaque ativo" class="tooltips"><i class="fa fa-check"></i></a>');
		}
		*/
		$id = $vet[0];

		if (file_exists('../img/design/'.$id.'.jpg')) {
			rename("../img/design/".$id.".jpg", "../img/tecnologia/".$id.".jpg");
			//unlink("../img/design/".$id.".jpg");
		}

		if (file_exists('../img/design/'.$id.'_900x600.jpg')) {
			rename("../img/design/".$id."_900x600.jpg", "../img/tecnologia/".$id."_900x600.jpg");
			//unlink("../img/design/".$id."_900x600.jpg");
		}

		if (file_exists('../img/design/'.$id.'_1.jpg')) {
			rename("../img/design/".$id."_1.jpg", "../img/tecnologia/".$id."_1.jpg");
			//unlink("../img/design/".$id."_1.jpg");
		}

		if (file_exists('../img/design/'.$id.'_2.jpg')) {
			rename("../img/design/".$id."_2.jpg", "../img/tecnologia/".$id."_2.jpg");
			//unlink("../img/design/".$id."_2.jpg");
		}

		if (file_exists('../img/design/'.$id.'_3.jpg')) {
			rename("../img/design/".$id."_3.jpg", "../img/tecnologia/".$id."_3.jpg");
			//unlink("../img/design/".$id."_3.jpg");
		}

		if (file_exists('../img/design/'.$id.'_4.jpg')) {
			rename("../img/design/".$id."_4.jpg", "../img/tecnologia/".$id."_4.jpg");
			//unlink("../img/design/".$id."_4.jpg");
		}


		if (file_exists('../img/design/'.$id.'_5.jpg')) {
			rename("../img/design/".$id."_5.jpg", "../img/tecnologia/".$id."_5.jpg");
			//unlink("../img/design/".$id."_5.jpg");
		}
	}
} 


?>