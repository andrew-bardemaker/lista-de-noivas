function deleteUsuario (cod, act, nome) {
  bootbox.confirm(
    'Deseja realmente excluir o usuário administrador: ' + nome + '?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}
function deleteImg (cod, act) {
  bootbox.confirm(
    'Você tem certeza que deseja deletar esta imagem?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

/************ CLIENTES  ************/
function deleteClientes (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja excluir o cliente: ' +
      nome +
      '? Serão deletados todos projetos relacionados a ele.',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

/************ CLIENTES  ************/
function adicionarProdutoCha (cod, id, quantidade) {
  bootbox.confirm(
    'Você tem certeza que deseja adicionar o produto ?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=adicionarProdutoCha&idcha=' +
          id +
          '&idproduto=' +
          cod +
          '&quantidade='+
          quantidade
      }
    }
  )
}
/************ DESIGN  ************/
function deleteDesign (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja excluir o case: ' + nome + '?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function deleteImgDesign (cod, act, num) {
  bootbox.confirm(
    'Você tem certeza que deseja deletar esta imagem?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod + '&num=' + num
      }
    }
  )
}

function ativarDesign (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja colocar o Case - Design: ' +
      nome +
      ' em destaque?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function desativarDesign (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja retirar o Case - Design: ' +
      nome +
      ' em destaque?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}
function desativarCuppom (cod, act, nome) {
  bootbox.prompt({
    title:
      'Você tem certeza que deseja fechar o evento de ' +
      nome +
      ' ?<br><br>Qual a porcentagem de bonificação deseja inserir para este evento',

    callback: function (result) {
		if(result != null){

			document.location.href =
			'_process_request.php?act=' +
			act +
			'&id=' +
			cod +
			'&bonificacao=' +
			result
		}
    }
  })
}

function buscarProduto(){
  var busca =document.getElementById('busca').value;
  document.location.href='produtos.php?busca='+busca;
}
function buscarProdutoAdiciona(){
  var busca =document.getElementById('busca').value;
  var idcha = document.getElementById('idCha').value;
  document.location.href='adicionar-produtos-cha.php?id='+idcha+'&busca='+busca;
}
/************ TECNOLOGIA  ************/
function deleteTecnologia (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja excluir o case: ' + nome + '?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function deleteImgTecnologia (cod, act, num) {
  bootbox.confirm(
    'Você tem certeza que deseja deletar esta imagem?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod + '&num=' + num
      }
    }
  )
}

function ativarTecnologia (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja colocar o Case - Tecnologia: ' +
      nome +
      ' em destaque?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function desativarTecnologia (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja retirar o Case - Tecnologia: ' +
      nome +
      ' em destaque?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}
/************ FOTOGRAFIA  ************/
function deleteFotografia (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja excluir o cha do Anfitrião(a): ' + nome + '?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function deleteImgFotografia (cod, act, num) {
  bootbox.confirm(
    'Você tem certeza que deseja deletar esta imagem?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod + '&num=' + num
      }
    }
  )
}

function ativarFotografia (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja colocar o Case - Fotografia: ' +
      nome +
      ' em destaque?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function desativarFotografia (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja retirar o Case - Fotografia: ' +
      nome +
      ' em destaque?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

/************ ARQUITETURA  ************/
function deleteArquitetura (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja excluir o case: ' + nome + '?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function deleteImgArquitetura (cod, act, num) {
  bootbox.confirm(
    'Você tem certeza que deseja deletar esta imagem?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod + '&num=' + num
      }
    }
  )
}

function ativarArquitetura (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja colocar o Case - Arquitetura: ' +
      nome +
      ' em destaque?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function desativarArquitetura (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja retirar o Case - Arquitetura: ' +
      nome +
      ' em destaque?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

/************ FILME  ************/
function deleteFilme (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja excluir o case: ' + nome + '?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function deleteImgFilme (cod, act, num) {
  bootbox.confirm(
    'Você tem certeza que deseja deletar esta imagem?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod + '&num=' + num
      }
    }
  )
}

function ativarFilme (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja colocar o Case - Filme: ' +
      nome +
      ' em destaque?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function desativarFilme (cod, act, nome) {
  bootbox.confirm(
    'Você tem certeza que deseja retirar o Case - Filme: ' +
      nome +
      ' em destaque?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function addBarras () {
  const value = document.getElementsByName('add_barras')[0].value
  $('#barras').append(
    " <input type='text' name='barras[]' class='form-control mt10' value='" +
      value +
      "' />"
  )
  document.getElementsByName('add_barras')[0].value = ''
}
function observacao (id) {
  bootbox.prompt({
    title: 'Observações sobre o produto ',
    inputType: 'textarea',
    callback: function (result) {
      console.log(result)
    }
  })
}

function deleteUnidade (cod, act, nome) {
  bootbox.confirm(
    'Deseja realmente excluir a : ' + nome + '?',
    function (result) {
      if (result == true) {
        document.location.href =
          '_process_request.php?act=' + act + '&id=' + cod
      }
    }
  )
}

function comprarProd (prod, id, status) {
  bootbox.confirm('Deseja realmente dar baixa no produto?', function (result) {
    if (result == true) {
      document.location.href =
        '_process_request.php?act=comprarProd&id=' +
        id +
        '&prod=' +
        prod +
        '&status=' +
        status
    }
  })
}
