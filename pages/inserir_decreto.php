<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['texto_decreto'])) {
    $texto_decreto = $_POST['texto_decreto'];

    // Insira o novo decreto na tabela "decretos"
    $stmt = $conn->prepare("INSERT INTO decretos (Texto, Votos_Derrubar) VALUES (?, 0)");
    $stmt->bind_param("s", $texto_decreto);

    if ($stmt->execute()) {
        echo "<script>alert('Novo decreto inserido com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao inserir o novo decreto. Por favor, tente novamente.');</script>";
    }
}

echo "<script>location.href='../dashboard.php';</script>";
?>