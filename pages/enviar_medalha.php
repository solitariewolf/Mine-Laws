<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario']) && isset($_POST['medalha'])) {
    $usuario = $_POST['usuario'];
    $medalha = $_POST['medalha'];

    // SQL para inserir o usuário e a medalha na tabela votacoes_medalhas
    $sql = "INSERT INTO votacoes_medalhas (Usuario, Medalha, Votos_Positivos, Votos_Negativos, Total_Votos) VALUES (?, ?, 0, 0, 0)";
    
    // Preparar e executar a consulta
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$usuario, $medalha])) {
        // Medalha enviada com sucesso, exibir alerta e redirecionar
        echo "<script>alert('Honraria enviada ao plneário com sucesso!');</script>";
        echo "<script>location.href='../dashboard.php';</script>";
        exit;
    } else {
        echo "Erro ao enviar a medalha. Por favor, tente novamente.";
    }
}
