function exibirConteudo(idUsuario) {
    // Abre o perfil do usuário especificado na mesma guia ou janela
    window.location.href = 'perfil_usuario.php?id=' + idUsuario;
}

$(document).ready(function(){
    $('#lei_existente').change(function(){
        // Atualiza o conteúdo da área de texto com o texto da opção selecionada
        $('#nova_lei').val($(this).find('option:selected').text());
    });
});

//leis constitucionais
$(document).ready(function(){
    $(".btn-danger").click(function(){
        var lei_id = $(this).data('id');
        $.ajax({
            url: 'pages/arquivar_lei.php',
            type: 'post',
            data: {id: lei_id},
            success: function(response){
                alert(response);
            }
        });
    });
});

    $(document).ready(function(){
    $(".btn-success").click(function(){
        var lei_id = $(this).data('id');
        $.ajax({
            url: 'pages/promulgar_lei.php',
            type: 'post',
            data: {id: lei_id},
            success: function(response){
                alert(response);
            }
        });
    });
});

//fim leis constitucionais

//inicio leis complementares

$(document).ready(function(){
    $(".btn-danger2").click(function(){
        var lei_id = $(this).data('id');
        $.ajax({
            url: 'pages/arquivar_lei_complementar.php',
            type: 'post',
            data: {id: lei_id},
            success: function(response){
                alert(response);
            }
        });
    });
});

    $(document).ready(function(){
    $(".btn-success2").click(function(){
        var lei_id = $(this).data('id');
        $.ajax({
            url: 'pages/promulgar_lei_complementar.php',
            type: 'post',
            data: {id: lei_id},
            success: function(response){
                alert(response);
            }
        });
    });
});

//fim leis complementares

//inicio decretos



//fim decretos

//inicio medalhas
    $(document).ready(function(){
    $(".btn-danger3").click(function(){
        var lei_id = $(this).data('id');
        $.ajax({
            url: 'pages/arquivar_medalha.php',
            type: 'post',
            data: {id: lei_id},
            success: function(response){
                alert(response);
            }
        });
    });
});

    $(document).ready(function(){
    $(".btn-success3").click(function(){
        var lei_id = $(this).data('id');
        $.ajax({
            url: 'pages/promulgar_medalha.php',
            type: 'post',
            data: {id: lei_id},
            success: function(response){
                alert(response);
            }
        });
    });
});
//fim medalhas

//loja

            //buscar itens
            document.getElementById('item_existente').addEventListener('change', function() {
                var id = this.value;

                // Cria um novo objeto XMLHttpRequest
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Quando a resposta do servidor estiver pronta, preencha os campos do formulário
                        var item = JSON.parse(this.responseText);
                        document.getElementById("nova_item").value = item.nome;
                        document.getElementById("novo_valor").value = item.valor;
                        document.getElementById("nova_qtd").value = item.qtd;
                    }
                };
                // Envia a solicitação ao servidor
                xhttp.open("GET", "pages/getItem.php?id=" + id, true);
                xhttp.send();
            });
// atualiza o valor total da compra
function updateTotal(quantity, price) {
    document.getElementById('valor_total').value = quantity * price;
}

// Atualiza o valor total da venda
function updateTotalVenda(quantity, price) {
    var valor_venda = price ;
    document.getElementById('valor_total_venda').value = quantity * valor_venda;
}

//calcular taxa de juros

function calcularValorTotal() {
    var valor_emprestimo = parseFloat(document.getElementById('valor_emprestimo').value);
    var taxa_juros = parseFloat(document.getElementById('taxa_juros').value);
    var prazo_pagamento = parseFloat(document.getElementById('prazo_pagamento').value);
    
    var valor_total = valor_emprestimo + (taxa_juros / 30) * (prazo_pagamento )*(valor_emprestimo ) / 100;
    
    document.getElementById('valor_total').value = valor_total.toFixed(2);
  }