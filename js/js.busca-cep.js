jQuery(document).ready(function() {
    "use strict";

    $("#cep").change(function(){
        $("input#cidade").attr('readonly', false);
        $("input#bairro").attr('readonly', false);
        $("input#uf").attr('readonly', false);

        var cep_code = $(this).val();
        $.get("https://webservice.kinghost.net/web_cep.php?auth=9366a47c3bd28ea388c324ba31d04e8e&formato=xml&cep="+cep_code, function(data){
            $(data).find('webservicecep').each(function () {
                var resultado = $(this).find('resultado').text();

                if (resultado != 1 && resultado != 2) {
                    $("input#cep").val("");
                    $("input#cidade").val("");
                    $("input#bairro").val("");
                    $("input#endereco").val("");
                    $("input#uf").val("");

                    bootbox.alert("CEP não encontrado.");
                    return;
                }

                var cidade = $(this).find('cidade').text();
                var bairro = $(this).find('bairro').text();
                var endereco = $(this).find('logradouro').text();
                var uf = $(this).find('uf').text();
                    
                //alert(bairro);                
                if (cidade != "") { $("input#cidade").attr('readonly', 'readonly'); }
                if (bairro != "") { $("input#bairro").attr('readonly', 'readonly'); }
                if (uf != "") { $("input#uf").attr('readonly', 'readonly'); }

                $("input#cep").val(cep_code);
                $("input#cidade").val(cidade);
                $("input#bairro").val(bairro);
                $("input#endereco").val(endereco);
                $("input#uf").val(uf);
                
            });
        });
    });    
});