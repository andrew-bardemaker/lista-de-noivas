jQuery(document).ready(function() {
   
   "use strict";

    // Pág. Produtos - Busca Subgrupos
	$('select#grupo').change( function() {			
		var grupo = $(this).val();
		$('select#subgrupo').load('_products_get_subproducts.php?grupo='+grupo); // Envia a requisição p URL de retorno dos dados
		$("select#subgrupo").select2('val', ''); // Carrega conteúdo no select
		$("select#subgrupo").select2({ // Reinicializa plugin do select
			placeholder: "Filtro de Subgrupo de Produtos"
		}); 		 	  	
    });

    // Pág. Bonificação - Busca Subgrupos e Produtos
	$('select#bonificacao_grupo').change( function() {			
		var grupo = $(this).val();
		
		$('select#bonificacao_subgrupo').load('_bonificacao_subgrupos.php?grupo='+grupo); // Envia a requisição p URL de retorno dos dados
		$("select#bonificacao_subgrupo").select2('val', ''); // Carrega conteúdo no select
		$("select#bonificacao_subgrupo").select2({ // Reinicializa plugin do select
			placeholder: "Subgrupo de Produtos"
		}); 

		$("select#bonificacao_produtos").select2("val", "");	
		$("select#bonificacao_produtos").select2({ // Reinicializa plugin do select
			placeholder: "Produto"
		}); 	 	  	
    });

    $('select#bonificacao_subgrupo').change( function() {		
    	var grupo = $('select#bonificacao_grupo').val();	
		var subgrupo = $(this).val();
		$('select#bonificacao_produtos').load('_bonificacao_produtos.php?subgrupo='+subgrupo+'&grupo='+grupo); // Envia a requisição p URL de retorno dos dados
		$("select#bonificacao_produtos").select2('val', ''); // Carrega conteúdo no select
		$("select#bonificacao_produtos").select2({ // Reinicializa plugin do select
			placeholder: "Produto"
		}); 		 	  	
    });

    // Pág. Produtos / Limite Consumo - Busca Subgrupos e Produtos
	$('select#cd_grupo').change( function() {			
		var grupo = $(this).val();
		
		$('select#cd_subgrupo').load('_cd_subgrupos.php?grupo='+grupo); // Envia a requisição p URL de retorno dos dados
		$("select#cd_subgrupo").select2('val', ''); // Carrega conteúdo no select
		$("select#cd_subgrupo").select2({ // Reinicializa plugin do select
			placeholder: "Subgrupo de Produtos"
		}); 

		$("select#cd_produtos").select2("val", "");	
		$("select#cd_produtos").select2({ // Reinicializa plugin do select
			placeholder: "Produto"
		}); 	 	  	
    });

    $('select#cd_subgrupo').change( function() {			
		var grupo = $('select#cd_grupo').val();	
		var subgrupo = $(this).val();
		$('select#cd_produtos').load('_cd_produtos.php?subgrupo='+subgrupo+'&grupo='+grupo); // Envia a requisição p URL de retorno dos dados
		$("select#cd_produtos").select2('val', ''); // Carrega conteúdo no select
		$("select#cd_produtos").select2({ // Reinicializa plugin do select
			placeholder: "Produto"
		}); 		 	  	
    });

    // Pág. Veículos - Busca Marcas e Modelos
	$('select#tipo').change( function() {			
		var tipo = $(this).val(); //
		$('select#marca').load('_veiculos_marca.php?tipo='+tipo); // Envia a requisição p URL de retorno dos dados
		$("select#marca").select2('val', ''); // Carrega conteúdo no select
		$("select#marca").select2({ // Reinicializa plugin do select
			placeholder: "Selecione a marca"
		}); 		 	  	
    });

    $('select#marca').change( function() {			
		var marca = $(this).val(); //
		$('select#modelo').load('_veiculos_modelo.php?marca='+marca); // Envia a requisição p URL de retorno dos dados
		$("select#modelo").select2('val', ''); // Carrega conteúdo no select
		$("select#modelo").select2({ // Reinicializa plugin do select
			placeholder: "Selecione o modelo"
		}); 		 	  	
    });

	$("#form-cupons-fiscais-manual").submit(function (event) {
		// event.preventDefault();
		var form = $(this);
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();
	});


	$('.btn-consulta-cdl').on('click',function(){
		var dataURL = $(this).attr('data-href');
	    $('.modal-body').load(dataURL,function(){
	        $('#myModal').modal({show:true});
	    });
	});

    $(".participantes_push_notification").change(function() {
        var participantes = $(this).val();
        console.log(participantes);

        if (participantes == 1) { // Colaborador Buffon
            $('#grupos_usuarios').hide();
            $('#grupos_usuarios_notificacoes').hide();
            $('#usuario_individual').hide();

        } else if (participantes == 2) { // PF
            $('#grupos_usuarios').hide();
            $('#grupos_usuarios_notificacoes').hide();
            $('#usuario_individual').hide();;

        } else if (participantes == 3) { // PJ
            $('#grupos_usuarios').hide();
            $('#grupos_usuarios_notificacoes').hide();
            $('#usuario_individual').hide();

        } else if (participantes == 4) { // Grupo de Usuários
            $('#grupos_usuarios').show();
            $('#grupos_usuarios_notificacoes').hide();
            $('#usuario_individual').hide();

        } else if (participantes == 5) { // Grupo de Usuários Notificações
            $('#grupos_usuarios').hide();
            $('#grupos_usuarios_notificacoes').show();
            $('#usuario_individual').hide();

        } else if (participantes == 6) { // Usuários Individual
            $('#grupos_usuarios').hide();
            $('#grupos_usuarios_notificacoes').hide();
            $('#usuario_individual').show();

        }
    });

    $(".descontos_v2_tipo").change(function() {
        var tipo = $(this).val();
        console.log(tipo);

        if (tipo == 1) { 
            $('.descontos_v2_grupos_produtos').show();
            $('.descontos_v2_subgrupos_produtos').hide();
            $('.descontos_v2_produtos').hide();

        } else if (tipo == 2) { 
            $('.descontos_v2_grupos_produtos').hide();
            $('.descontos_v2_subgrupos_produtos').show();
            $('.descontos_v2_produtos').hide();

        } else if (tipo == 3) { 
            $('.descontos_v2_grupos_produtos').hide();
            $('.descontos_v2_subgrupos_produtos').hide();
            $('.descontos_v2_produtos').show();

        }
    });

    $(".programacao_envio").change(function() {
        var programacao_envio = $(this).val();
        console.log(programacao_envio);

        if (programacao_envio == 1) { 
            $('.data_envio').hide();
            $('.hora_envio').hide();

        } else if (programacao_envio == 2) { 
            $('.data_envio').show();
            $('.hora_envio').show();

        } else if (programacao_envio == 3) { 
            $('.data_envio').hide();
            $('.hora_envio').hide();

        }
    });

	function select2_postos() {
        $(".select-postos").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_postos_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });  
    }

    function select2_gruposbonificacao() {
        $(".select-gruposbonificacao").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_gruposbonificacao_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });  
    }

    function select2_grupos_usuarios() {
        $(".select-grupos-usuarios").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_grupos_usuarios_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }); 
    }

    function select2_grupos_usuarios_notificacoes() {
        $(".select-grupos-usuarios-notificacoes").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_grupos_usuarios_notificacoes_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }); 
    }

    function select2_usuarios() {
        $(".select-usuarios").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_usuarios_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }); 
    }

    function select2_usuarios_credito() {
        $(".select-usuarios-credito").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_usuarios_credito_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }); 
    }

    function select2_grupos_produtos() {
        $(".select-grupos-produtos").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_grupos_produtos_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }); 
    }

    function select2_subgrupos_produtos() {
        $(".select-subgrupos-produtos").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_subgrupos_produtos_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }); 
    }

    function select2_produtos() {
        $(".select-produtos").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_produtos_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }); 
    }

    jQuery(function(){
        select2_postos();
        select2_gruposbonificacao();
        select2_grupos_usuarios();
        select2_grupos_usuarios_notificacoes();
        select2_usuarios();
        select2_usuarios_credito();
        select2_grupos_produtos();
        select2_subgrupos_produtos();
        select2_produtos();
    });

    // Adiciona novos campos Cargos
    $(document).ready(function() {
        var max_fields3 = 30;
        var wrapper3 = $(".cargos-multi-inputs"); 
        var add_button3 = $("#add_cargos_multi");
        var count3 = 1; 

        $(add_button3).click(function(e) {
            e.preventDefault();
            var length = wrapper3.find(".cargos-multi").length;
            if (count3 < max_fields3) { 
                count3++; 

                $.ajax({
                    method: "GET",
                    url: "_get_cargos.php", 
                }).done(function( data ) {
                    // console.log(data);
                    $('.cargos-multi-inputs').append('<div class="cargos-multi"> <select data-placeholder="Selecione um Cargo" name="cargos_bonificacao[]" class="width100p select-basic mb15"> <option value=""></option> '+data+' </select> <input type="text" name="cargos_porcentagem_bonificacao[]" class="form-control mb15 numero"/> <a href="#" class="btn btn-xs btn-danger remove_field ml0"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                    $('select').select2();
                });

                // $('.cargos-multi-inputs').append('<div class="cargos-multi"> <label>Descrição </label> <div class="mb15"> <textarea class="form-control" name="equipamentos_locatario[]" rows="8"></textarea> </div><a href="#" class="btn btn-xs btn-danger remove_field"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper3).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count3--;
                }
            });     
        })
    });

    // Adiciona novos campos Grupos Usuários
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".grupos-usuarios-inputs"); 
        var add_button1 = $("#add_grupos_usuarios");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".grupos-usuarios").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.grupos-usuarios-inputs').append('<div class="grupos-usuarios"> <label>Grupo Usuário *</label> <div class="mb15"> <select data-placeholder="Buscar grupo usuários..." name="id_grupos_usuarios[]" class="width100p select-grupos-usuarios"> <option value="">Buscar grupo usuários...</option> </select> </div><a href="#" class="btn btn-xs btn-danger remove_field ml0"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_grupos_usuarios();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Grupos Usuários Notificações
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".grupos-usuarios-notificacoes-inputs"); 
        var add_button1 = $("#add_grupos_usuarios_notificacoes");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".grupos-usuarios-notificacoes").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.grupos-usuarios-notificacoes-inputs').append('<div class="grupos-usuarios-notificacoes"> <label>Grupo Usuário Notificações *</label> <div class="mb15"> <select data-placeholder="Buscar grupo usuários notificações..." name="id_grupos_usuarios_notificacoes[]" class="width100p select-grupos-usuarios-notificacoes"> <option value="">Buscar grupo usuários notificações...</option> </select> </div><a href="#" class="btn btn-xs btn-danger remove_field ml0"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_grupos_usuarios_notificacoes();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Usuários
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".usuarios-inputs"); 
        var add_button1 = $("#add_usuarios");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".usuarios").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.usuarios-inputs').append('<div class="usuarios"> <label>Usuário *</label> <div class="mb15"> <select data-placeholder="Buscar usuário..." name="id_usuario[]" class="width100p select-usuarios"> <option value="">Buscar usuário...</option> </select> </div><a href="#" class="btn btn-xs btn-danger remove_field ml0"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_usuarios();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

	// Adiciona novos campos Postos/Ecomm
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".ecommpostos-inputs"); 
        var add_button1 = $("#add_ecommpostos");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".ecommpostos").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.ecommpostos-inputs').append('<div class="periodoposto"> <label>Posto *</label> <div class="mb15"> <select data-placeholder="Buscar posto..." name="posto[]" class="width100p select-postos" required> <option value="">Buscar posto...</option> </select> </div><label>Quantidade *</label> <div class="mb15"> <input type="text" name="quantidade[]" placeholder="Quantidade" class="form-control numero" required/> </div><a href="#" class="btn btn-xs btn-danger remove_field ml0"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_postos();
                // $('.datepicker').datepicker();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Meu Feito/ Metas/ Grupos Bonificação
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".gruposbonificacao-inputs"); 
        var add_button1 = $("#add_gruposbonificacao");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".gruposbonificacao").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.gruposbonificacao-inputs').append('<div class="gruposbonificacao"> <label>Grupo Bonificação *</label> <div class="mb15"> <select data-placeholder="Buscar Grupo Bonificação..." name="grupos[]" class="width100p select-gruposbonificacao" required> <option value="">Buscar Grupo Bonificação...</option> </select> </div><a href="#" class="btn btn-xs btn-danger remove_field ml0"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_gruposbonificacao();
                // $('.datepicker').datepicker();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Meu Feito/ Metas/ Grupos Bonificação Matriz
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".gruposbonificacaomatriz-inputs"); 
        var add_button1 = $("#add_gruposbonificacaomatriz");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".gruposbonificacaomatriz").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.gruposbonificacaomatriz-inputs').append('<div class="gruposbonificacaomatriz"> <label>Grupo Bonificação *</label> <div class="mb15"> <select data-placeholder="Buscar Grupo Bonificação..." name="grupos_matriz[]" class="width100p select-gruposbonificacao" required> <option value="">Buscar Grupo Bonificação...</option> </select> </div><a href="#" class="btn btn-xs btn-danger remove_field ml0"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_gruposbonificacao();
                // $('.datepicker').datepicker();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Grupos de Produtos
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".gruposprodutos-inputs"); 
        var add_button1 = $("#add_gruposprodutos");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".gruposprodutos").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.gruposprodutos-inputs').append('<div class="gruposprodutos"> <label>Grupos de Produtos *</label><div class="mb15"> <select data-placeholder="Buscar Grupos de Produtos..." name="grupos_produtos[]" class="width100p select-grupos-produtos"><option value="">Buscar Grupos de Produtos...</option> </select></div><a href="#" class="btn btn-xs btn-danger remove_field ml0"><i class="fa fa-trash-o" aria-hidden="true"></i></a></div>');
                select2_grupos_produtos();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Subgrupos de Produtos
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".subgruposprodutos-inputs"); 
        var add_button1 = $("#add_subgruposprodutos");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".subgruposprodutos").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.subgruposprodutos-inputs').append('<div class="subgruposprodutos"> <label>Subgrupos de Produtos *</label><div class="mb15"> <select data-placeholder="Buscar Subgrupos de Produtos..." name="subgrupos_produtos[]" class="width100p select-subgrupos-produtos"><option value="">Buscar Subgrupos de Produtos...</option> </select></div><a href="#" class="btn btn-xs btn-danger remove_field ml0"><i class="fa fa-trash-o" aria-hidden="true"></i></a></div>');
                select2_subgrupos_produtos();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });


    // Adiciona novos campos de Produtos
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".produtos-inputs"); 
        var add_button1 = $("#add_produtos");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".produtos").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.produtos-inputs').append('<div class="produtos"> <label>Produtos *</label><div class="mb15"> <select data-placeholder="Buscar Produtos..." name="produtos[]" class="width100p select-produtos"><option value="">Buscar Produtos...</option> </select></div><a href="#" class="btn btn-xs btn-danger remove_field ml0"><i class="fa fa-trash-o" aria-hidden="true"></i></a></div>');
                select2_produtos();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });


});