<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $votacao_id = $_POST['id'];

    // Recupere o ID do usuário e o ID da medalha da votação
    $stmt = $conn->prepare("SELECT Usuario, Medalha FROM votacoes_medalhas WHERE id = ?");
    $stmt->bind_param("i", $votacao_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $usuario_id = $row['Usuario'];
    $medalha_id = $row['Medalha'];

    // Verifique se o usuário já tem essa medalha
    $stmt = $conn->prepare("SELECT usuario_id FROM usuarios_medalhas WHERE usuario_id = ? AND medalha_id = ?");
    $stmt->bind_param("ii", $usuario_id, $medalha_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // O usuário ainda não tem essa medalha, insira uma nova linha
        $stmt = $conn->prepare("INSERT INTO usuarios_medalhas (usuario_id, medalha_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $usuario_id, $medalha_id);

        if ($stmt->execute()) {
            // Atualize a medalha para ser promulgada
            $stmt = $conn->prepare("UPDATE votacoes_medalhas SET Promulgado = 'sim' WHERE id = ?");
            $stmt->bind_param("i", $votacao_id);
            if ($stmt->execute()) {
                // Exclua todas as linhas na tabela "votos_medalhas" que têm o mesmo Lei_ID que a medalha promulgada
                $stmt = $conn->prepare("DELETE FROM votos_medalhas WHERE Lei_ID = ?");
                $stmt->bind_param("i", $votacao_id);
                if ($stmt->execute()) {
                    echo "Medalha promulgada com sucesso!";
                } else {
                    echo "Erro ao excluir votos da medalha. Por favor, tente novamente.";
                }
            } else {
                echo "Erro ao promulgar a medalha. Por favor, tente novamente.";
            }
        } else {
            echo "Erro ao inserir a medalha. Por favor, tente novamente.";
        }
    } else {
        echo "O usuário já tem essa medalha.";
    }
}
?>
