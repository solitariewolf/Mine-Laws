<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
    exit;
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lei_existente']) && isset($_POST['nova_lei'])) {
    $lei_id = $_POST['lei_existente'];
    $novo_texto = $_POST['nova_lei'];

    // Recupere o texto original da lei
    $stmt = $conn->prepare("SELECT Texto FROM leis WHERE ID = ?");
    $stmt->bind_param("i", $lei_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $texto_original = $row['Texto'];

    // Insira a nova lei na tabela votacoes_leis
    $stmt = $conn->prepare("INSERT INTO votacoes_leis (ID, Texto_Original, Novo_Texto, Votos_Positivos, Votos_Negativos, Total_Votos) VALUES (?, ?, ?, 0, 0, 0)");
    $stmt->bind_param("iss", $lei_id, $texto_original, $novo_texto);

    if ($stmt->execute()) {
        echo "<script>alert('Lei enviada ao plenário com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao enviar a lei ao plenário. Por favor, tente novamente.');</script>";
    }
}

echo "<script>location.href='../dashboard.php';</script>";
?>
