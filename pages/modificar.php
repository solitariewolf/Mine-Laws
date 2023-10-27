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

    // Verifique se a lei já existe na tabela "votacoes_leis"
    $stmt = $conn->prepare("SELECT ID FROM votacoes_leis WHERE ID = ?");
    $stmt->bind_param("i", $lei_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // A lei já existe, atualize a lei
        $stmt = $conn->prepare("UPDATE votacoes_leis SET Texto_Original = ?, Novo_Texto = ?, Votos_Positivos = 0, Votos_Negativos = 0, Total_Votos = 0, Arquivado = 'não', Promulgado = 'não' WHERE ID = ?");
        $stmt->bind_param("ssi", $texto_original, $novo_texto, $lei_id);
    } else {
        // A lei não existe, insira uma nova lei
        $stmt = $conn->prepare("INSERT INTO votacoes_leis (ID, Texto_Original, Novo_Texto, Votos_Positivos, Votos_Negativos, Total_Votos, Arquivado, Promulgado) VALUES (?, ?, ?, 0, 0, 0, 'não', 'não')");
        $stmt->bind_param("iss", $lei_id, $texto_original, $novo_texto);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Lei enviada ao plenário com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao enviar a lei ao plenário. Por favor, tente novamente.');</script>";
    }
}


echo "<script>location.href='../dashboard.php';</script>";
?>
