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