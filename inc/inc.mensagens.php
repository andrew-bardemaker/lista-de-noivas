<?php
if (isset($_GET['msg']) && !empty($_GET['msg'])) {
	$msg = $_GET['msg'];
	switch ($msg) {
		case '000':
			$txt = 'Navegue pelos links!';
			break;
		case '123':
			$txt = 'E-mail ou senha invalidos!';
			break;
		case '456':
			$txt = 'Faça o login primeiro!';
			break;

		case 'up01':
			$txt="Usuario atualizado com sucesso!";
			break;
		case 'u123':
			$txt = 'Usuario cadastrado com sucesso!';
			break;
		case 'u456':
			$txt = 'Usuario atualizado com sucesso!';
			break;
		case 'u789':
			$txt = 'Usuario excluído com sucesso!';
			break;


		case 'p123':
			$txt = 'Promoção cadastrada com sucesso!';
			break;
		case 'p456':
			$txt = 'Promoção atualizada com sucesso!';
			break;
		case 'p789':
			$txt = 'Promoção excluída com sucesso!';
			break;
		case 'p234':
			$txt = 'Imagem maior que o limite (1.5 MB)!';
			break;
		case 'p567':
			$txt = 'Promoção cadastrada com foto!';
			break;
		case 'p890':
			$txt = 'Promoção cadastrada, mas houve algum problema com o cadastro da imagem!';
			break;
		case 'p345':
			$txt = 'Promoção atualizada com alteração da foto!';
			break;
		case 'p678':
			$txt = 'Promoção atualizada, mas houve algum problema com a atualização da imagem!';
			break;
		case 'p901':
			$txt = 'Imagem excluída da promoção!';
			break;


		case 'a123':
			$txt = 'Agenda cadastrada com sucesso!';
			break;
		case 'a456':
			$txt = 'Agenda atualizada com sucesso!';
			break;
		case 'a789':
			$txt = 'Agenda excluída com sucesso!';
			break;
		case 'a234':
			$txt = 'Imagem maior que o limite (1.5 MB)!';
			break;
		case 'a567':
			$txt = 'Agenda cadastrada com foto!';
			break;
		case 'a890':
			$txt = 'Agenda cadastrada, mas houve algum problema com o cadastro da imagem!';
			break;
		case 'a345':
			$txt = 'Agenda atualizada com alteração da foto!';
			break;
		case 'a678':
			$txt = 'Agenda atualizada, mas houve algum problema com a atualização da imagem!';
			break;
		case 'a901':
			$txt = 'Imagem excluída do evento!';
			break;


		case 'n123':
			$txt = 'Item cadastrado com sucesso!';
			break;
		case 'n456':
			$txt = 'Item atualizado com sucesso!';
			break;
		case 'n789':
			$txt = 'Notícia excluída com sucesso!';
			break;
		case 'n234':
			$txt = 'Imagem maior que o limite (3 MB)!';
			break;
		case 'n567':
			$txt = 'Produto cadastrado com foto!';
			break;
		case 'n890':
			$txt = 'Produto cadastrado, mas houve algum problema com o cadastro da imagem!';
			break;
		case 'n345':
			$txt = 'Novidade atualizada com alteração da foto!';
			break;
		case 'n678':
			$txt = 'Novidade atualizada, mas houve algum problema com a atualização da imagem!';
			break;


		case 'p002':
			$txt = 'Produto atualizado com foto!';
			break;
		case 'p003':
			$txt = 'Produto atualizado, mas houve algum problema com a atualização da iamgem!';
			break;
		case 'p004':
			$txt = 'Produto atualizado com sucesso!';
			break;
		case 'p005':
			$txt = 'Você deve selecionar uma opção!';
			break;
		case 'p006':
			$txt = 'Não há produtos cadastrados nesta categoria!';
			break;


		case 'n111':
			$txt = 'Notícia atualizada com sucesso!';
			break;
		case 'e123':
			$txt = 'Notícia cadastrada com sucesso!';
			break;
		case 'e456':
			$txt = 'Evento atualizado com sucesso!';
			break;
		case 'e789':
			$txt = 'Notícia excluída com sucesso!';
			break;
		case 'e234':
			$txt = 'Foto vinculada ao evento!';
			break;
		case 'e567':
			$txt = 'Foto desvinculada ao evento!';
			break;
		case 'e890':
			$txt = 'Tipo de imagem incompatível!';
			break;
		case 'e345':
			$txt = 'Tamanho da imagem não pode exceder 1MB!';
			break;


		case 'c123':
			$txt = 'Arquivo com os e-mails de contato gerado com sucesso!';
			break;


		case 'f001':
			$txt = ' <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			Chá de Panela Criado com sucesso!
			</div>';
			break;
		case 'u001':
			$txt = ' <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Unidade Criado com sucesso!
				</div>';
			break;
		case 'u002':
			$txt = ' <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					Unidade Deletada com sucesso!
					</div>';
			break;
		case 'p001':
			$txt = ' <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					Produtos importados com sucesso!
					</div>';
			break;
		case 'usu001':
			$txt = ' <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					Usuario Criado com sucesso!
						</div>';
			break;
		case 'c003':
			$txt = ' <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						Usuario Deletado com sucesso!
							</div>';
			break;

		default:
			$txt = 'Navegue pelos links!';
	}

	$tpl->gotoBlock('_ROOT');
	$tpl->assign('msg',  $txt);
}
