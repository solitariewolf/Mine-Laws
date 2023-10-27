<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $lei_id = $_POST['id'];

    // Atualize a lei para ser arquivada
    $stmt = $conn->prepare("UPDATE votacoes_leis SET Arquivado = 'sim' WHERE ID = ?");
    $stmt->bind_param("i", $lei_id);

    if ($stmt->execute()) {
        echo "Lei arquivada com sucesso!";
    } else {
        echo "Erro ao arquivar a lei. Por favor, tente novamente.";
    }
}
?>
