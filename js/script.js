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

function updateTotal(quantity, price) {
    document.getElementById('valor_total').value = quantity * price;
}

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