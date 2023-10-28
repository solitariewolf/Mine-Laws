<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $lei_id = $_POST['id'];

    // Recupere o texto original e o novo texto da lei
    $stmt = $conn->prepare("SELECT Texto_Original, Novo_Texto FROM votacoes_leis_complementares WHERE ID = ?");
    $stmt->bind_param("i", $lei_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $texto_original = $row['Texto_Original'];
    $novo_texto = $row['Novo_Texto'];

    // Verifique se a lei já existe na tabela "complementares"
    $stmt = $conn->prepare("SELECT ID FROM complementar WHERE ID = ?");
    $stmt->bind_param("i", $lei_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // A lei já existe, atualize o texto
        $stmt = $conn->prepare("UPDATE complementar SET Texto = ? WHERE ID = ?");
        $stmt->bind_param("si", $novo_texto, $lei_id);
    } else {
        // A lei não existe, insira uma nova linha
        $stmt = $conn->prepare("INSERT INTO complementar (ID, Texto) VALUES (?, ?)");
        $stmt->bind_param("is", $lei_id, $texto_original);
    }

    if ($stmt->execute()) {
        // Atualize a lei para ser promulgada
        $stmt = $conn->prepare("UPDATE votacoes_leis_complementares SET Promulgado = 'sim' WHERE ID = ?");
        $stmt->bind_param("i", $lei_id);
        if ($stmt->execute()) {
                    // Exclua todas as linhas na tabela "votos_complementar" que têm o mesmo Lei_ID que a lei promulgada
        $stmt = $conn->prepare("DELETE FROM votos_complementar WHERE Lei_ID = ?");
        $stmt->bind_param("i", $lei_id);
        if ($stmt->execute()) {
            echo "Lei promulgada com sucesso!";
        } else {
            echo "Erro ao promulgar a lei. Por favor, tente novamente.";
        }
    } else {
        echo "Erro ao atualizar ou inserir a lei. Por favor, tente novamente.";
    }
}
}
?>
