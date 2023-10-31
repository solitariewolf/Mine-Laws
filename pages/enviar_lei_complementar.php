<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['textoLei'])) {
    $textoLei = $_POST['textoLei'];

    // SQL para inserir o texto da lei na tabela votacoes_leis_complementares
    $sql = "INSERT INTO votacoes_leis_complementares (Texto_Original, Votos_Positivos, Votos_Negativos) VALUES (?, 0, 0)";
    
    // Preparar e executar a consulta
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$textoLei])) {
        // Lei enviada com sucesso, exibir alerta e redirecionar
        echo "<script>alert('Lei enviada com sucesso!');</script>";
        echo "<script>location.href='../dashboard.php';</script>";
        exit;
    } else {
        echo "Erro ao enviar a lei. Por favor, tente novamente.";
    }
}
