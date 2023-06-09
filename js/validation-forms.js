function formClientes() {
	$("#form-clientes").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-clientes").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function(data) {				
				$('.loader').hide();
				$('.btn').show();
				if (data == "success") {
					window.location.href="clientes?msg=c001";
				} else if (data == "success_edit") {
					window.location.href="clientes?msg=c002";
				
				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();
				
				} else if (data == "file") {					
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();
				
				} else if (data == "file_upload") {					
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();
				
				} else if (data == "invalidimg") {					
					status.html("").hide();
					status.html("A extensão da imagem não é válida.").slideDown();
				
				} else if (data == "invalidimgsize") {					
					status.html("").hide();
					status.html("A imagem é muito grande, envie arquivos de até 2Mb.").slideDown();
				
				} else if (data == "invalidimgdimensoes") {					
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();
				
				} else {
					//alert(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});	
}

function formDesign() {
	$("#form-design").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-design").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function(data) {				
				$('.loader').hide();
				$('.btn').show();
				if (data == "success") {
					window.location.href="design?msg=d001";
				} else if (data == "success_edit") {
					window.location.href="design?msg=d002";
				
				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();
				
				} else if (data == "ano") {
					status.html("").hide();
					status.html("Preencha o campo ano").slideDown();
				
				} else if (data == "categoria") {
					status.html("").hide();
					status.html("Selecione uma categoria").slideDown();
				
				} else if (data == "file") {					
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();
				
				} else if (data == "file_upload") {					
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();
				
				} else if (data == "invalidimg") {					
					status.html("").hide();
					status.html("A extensão de alguma imagem não é válida.").slideDown();
				
				} else if (data == "invalidimgsize") {					
					status.html("").hide();
					status.html("Alguma das imagens é muito grande, envie arquivos de até 2Mb.").slideDown();
				
				} else if (data == "invalidimgdimensoes") {					
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();
				
				} else {
					//alert(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});	
}

function formTecnologia() {
	$("#form-tecnologia").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-tecnologia").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function(data) {				
				$('.loader').hide();
				$('.btn').show();
				if (data == "success") {
					window.location.href="tecnologia?msg=t001";
				} else if (data == "success_edit") {
					window.location.href="tecnologia?msg=t002";
				
				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();
				
				} else if (data == "ano") {
					status.html("").hide();
					status.html("Preencha o campo ano").slideDown();
				
				} else if (data == "categoria") {
					status.html("").hide();
					status.html("Selecione uma categoria").slideDown();
				
				} else if (data == "file") {					
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();
				
				} else if (data == "file_upload") {					
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();
				
				} else if (data == "invalidimg") {					
					status.html("").hide();
					status.html("A extensão de alguma imagem não é válida.").slideDown();
				
				} else if (data == "invalidimgsize") {					
					status.html("").hide();
					status.html("Alguma das imagens é muito grande, envie arquivos de até 2Mb.").slideDown();
				
				} else if (data == "invalidimgdimensoes") {					
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();
				
				} else {
					alert(data);
					//status.html("").hide();
					//status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});	
}

function formFotografia() {
	$("#form-fotografia").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		var idcha = document.getElementById("idcha").value;
		$('.btn').hide();
		$('.loader').show();

		$("#form-fotografia").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function(data) {				
				$('.loader').hide();
				$('.btn').show();
				if (data == "success") {
					window.location.href="adicionar-produtos-cha.php?status=1&id="+idcha;
				} else if (data == "success_edit") {
					window.location.href="cha?msg=f002";
				
				} else if (data == "success_prod") {
					window.location.href="cha?msg=f002";
				
				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();
				
				} else if (data == "ano") {
					status.html("").hide();
					status.html("Preencha o campo ano").slideDown();
				
				} else if (data == "categoria") {
					status.html("").hide();
					status.html("Selecione uma categoria").slideDown();
				
				} else if (data == "file") {					
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();
				
				} else if (data == "file_upload") {					
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();
				
				} else if (data == "invalidimg") {					
					status.html("").hide();
					status.html("A extensão de alguma imagem não é válida.").slideDown();
				
				} else if (data == "invalidimgsize") {					
					status.html("").hide();
					status.html("Alguma das imagens é muito grande, envie arquivos de até 2Mb.").slideDown();
				
				} else if (data == "invalidimgdimensoes") {					
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();
				
				} else {
					//alert(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});	
}

function formUnidade() {
	$("#form-unidade").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-unidade").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function(data) {				
				$('.loader').hide();
				$('.btn').show();
				if (data == "success") {
					window.location.href="unidades?msg=u001";
				} else if (data == "success_edit") {
					window.location.href="unidades?msg=u002";
				
				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();
				
				} else if (data == "ano") {
					status.html("").hide();
					status.html("Preencha o campo ano").slideDown();
				
				} else if (data == "categoria") {
					status.html("").hide();
					status.html("Selecione uma categoria").slideDown();
				
				} else if (data == "file") {					
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();
				
				} else if (data == "file_upload") {					
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();
				
				} else if (data == "invalidimg") {					
					status.html("").hide();
					status.html("A extensão de alguma imagem não é válida.").slideDown();
				
				} else if (data == "invalidimgsize") {					
					status.html("").hide();
					status.html("Alguma das imagens é muito grande, envie arquivos de até 2Mb.").slideDown();
				
				} else if (data == "invalidimgdimensoes") {					
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();
				
				} else {
					//alert(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});	
}

function formArquitetura() {
	$("#form-arquitetura").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-arquitetura").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function(data) {				
				$('.loader').hide();
				$('.btn').show();
				if (data == "success") {
					window.location.href="arquitetura?msg=a001";
				} else if (data == "success_edit") {
					window.location.href="arquitetura?msg=a002";
				
				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();
				
				} else if (data == "ano") {
					status.html("").hide();
					status.html("Preencha o campo ano").slideDown();
				
				} else if (data == "categoria") {
					status.html("").hide();
					status.html("Selecione uma categoria").slideDown();
				
				} else if (data == "file") {					
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();
				
				} else if (data == "file_upload") {					
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();
				
				} else if (data == "invalidimg") {					
					status.html("").hide();
					status.html("A extensão de alguma imagem não é válida.").slideDown();
				
				} else if (data == "invalidimgsize") {					
					status.html("").hide();
					status.html("Alguma das imagens é muito grande, envie arquivos de até 2Mb.").slideDown();
				
				} else if (data == "invalidimgdimensoes") {					
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();
				
				} else {
					alert(data);
					//status.html("").hide();
					//status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});	
}

function formFilme() {
	$("#form-filme").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-filme").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function(data) {				
				$('.loader').hide();
				$('.btn').show();
				if (data == "success") {
					window.location.href="filme?msg=flm1";
				} else if (data == "success_edit") {
					window.location.href="filme?msg=flm2";
				
				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();
				
				} else if (data == "ano") {
					status.html("").hide();
					status.html("Preencha o campo ano").slideDown();
				
				} else if (data == "link") {
					status.html("").hide();
					status.html("Informe o link do vídeo").slideDown();
				
				} else if (data == "categoria") {
					status.html("").hide();
					status.html("Selecione uma categoria").slideDown();
				
				} else if (data == "file") {					
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();
				
				} else if (data == "file_upload") {					
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();
				
				} else if (data == "invalidimg") {					
					status.html("").hide();
					status.html("A extensão de alguma imagem não é válida.").slideDown();
				
				} else if (data == "invalidimgsize") {					
					status.html("").hide();
					status.html("Alguma das imagens é muito grande, envie arquivos de até 2Mb.").slideDown();
				
				} else if (data == "invalidimgdimensoes") {					
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();
				
				} else {
					alert(data);
					//status.html("").hide();
					//status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});	
}


function formProdutos() {
	$("#form-produtos").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show(); 
		$("#form-produtos").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function(data) {				
				$('.loader').hide();
				$('.btn').show();
				if (data == "success") {
					window.location.href="produtos?msg=p001";
				} else if (data == "success_edit") {
					window.location.href="produtos?msg=p002";
				
				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();
				
				} else if (data == "ano") {
					status.html("").hide();
					status.html("Preencha o campo ano").slideDown();
				
				} else if (data == "categoria") {
					status.html("").hide();
					status.html("Selecione uma categoria").slideDown();
				
				} else if (data == "file") {					
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();
				
				} else if (data == "file_upload") {					
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();
				
				} else if (data == "invalidimg") {					
					status.html("").hide();
					status.html("A extensão de alguma imagem não é válida.").slideDown();
				
				} else if (data == "invalidimgsize") {					
					status.html("").hide();
					status.html("Alguma das imagens é muito grande, envie arquivos de até 2Mb.").slideDown();
				
				} else if (data == "invalidimgdimensoes") {					
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();
				
				} else {
					//alert(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});	
}
function formUsuarios() {
	$("#form-usu").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-usu").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function(data) {				
				$('.loader').hide();
				$('.btn').show();
				if (data == "success") {
					window.location.href="usuarios?msg=usu001";
				} else if (data == "updated") {
					window.location.href="usuarios?msg=up01";
				}else if (data == "success_edit") {
					window.location.href="usuarios?msg=usu002";
				
				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();
				
				} else if (data == "ano") {
					status.html("").hide();
					status.html("Preencha o campo ano").slideDown();
				
				} else if (data == "categoria") {
					status.html("").hide();
					status.html("Selecione uma categoria").slideDown();
				
				} else if (data == "file") {					
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();
				
				} else if (data == "file_upload") {					
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();
				
				} else if (data == "invalidimg") {					
					status.html("").hide();
					status.html("A extensão de alguma imagem não é válida.").slideDown();
				
				} else if (data == "invalidimgsize") {					
					status.html("").hide();
					status.html("Alguma das imagens é muito grande, envie arquivos de até 2Mb.").slideDown();
				
				} else if (data == "invalidimgdimensoes") {					
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();
				
				} else {
					//alert(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});	
}


jQuery(function(){
	formClientes();	
	formTecnologia();	
	formDesign();	
	formFotografia();
	formArquitetura();
	formFilme();
	formUnidade();
	formProdutos();
	formUsuarios();
});
