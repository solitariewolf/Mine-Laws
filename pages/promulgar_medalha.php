<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $medalha_id = $_POST['id'];

    // Recupere os detalhes da medalha
    $stmt = $conn->prepare("SELECT nome, imagem, descricao FROM medalhas WHERE id = ?");
    $stmt->bind_param("i", $medalha_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $nome = $row['nome'];
    $imagem = $row['imagem'];
    $descricao = $row['descricao'];

    // Verifique se a medalha já existe na tabela "votacoes_medalhas"
    $stmt = $conn->prepare("SELECT id FROM votacoes_medalhas WHERE id = ?");
    $stmt->bind_param("i", $medalha_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // A medalha já existe, atualize os detalhes
        $stmt = $conn->prepare("UPDATE votacoes_medalhas SET Usuario = ?, Medalha = ?, Votos_Positivos = 0, Votos_Negativos = 0, Total_Votos = 0, Arquivado = 'não', Promulgado = 'sim' WHERE id = ?");
        $stmt->bind_param("isi", $_SESSION['id'], $medalha_id, $medalha_id);
    } else {
        // A medalha não existe, insira uma nova linha
        $stmt = $conn->prepare("INSERT INTO votacoes_medalhas (id, Usuario, Medalha, Votos_Positivos, Votos_Negativos, Total_Votos, Arquivado, Promulgado) VALUES (?, ?, ?, 0, 0, 0, 'não', 'sim')");
        $stmt->bind_param("iii", $medalha_id, $_SESSION['id'], $medalha_id);
    }

    if ($stmt->execute()) {
        echo "Medalha promulgada com sucesso!";
    } else {
        echo "Erro ao promulgar a medalha. Por favor, tente novamente.";
    }
}
?>
