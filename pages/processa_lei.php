<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}

// Inclua o arquivo de conexão com o banco de dados
include('../config.php');

// Verifique se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receba os dados do formulário
    $lei_id = $_POST['lei'];
    $acao = $_POST['acao_lei'];
    $nova_lei = isset($_POST['nova_lei']) ? $_POST['nova_lei'] : ''; // Apenas se a ação for "alterar"

    // Recupere o texto da lei original da tabela leis
    $query_lei_original = "SELECT lei FROM leis WHERE id = ?";
    $stmt_lei_original = mysqli_prepare($conn, $query_lei_original);

    if ($stmt_lei_original) {
        mysqli_stmt_bind_param($stmt_lei_original, "i", $lei_id);
        mysqli_stmt_execute($stmt_lei_original);
        mysqli_stmt_bind_result($stmt_lei_original, $texto_original);
        mysqli_stmt_fetch($stmt_lei_original);
        mysqli_stmt_close($stmt_lei_original);

        // Verifique se uma ação foi selecionada
        if ($acao == "revogar" || ($acao == "alterar" && !empty($nova_lei))) {
            // Inicie uma transação
            mysqli_autocommit($conn, false);

            // Inserir na tabela leis_em_votacao
            $insert_query = "INSERT INTO leis_em_votacao (id_lei_original, acao, nova_lei, texto_original) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "isss", $lei_id, $acao, $nova_lei, $texto_original);
                mysqli_stmt_execute($stmt);

                // Verifique se a inserção foi bem-sucedida
                if (mysqli_affected_rows($conn) > 0) {
                    // Confirme a transação
                    mysqli_commit($conn);

                    // Exiba um alerta
                    echo "<script>alert('Ação registrada com sucesso. Aguardando votação.');</script>";

                    // Redirecione para dashboard.php após um breve atraso
                    echo "<script>setTimeout(function() { window.location.href = '../dashboard.php'; }, 0);</script>";
                } else {
                    // Rolback em caso de erro
                    mysqli_rollback($conn);

                    // Exiba um alerta
                    echo "<script>alert('Erro ao registrar a ação.');</script>";
                }

                // Feche a declaração preparada
                mysqli_stmt_close($stmt);
            } else {
                echo "Erro na preparação da consulta.";
            }

            // Restaure o modo de autocommit
            mysqli_autocommit($conn, true);
        } else {
            echo "Selecione uma ação válida e, se for 'Alterar Lei', forneça a nova lei.";
        }
    } else {
        echo "Erro na recuperação do texto da lei original.";
    }
} else {
    echo "Acesso inválido a este script.";
}

// Feche a conexão com o banco de dados
mysqli_close($conn);
?>
