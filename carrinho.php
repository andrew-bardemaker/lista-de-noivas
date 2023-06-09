<?php
/**
 * @author			Andrew Bardemaker - v 1.0 - mai/2018
 * @description 	Serviço de retorno das notificações usuário
 * @params

 */

header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Credentials: true');
header('Content-type: application/json');
header('X-Content-Type-Options: nosniff');

include('../admin/inc/inc.configdb.php');
include('../admin/inc/inc.lib.php');
include('../admin/inc/class.ValidaCpfCnpj.php');
include('./verificatoken.php');

$array = array();

$json  = file_get_contents('php://input');

$obj   = json_decode($json); // var_dump($obj);

if ($obj === null) {
	$array = array("error" => "true", "type" => "format_json");
	echo json_encode($array);
	exit;
}

if (!empty($obj->id_usuario) && isset($obj->id_usuario) && is_numeric($obj->id_usuario) && 
	!empty($obj->token) && isset($obj->token) &&
	!empty($obj->action) && isset($obj->action)) {

	$id_usuario  = addslashes($obj->id_usuario);
	$token       = addslashes($obj->token);
	$action      = addslashes($obj->action);

	$verificatoken = verificaToken($token, $id_usuario);
	if ($verificatoken === false) {
		$array = array("error" => "true", "type" => "token_invalido");
		header('Content-type: application/json');
		echo json_encode($array);
		exit;
	}

	if ($action == "criar") {

		$sql = "INSERT INTO proclube_viavarejo_carrinho (id_usuario, data_hora_registro) VALUES ('$id_usuario', NOW())"; //die($sql);
        $dba->query($sql);

        $id_carrinho = $dba->lastid();

		$array = array("success" => "true", "id_carrinho" => $id_carrinho);

	} elseif ($action == "add_produto") {
		
		if (empty($obj->id_loja) && !is_numeric($obj->id_loja)) { 
			$array = array("error" => "true", "type" => "id_loja");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		} 	

		if (empty($obj->sku) && !is_numeric($obj->sku)) { 
			$array = array("error" => "true", "type" => "sku");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->id_carrinho) && !is_numeric($obj->id_carrinho)) { 
			$array = array("error" => "true", "type" => "id_carrinho");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		} 

		$id_loja     = $obj->id_loja;
		$codigo_sku  = $obj->sku;
		$id_carrinho = $obj->id_carrinho;

		$sql2   = "SELECT * FROM proclube_viavarejo_carrinho_produtos WHERE codigo_sku='$codigo_sku' AND id_loja='$id_loja' AND id_carrinho='$id_carrinho'"; // print_r($sql);
		$query2 = $dba->query($sql2);
		$qntd2  = $dba->rows($query2);
		if ($qntd2 == 0) {

			$sql1 = "INSERT INTO proclube_viavarejo_carrinho_produtos (codigo_sku, qntd, id_loja, id_carrinho) VALUES ('$codigo_sku', 1, '$id_loja', '$id_carrinho')";
			$dba->query($sql1);

		} else {
			$sql1 = "UPDATE proclube_viavarejo_carrinho_produtos SET qntd = qntd+1 WHERE codigo_sku='$codigo_sku' AND id_loja='$id_loja' AND id_carrinho='$id_carrinho'";
			$dba->query($sql1);

		}

		$array = array("success" => "true", "id_carrinho" => $id_carrinho);
	
	} elseif ($action == "up_produto") {
		
		if (empty($obj->id_loja) && !is_numeric($obj->id_loja)) { 
			$array = array("error" => "true", "type" => "id_loja");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		} 	

		if (empty($obj->sku) && !is_numeric($obj->sku)) { 
			$array = array("error" => "true", "type" => "sku");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->id_carrinho) && !is_numeric($obj->id_carrinho)) { 
			$array = array("error" => "true", "type" => "id_carrinho");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		} 

		if (empty($obj->qntd) && is_numeric($obj->qntd)) { 
			$array = array("error" => "true", "type" => "qntd");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		$id_loja     = $obj->id_loja;
		$codigo_sku  = $obj->sku;
		$id_carrinho = $obj->id_carrinho;
		$qntd        = $obj->qntd;

		$sql1 = "UPDATE proclube_viavarejo_carrinho_produtos SET qntd = '$qntd' WHERE codigo_sku='$codigo_sku' AND id_loja='$id_loja' AND id_carrinho='$id_carrinho'";
		$dba->query($sql1);

		$total_carrinho = 0;
		$array_produtos = array();

		$sql   = "SELECT pontuacao, valor FROM proclube_viavarejo_resgate WHERE id = 1"; // print_r($sql);
		$query = $dba->query($sql);
		$vet   = $dba->fetch($query);
		$pontuacao = $vet[0];
		$valor     = $vet[1];

		$sql2   = "SELECT 
				   vp.codigo, 
				   vp.titulo, 
				   vps.codigo_sku, 
				   (vps.preco*$pontuacao)/$valor AS pontos, 
				   vp.id_categoria, 
				   vp.foto_grande, 
				   vp.foto_media, 
				   vp.foto_pequena,
				   vps.modelo,
				   vps.id_loja,
				   vcp.qntd,
				   vps.imagem_menor,
			   	   vps.imagem_maior,
			   	   vps.imagem_zoom
				   FROM proclube_viavarejo_carrinho_produtos AS vcp
				   INNER JOIN proclube_viavarejo_produtos_skus AS vps
				   INNER JOIN proclube_viavarejo_produtos AS vp
				   WHERE vcp.codigo_sku = vps.codigo_sku
				   AND vps.codigo_produto = vp.codigo
			   	   AND vp.id_loja = vps.id_loja
			   	   AND vcp.id_loja = vps.id_loja
			   	   AND vcp.id_loja = vp.id_loja
				   AND vcp.id_carrinho = $id_carrinho";
		// print_r($sql2);
		$query2 = $dba->query($sql2);
		$qntd2  = $dba->rows($query2);
		if ($qntd2 > 0) {	
			for ($j=0; $j<$qntd2; $j++) {
				$vet2           = $dba->fetch($query2);
				$produto_codigo = $vet2[0];
				$produto_titulo = $vet2[1];
				$produto_sku    = $vet2[2];
				// $produto_pontos = $vet2[3];
				$produto_pontos = round(floatval($vet2[3]));
				$id_loja        = $vet2[9];
				$qntd           = $vet2[10];

				if ($vet[8] != ".") {
					$produto_titulo = $vet2[1]." ".$vet2[8];
				}		

				$foto_grande  = $vet2[13];
				$foto_media   = $vet2[12];
				$foto_pequena = $vet2[11];					
				
				$array_produtos[] = array('sku' => $produto_sku, 'codigo' => $produto_codigo, 'titulo' => $produto_titulo, 'pontos' => $produto_pontos, 'qntd' => $qntd, 'foto_grande' => $foto_grande, 'foto_media' => $foto_media, 'foto_pequena' => $foto_pequena, 'id_loja' => $id_loja);

				$total_carrinho = $total_carrinho+($produto_pontos*$qntd);
			}
		}

		$sql79   = "SELECT app_pontos FROM proclube_usuarios WHERE id = $id_usuario"; // print_r($sql);
		$query79 = $dba->query($sql79);
		$vet79   = $dba->fetch($query79);		
		$total_pontos = $vet79[0];

		$array = array("success" => "true", "id_carrinho" => $id_carrinho, "total_pontos" => $total_pontos, "produtos" => $array_produtos, "total_carrinho" => $total_carrinho);
	
	} elseif ($action == "del_produto") {
		
		if (empty($obj->id_loja) && !is_numeric($obj->id_loja)) { 
			$array = array("error" => "true", "type" => "id_loja");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		} 	

		if (empty($obj->sku) && !is_numeric($obj->sku)) { 
			$array = array("error" => "true", "type" => "sku");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->id_carrinho) && is_numeric($obj->id_carrinho)) { 
			$array = array("error" => "true", "type" => "id_carrinho");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		$id_loja     = $obj->id_loja;
		$codigo_sku  = $obj->sku;
		$id_carrinho = $obj->id_carrinho;

		$sql1 = "DELETE FROM proclube_viavarejo_carrinho_produtos WHERE codigo_sku='$codigo_sku' AND id_loja='$id_loja' AND id_carrinho='$id_carrinho'";
		$dba->query($sql1);		

		$total_carrinho = 0;
		$array_produtos = array();

		$sql   = "SELECT pontuacao, valor FROM proclube_viavarejo_resgate WHERE id = 1"; // print_r($sql);
		$query = $dba->query($sql);
		$vet   = $dba->fetch($query);
		$pontuacao = $vet[0];
		$valor     = $vet[1];

		$sql2   = "SELECT 
				   vp.codigo, 
				   vp.titulo, 
				   vps.codigo_sku, 
				   (vps.preco*$pontuacao)/$valor AS pontos, 
				   vp.id_categoria, 
				   vp.foto_grande, 
				   vp.foto_media, 
				   vp.foto_pequena,
				   vps.modelo,
				   vps.id_loja,
				   vcp.qntd,
				   vps.imagem_menor,
			   	   vps.imagem_maior,
			   	   vps.imagem_zoom
				   FROM proclube_viavarejo_carrinho_produtos AS vcp
				   INNER JOIN proclube_viavarejo_produtos_skus AS vps
				   INNER JOIN proclube_viavarejo_produtos AS vp
				   WHERE vcp.codigo_sku = vps.codigo_sku
				   AND vps.codigo_produto = vp.codigo
			   	   AND vp.id_loja = vps.id_loja
			   	   AND vcp.id_loja = vps.id_loja
			   	   AND vcp.id_loja = vp.id_loja
				   AND vcp.id_carrinho = $id_carrinho";
		// print_r($sql2);
		$query2 = $dba->query($sql2);
		$qntd2  = $dba->rows($query2);
		if ($qntd2 > 0) {	
			for ($j=0; $j<$qntd2; $j++) {
				$vet2           = $dba->fetch($query2);
				$produto_codigo = $vet2[0];
				$produto_titulo = $vet2[1];
				$produto_sku    = $vet2[2];
				// $produto_pontos = $vet2[3];
				$produto_pontos = round(floatval($vet2[3]));
				$id_loja        = $vet2[9];
				$qntd           = $vet2[10];

				if ($vet[8] != ".") {
					$produto_titulo = $vet2[1]." ".$vet2[8];
				}				

				$foto_grande  = $vet2[13];
				$foto_media   = $vet2[12];
				$foto_pequena = $vet2[11];			
				
				$array_produtos[] = array('sku' => $produto_sku, 'codigo' => $produto_codigo, 'titulo' => $produto_titulo, 'pontos' => $produto_pontos, 'qntd' => $qntd, 'foto_grande' => $foto_grande, 'foto_media' => $foto_media, 'foto_pequena' => $foto_pequena, 'id_loja' => $id_loja);

				$total_carrinho = $total_carrinho+($produto_pontos*$qntd);
			}
		}

		$sql79   = "SELECT app_pontos FROM proclube_usuarios WHERE id = $id_usuario"; // print_r($sql);
		$query79 = $dba->query($sql79);
		$vet79   = $dba->fetch($query79);		
		$total_pontos = $vet79[0];

		$array = array("success" => "true", "id_carrinho" => $id_carrinho, "total_pontos" => $total_pontos, "produtos" => $array_produtos, "total_carrinho" => $total_carrinho);
	
	} elseif ($action == "list_produtos") {
		
		if (empty($obj->id_carrinho) && is_numeric($obj->id_carrinho)) { 
			$array = array("error" => "true", "type" => "id_carrinho");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		$id_carrinho    = $obj->id_carrinho;
		$total_carrinho = 0;
		$array_produtos = array();

		$sql   = "SELECT pontuacao, valor FROM proclube_viavarejo_resgate WHERE id = 1"; // print_r($sql);
		$query = $dba->query($sql);
		$vet   = $dba->fetch($query);
		$pontuacao = $vet[0];
		$valor     = $vet[1];

		$sql2   = "SELECT 
				   vp.codigo, 
				   vp.titulo, 
				   vps.codigo_sku, 
				   (vps.preco*$pontuacao)/$valor AS pontos, 
				   vp.id_categoria, 
				   vp.foto_grande, 
				   vp.foto_media, 
				   vp.foto_pequena,
				   vps.modelo,
				   vps.id_loja,
				   vcp.qntd,
				   vps.imagem_menor,
			   	   vps.imagem_maior,
			   	   vps.imagem_zoom
				   FROM proclube_viavarejo_carrinho_produtos AS vcp
				   INNER JOIN proclube_viavarejo_produtos_skus AS vps
				   INNER JOIN proclube_viavarejo_produtos AS vp
				   WHERE vcp.codigo_sku = vps.codigo_sku
				   AND vps.codigo_produto = vp.codigo
			   	   AND vp.id_loja = vps.id_loja
			   	   AND vcp.id_loja = vps.id_loja
			   	   AND vcp.id_loja = vp.id_loja
				   AND vcp.id_carrinho = $id_carrinho";
		// print_r($sql2);
		$query2 = $dba->query($sql2);
		$qntd2  = $dba->rows($query2);
		if ($qntd2 > 0) {	
			for ($j=0; $j<$qntd2; $j++) {
				$vet2           = $dba->fetch($query2);
				$produto_codigo = $vet2[0];
				$produto_titulo = $vet2[1];
				$produto_sku    = $vet2[2];
				// $produto_pontos = $vet2[3];
				$produto_pontos = round(floatval($vet2[3]));
				$id_loja        = $vet2[9];
				$qntd           = $vet2[10];

				if ($vet[8] != ".") {
					$produto_titulo = $vet2[1]." ".$vet2[8];
				}				

				$foto_grande  = $vet2[13];
				$foto_media   = $vet2[12];
				$foto_pequena = $vet2[11];			
				
				$array_produtos[] = array('sku' => $produto_sku, 'codigo' => $produto_codigo, 'titulo' => $produto_titulo, 'pontos' => $produto_pontos, 'qntd' => $qntd, 'foto_grande' => $foto_grande, 'foto_media' => $foto_media, 'foto_pequena' => $foto_pequena, 'id_loja' => $id_loja);

				$total_carrinho = $total_carrinho+($produto_pontos*$qntd);
			}
		}

		$sql79   = "SELECT app_pontos FROM proclube_usuarios WHERE id = $id_usuario"; // print_r($sql);
		$query79 = $dba->query($sql79);
		$vet79   = $dba->fetch($query79);		
		$total_pontos = $vet79[0];

		$array = array("success" => "true", "id_carrinho" => $id_carrinho, "total_pontos" => $total_pontos, "produtos" => $array_produtos, "total_carrinho" => $total_carrinho);

	} elseif ($action == "calcula_carrinho") {

		if (empty($obj->id_carrinho) && is_numeric($obj->id_carrinho)) { 
			$array = array("error" => "true", "type" => "id_carrinho");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->cep)) { 
			$array = array("error" => "true", "type" => "cep");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}			

		$id_carrinho    = $obj->id_carrinho;
		$cep            = addslashes($obj->cep);
		// $array_produtos = array();

		$total_frete    = 0;
		$total_produtos = 0;
		$total_pedido   = 0;

		$sql2   = "SELECT 
				   vps.codigo_sku, 
				   vps.preco, 
				   vps.id_loja,
				   vcp.qntd,
				   vp.titulo
				   FROM proclube_viavarejo_carrinho_produtos AS vcp
				   INNER JOIN proclube_viavarejo_produtos_skus AS vps
				   INNER JOIN proclube_viavarejo_produtos AS vp
				   WHERE vcp.codigo_sku = vps.codigo_sku
				   AND vps.codigo_produto = vp.codigo
			   	   AND vp.id_loja = vps.id_loja
			   	   AND vcp.id_loja = vps.id_loja
			   	   AND vcp.id_loja = vp.id_loja
				   AND vcp.id_carrinho = $id_carrinho";
		// print_r($sql2);
		$query2 = $dba->query($sql2);
		$qntd2  = $dba->rows($query2);
		if ($qntd2 > 0) {	
			for ($j=0; $j<$qntd2; $j++) {
				$vet2 = $dba->fetch($query2);
				$produto_preco  = $vet2[1];
				$produto_sku    = $vet2[0];
				$id_loja        = $vet2[2];
				$produto_qntd   = $vet2[3];	
				$produto_titulo = $vet2[4];	

				if ($id_loja == 1) {
					$url         = 'https://api-integracao-b2b.casasbahia.com.br';
					$url_h		 = 'https://b2b-integracao.casasbahia.viavarejo-hlg.com.br';
					$cnpj        = '06.007.513/0001-00';
					$id_campanha = '2723';

				} elseif ($id_loja == 2) {
					$url         = 'https://api-integracao-b2b.extra.com.br';
					$url_h		 = 'https://b2b-integracao.extra.viavarejo-hlg.com.br';
					$cnpj        = '06.007.513/0001-00';
					$id_campanha = '5525';

				} elseif ($id_loja == 3) {
					$url         = 'https://api-integracao-b2b.pontofrio.com.br';
					$url_h		 = 'https://b2b-integracao.pontofrio.viavarejo-hlg.com.br';
					$cnpj        = '06.007.513/0001-00';
					$id_campanha = '5561';

				}

				$produtos[] = array("codigo" => $produto_sku, "quantidade" => $produto_qntd);

				$array_1    = array( "idCampanha" => $id_campanha,
									 "cnpj"       => $cnpj,
									 "cep"        => $cep,
									 "produtos"   => $produtos
									);

				$data_string = json_encode($array_1);

				$tmp_pedido = 0;
				while ($tmp_pedido != 1) {
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url.'/pedidos/carrinho');				
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_POST, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: text/json', 'Authorization: fTC8wD3yPEeliws2hoeIXQ=='));
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
					$response    = curl_exec($curl);
					$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
					// if (curl_errno($curl)) { echo 'Error:' . curl_error($curl); }
					curl_close($curl);
					// print_r($response); 
					// print_r($status_code);

					if ($status_code == 200 || $status_code == 400) {
					    $tmp_pedido = 1;
					    // exit;
					}
				}

				$obj = json_decode($response); // var_dump($obj);

				// Validar a “tag” erro do Carrinho:
				// Caso retorne Erro igual à true, tratar a mensagem de erro retornada e exibir ao usuário.
				// Caso retorne Erro igual à false, verificar a “tag” erro de cada Produto contido no carrinho.
				if ($obj->error->code === null) {
					
					// Validar a “tag” erro do Produto:
					// Caso retorne Erro igual à True, será necessário atualizar o carrinho, excluindo o produto com erro e apresentado a mensagem de erro tratada, sinalizando o produto.
					// Caso retorne Erro igual à False, realizar as validações de preço e quantidade, citadas abaixo.
					// if ($xml->xpath('//b:ListaProdutoCarrinhoDTO//b:Erro')[0]->__toString() == "true") { 
					if ($obj->data->produtos[0]->erro == "true") {
						// $mnsg_erro = $xml->xpath('//b:ListaProdutoCarrinhoDTO//b:MensagemDeErro')[0]->__toString();
						$mnsg_erro = $obj->data->produtos[0]->mensagemDeErro;

						$array = array("error" => "true", "type" => "viavarejo", "mnsg" => $mnsg_erro, "titulo_produto" => $produto_titulo);
						header('Content-type: application/json');
						echo json_encode($array);
						exit;

					}

					// Validar preço informado e preço retornado:
					// Caso o preço retornado no serviço seja diferente, será necessário atualizar a informação de preço no carrinho, sinalizando o usuário.
					// $preco_produto_ =  $xml->xpath('//b:ListaProdutoCarrinhoDTO//b:ValorTotal')[0]->__toString(); 
					$preco_produto_ = $obj->data->produtos[0]->valorTotal;
					$preco_produto_ = str_replace('.', '', $preco_produto_);
					$preco_produto_ = str_replace(',', '.', $preco_produto_);

					if ($preco_produto_ != ($produto_preco*$produto_qntd)) {

						$preco_produto_ = $preco_produto_/$produto_qntd;
						$preco_produto_ = str_replace('.', '', $preco_produto_); //primeiro tira o ponto 1.519,80
						$preco_produto_ = str_replace(',', '.', $preco_produto_); //troca a vírgula por ponto 1519.8
						// exit;

						$sql4 = "UPDATE proclube_viavarejo_produtos_skus SET preco = '$preco_produto_' WHERE codigo_sku = '$produto_sku' AND id_loja = '$id_loja'";
						$dba->query($sql4);

						$array = array("error" => "true", "type" => "viavarejo", "mnsg" => "Pontuação de produto atualizada.", "titulo_produto" => $produto_titulo);
						header('Content-type: application/json');
						echo json_encode($array);
						exit;
					}
						
					// // Validar se a quantidade retornada para o produto confere com a solicitada pelo usuário:
					// $qntd_produto_ = $xml->xpath('//b:ListaProdutoCarrinhoDTO//b:Quantidade')[0]->__toString();
					// if ($qntd_produto_ != $produto_qntd) {

					// 	// Caso a quantidade seja igual à zero, significa que não temos o produto disponível. Atualizar o carrinho, excluindo o produto, sinalizando o usuário. Também será necessário indisponibilizar o produto no catálogo.
					// 	if ($qntd_produto_ == 0) {
					// 		$sql4 = "UPDATE proclube_viavarejo_produtos_skus SET habilitado = 0 WHERE codigo_sku = '$produto_sku' AND id_loja = $id_loja"; // Modifica status habilitado 
					// 		$dba->query($sql4);

					// 		$array = array("error" => "true", "type" => "viavarejo", "mnsg" => "Produto indisponível para entrega.", "titulo_produto" => $produto_titulo);
					// 		header('Content-type: application/json');
					// 		echo json_encode($array);
					// 		exit;

					// 	}

					// 	// Caso a quantidade seja menor, será necessário atualizar a informação no carrinho, sinalizando o usuário quantidade insuficiente do produto em relação à quantidade solicitada.
					// 	if ($qntd_produto_ < $produto_qntd) {
					// 		$array = array("error" => "true", "type" => "viavarejo", "mnsg" => "Quantidade de produto indisponível para entrega. Disponível de ".$qntd_produto_, "titulo_produto" => $produto_titulo);
					// 		header('Content-type: application/json');
					// 		echo json_encode($array);
					// 		exit;
							
					// 	}						
					// }

					// $total_frete_    = $xml->xpath('//a:ValorFrete')[0]->__toString();
					$total_frete_ = $obj->data->valorFrete;
					$total_frete_ = str_replace('.', '', $total_frete_);
					$total_frete_ = str_replace(',', '.', $total_frete_);
					$total_frete  = $total_frete + $total_frete_;

					// $total_produtos_ = $xml->xpath('//a:ValorTotaldosProdutos')[0]->__toString();
					$total_produtos_ = $obj->data->valorTotaldosProdutos;
					$total_produtos_ = str_replace('.', '', $total_produtos_);
					$total_produtos_ = str_replace(',', '.', $total_produtos_);
					$total_produtos  = $total_produtos + $total_produtos_;

					// $total_pedido_ = $xml->xpath('//a:ValorTotaldoPedido')[0]->__toString();
					$total_pedido_ = $obj->data->valorTotaldoPedido;
					$total_pedido  = $total_pedido + $total_pedido_;

					$sql8 = "UPDATE proclube_viavarejo_carrinho_produtos 
							 SET 
							 valor_frete = '$total_frete_', 
							 total_produtos = '$total_produtos_' 
							 WHERE id_carrinho = $id_carrinho 
							 AND codigo_sku = '$produto_sku' 
							 AND id_loja = $id_loja";
					$dba->query($sql8);

				} else { 

					$mnsg_erro   = $obj->error->code;
					$codigo_erro = $obj->error->message;

					$array = array("error" => "true", "type" => "viavarejo", "mnsg" => $codigo_erro.' '.$mnsg_erro, "titulo_produto" => $produto_titulo);
					header('Content-type: application/json');
					echo json_encode($array);
					exit;

				}			
			}			

		} else {
			$array = array("error" => "true", "type" => "erro_carrinho");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		$sql99   = "SELECT pontuacao, valor FROM proclube_viavarejo_resgate WHERE id = 1"; // print_r($sql);
		$query99 = $dba->query($sql99);
		$vet99   = $dba->fetch($query99);
		$pontuacao_resgate = $vet99[0];
		$valor_resgate     = $vet99[1];

		$total_frete    = ($total_frete*$pontuacao_resgate)/$valor_resgate;
		$total_frete    = round(floatval($total_frete));

		$total_produtos = ($total_produtos*$pontuacao_resgate)/$valor_resgate;
		$total_produtos = round(floatval($total_produtos));

		$total_pedido   = ($total_pedido*$pontuacao_resgate)/$valor_resgate;
		$total_pedido   = round(floatval($total_pedido));

		$sql79   = "SELECT app_pontos FROM proclube_usuarios WHERE id = $id_usuario"; // print_r($sql);
		$query79 = $dba->query($sql79);
		$vet79   = $dba->fetch($query79);		
		$total_pontos = $vet79[0];

		$array = array("success" => "true", "id_carrinho" => $id_carrinho, "total_pontos" => $total_pontos, "total_frete" => $total_frete, "total_produtos" => $total_produtos, "total_pedido" => $total_pedido);

	} elseif ($action == "cria_pedido") {

		$data_ini = date('Y-m-d 00:00:00');
		$data_fim = date('Y-m-d 23:59:59');

		$sql9   = "SELECT COUNT(id) FROM proclube_usuarios_extrato WHERE id_usuario = $id_usuario AND tipo_transacao = 2 AND data BETWEEN '$data_ini' AND '$data_fim'";
		$query9 = $dba->query($sql9);
		$vet9   = $dba->fetch($query9);
		if ($vet9[0] > 3) {
			$array = array("error" => "true", "type" => "limite_cadastro",  "mnsg" => "Seu pedido não pode ser finalizado, pois você excedeu seu limite de 3 resgates diário");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->id_carrinho) && is_numeric($obj->id_carrinho)) { 
			$array = array("error" => "true", "type" => "id_carrinho");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->cpf)) { 
			$array = array("error" => "true", "type" => "cpf");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		$cpf = new ValidaCPFCNPJ($obj->cpf); // Cria um objeto sobre a classe
		if (!$cpf->valida()) { // Verifica se o CPF é válido
			$array = array("error" => "true", "type" => "cpf_invalido"); 
		    header('Content-type: application/json');
		    echo json_encode($array);
			exit;
		}

		if (empty($obj->nome)) { 
			$array = array("error" => "true", "type" => "nome");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->cep)) { 
			$array = array("error" => "true", "type" => "cep");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->logradouro)) { 
			$array = array("error" => "true", "type" => "logradouro");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->numero) || !is_numeric($obj->numero)) { 
			$array = array("error" => "true", "type" => "numero");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->estado)) { 
			$array = array("error" => "true", "type" => "estado");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->cidade)) { 
			$array = array("error" => "true", "type" => "cidade");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->bairro)) { 
			$array = array("error" => "true", "type" => "bairro");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->referencia)) { 
			$array = array("error" => "true", "type" => "referencia");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		if (empty($obj->telefone)) { 
			$array = array("error" => "true", "type" => "telefone");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}

		$id_carrinho = $obj->id_carrinho;
		$cpf         = addslashes($obj->cpf);
		$email       = preg_replace("/[^0-9]/", "", $cpf)."@proclubeapp.com.br";
		$nome        = addslashes($obj->nome);
		$cep         = addslashes($obj->cep);
		$logradouro  = addslashes($obj->logradouro);
		$numero      = addslashes($obj->numero);
		$estado      = addslashes($obj->estado);
		$cidade      = addslashes($obj->cidade);
		$bairro      = addslashes($obj->bairro);
		$referencia  = addslashes($obj->referencia);
		$telefone    = addslashes($obj->telefone);

		$complemento = "";
		if (isset($obj->complemento)) {
			$complemento = addslashes($obj->complemento);
		}	

		$sql99   = "SELECT pontuacao, valor FROM proclube_viavarejo_resgate WHERE id = 1"; // print_r($sql);
		$query99 = $dba->query($sql99);
		$vet99   = $dba->fetch($query99);
		$pontuacao = $vet99[0];
		$valor     = $vet99[1];

		$sql2   = "SELECT 
				   vps.codigo_sku, 
				   vps.preco, 
				   vps.id_loja,
				   vcp.qntd,
				   vp.titulo,
				   vcp.valor_frete,
				   (vps.preco*$pontuacao)/$valor AS pontos,
				   vcp.total_produtos,
				   (vcp.valor_frete*$pontuacao)/$valor AS frete_pontos,
				   vp.codigo
				   FROM proclube_viavarejo_carrinho_produtos AS vcp
				   INNER JOIN proclube_viavarejo_produtos_skus AS vps
				   INNER JOIN proclube_viavarejo_produtos AS vp
				   WHERE vcp.codigo_sku = vps.codigo_sku
				   AND vps.codigo_produto = vp.codigo
			   	   AND vp.id_loja = vps.id_loja
			   	   AND vcp.id_loja = vps.id_loja
			   	   AND vcp.id_loja = vp.id_loja
				   AND vcp.id_carrinho = $id_carrinho";
		// print_r($sql2);
		$query2 = $dba->query($sql2);
		$qntd2  = $dba->rows($query2);
		if ($qntd2 > 0) {	
			for ($j=0; $j<$qntd2; $j++) {
				$vet2 = $dba->fetch($query2);
				$produto_sku    = $vet2[0];
				$produto_preco  = $vet2[1];				
				$id_loja        = $vet2[2];
				$produto_qntd   = $vet2[3];	
				$produto_titulo = $vet2[4];	
				$produto_frete  = $vet2[5];
				$produto_pontos = round(floatval($vet2[6]));	
				$produto_total  = $vet2[7];	
				$frete_pontos   = round(floatval($vet2[8]));
				$produto_codigo = $vet2[9];		
				

				$app_pontos = ($produto_pontos*$produto_qntd)+$frete_pontos;
				$app_pontos = round(floatval($app_pontos));

				// Confirma Pedido - ProClube
				$sql9 = "INSERT INTO proclube_resgates (id_usuario, data_hora_registro, app_pontos, bairro, cep, cidade, complemento, estado, logradouro, numero, referencia, telefone, codigo_sku, codigo_produto, preco, titulo_produto, qntd_produto, id_loja, id_carrinho, valor_frete, produto_pontos, frete_pontos, cpf, cpf_entrega, nome_entrega, status_dedstudio_easypoints) VALUES ($id_usuario, NOW(), '$app_pontos', '$bairro', '$cep', '$cidade', '$complemento', '$estado', '$logradouro', '$numero', '$referencia', '$telefone', '$produto_sku', '$produto_codigo', '$produto_preco', '$produto_titulo', '$produto_qntd', '$id_loja', '$id_carrinho', '$produto_frete', '$produto_pontos', '$frete_pontos', '$cpf', '$cpf', '$nome', 1)";
				$dba->query($sql9);

				$id_pedido = $dba->lastid();

				if ($id_loja == 1) {
					$url         = 'https://api-integracao-b2b.casasbahia.com.br';
					$url_h		 = 'https://b2b-integracao.casasbahia.viavarejo-hlg.com.br';
					$cnpj        = '06.007.513/0001-00';
					$id_campanha = '2723';

				} elseif ($id_loja == 2) {
					$url         = 'https://api-integracao-b2b.extra.com.br';
					$url_h		 = 'https://b2b-integracao.extra.viavarejo-hlg.com.br';
					$cnpj        = '06.007.513/0001-00';
					$id_campanha = '5525';

				} elseif ($id_loja == 3) {
					$url         = 'https://api-integracao-b2b.pontofrio.com.br';
					$url_h		 = 'https://b2b-integracao.pontofrio.viavarejo-hlg.com.br';
					$cnpj        = '06.007.513/0001-00';
					$id_campanha = '5561';

				}

				$produtos[] = array(
							    "codigo"     => $produto_sku, 
							    "quantidade" => $produto_qntd, 
							    "precoVenda" => $produto_preco);

				$endereco   = array(
								"cep"         => $cep,
							    "estado"      => $estado,
							    "logradouro"  => $logradouro,
							    "cidade"      => $cidade,
							    "numero"      => $numero,
							    "referencia"  => $referencia,
							    "bairro"      => $bairro,
							    "complemento" => $complemento,
							    "telefone"    => $telefone
							);

				$destinatario = array(
								"nome"    => $nome,
							    "cpfCnpj" => $cpf,
							    "email"   => $email
							);

				// "cnpjIntermediador"        => "06007.513/0001-00",
				// "razaoSocialIntermediador" => "PRO SHOWS COMERCIO DE ELETRO ELETRONICOS S.A.",
				$intermediadores_financeiros[] = array(
								"formaPagamento"           => 0,
							    "meioPagamento"            => 19,
							    "valorPagamento"           => number_format(($produto_preco * $produto_qntd) + $produto_frete, 2),
							    "tipoIntegracaoPagamento"  => 2,
							    "cnpjIntermediador"        => "",
							    "razaoSocialIntermediador" => "",
							    "bandeiraOperadoraCartao"  => "",
							    "numAutorizacaoCartao"     => ""
							);

				$array_1    = array( "campanha"        => $id_campanha,
									 "cnpj"            => $cnpj,
									 "pedidoParceiro"  => $id_pedido,
									 "valorFrete"      => $produto_frete,
									 "aguardarConfirmacao" => true,
									 "destinatario"    => $destinatario,
									 "enderecoEntrega" => $endereco,
									 "produtos"        => $produtos,
									 "intermediadoresFinanceiros" => $intermediadores_financeiros
									);

				$data_string = json_encode($array_1);

				$tmp_pedido = 0;
				while ($tmp_pedido != 1) {
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url.'/pedidos');				
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_POST, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: text/json', 'Authorization: fTC8wD3yPEeliws2hoeIXQ=='));
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
					$response    = curl_exec($curl);
					$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
					// if (curl_errno($curl)) { echo 'Error:' . curl_error($curl); }
					curl_close($curl);
					// print_r($response); 
					// print_r($status_code);

					if ($status_code == 200 || $status_code == 400) {
					    $tmp_pedido = 1;
					}
				}

				$obj = json_decode($response); // var_dump($obj);

				if ($obj->error->code === null) {

					// $codigo_pedido_via_varejo = $xml->xpath('//a:CodigoPedido')[0]->__toString();
					// $previsao_entrega         = $xml->xpath('//a:PrevisaoDeEntrega')[0]->__toString();
					$codigo_pedido_via_varejo = $obj->data->codigoPedido;
					$previsao_entrega         = $obj->data->dadosEntrega->previsaoDeEntrega;
					$valor_resgate            = number_format(($produto_preco * $produto_qntd) + $produto_frete, 2, '.', '');

					$sql23 = "UPDATE proclube_resgates SET codigo_pedido_via_varejo='$codigo_pedido_via_varejo', previsao_entrega='$previsao_entrega', valor_resgate=$valor_resgate WHERE id = $id_pedido"; // print_r($sql23);
					$dba->query($sql23);

					$array_2    = array( "idCampanha"       => $id_campanha,
										 "idPedidoParceiro" => $id_pedido,
										 "confirmado"       => true
										);

					$data_string2 = json_encode($array_2);

					// Confirma Pedido - Via Varejo
					$tmp_pedido2 = 0;
					while ($tmp_pedido2 != 1) {
						$curl2 = curl_init();
						curl_setopt($curl2, CURLOPT_URL, $url.'/pedidos/'.$codigo_pedido_via_varejo);				
						curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curl2, CURLOPT_CUSTOMREQUEST, 'PATCH');
						curl_setopt($curl2, CURLOPT_POSTFIELDS, $data_string2);
						curl_setopt($curl2, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: text/json', 'Authorization: fTC8wD3yPEeliws2hoeIXQ=='));
						curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, 0);
						$response2    = curl_exec($curl2);
						$status_code2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
						// if (curl_errno($curl2)) { echo 'Error:' . curl_error($curl2); }
						curl_close($curl2);
						// print_r($response2); 
						// print_r($status_code2);

						if ($status_code2 == 200) {
						    $tmp_pedido2 = 1;
						}
					}

					$obj2 = json_decode($response2); // var_dump($obj);

					// if ($parser->ConfirmarPedidoResponse->ConfirmarPedidoResult->Erro == "false") { 
					if ($obj2->error->code === null) {
						$sql26 = "UPDATE proclube_resgates SET status = 1, num_resgate = $id_pedido WHERE id = $id_pedido";
						$dba->query($sql26);

						// Atualiza pontos - Usuário
						$sql12 = "UPDATE proclube_usuarios SET app_pontos = app_pontos-$app_pontos WHERE id = '$id_usuario'";
						$dba->query($sql12);

						// Registra extrato - Usuário
						$sql13 = "INSERT INTO proclube_usuarios_extrato (tipo_transacao, app_pontos, id_usuario, data, protocolo) VALUES (2, -$app_pontos, $id_usuario, NOW(), $id_pedido)";
						$dba->query($sql13);

						$array = array("success" => "true");

					} else { 
						$mnsg_erro   = $obj2->error->code;
						$codigo_erro = $obj2->error->message;

						$array = array("error" => "true", "type" => "viavarejo", "mnsg" => $codigo_erro.' '.$mnsg_erro, "titulo_produto" => $produto_titulo);
						header('Content-type: application/json');
						echo json_encode($array);
						exit;

					}

				} else { 

					$mnsg_erro   = $obj->error->code;
					$codigo_erro = $obj->error->message;

					$array = array("error" => "true", "type" => "viavarejo", "mnsg" => $codigo_erro.' '.$mnsg_erro, "titulo_produto" => $produto_titulo);
					header('Content-type: application/json');
					echo json_encode($array);
					exit;

				}		
			}			

		} else {
			$array = array("error" => "true", "type" => "erro_carrinho");
			header('Content-type: application/json');
			echo json_encode($array);
			exit;
		}
		
	} else {
		$array = array("error" => "true", "type" => "action");

	}
	

} else {
	$array = array("error" => "true", "type" => "parametros");
}

header('Content-type: application/json');
echo json_encode($array);

?>