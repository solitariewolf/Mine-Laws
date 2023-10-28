<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lei_id']) && isset($_POST['voto'])) {
    $lei_id = $_POST['lei_id'];
    $voto = $_POST['voto'];
    
    // Recupere o ID do usuário da sessão
    $usuario_id = $_SESSION['id']; // Usamos 'id' da sessão como identificador do usuário
    
    // Verifique se o usuário já votou nessa lei
    $stmt = $conn->prepare("SELECT ID FROM Votos_complementar WHERE Usuario_ID = ? AND Lei_ID = ?");
    $stmt->bind_param("ii", $usuario_id, $lei_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // O usuário ainda não votou nessa lei, registre o voto
        $stmt = $conn->prepare("INSERT INTO Votos_complementar (Usuario_ID, Lei_ID, Voto_Pos_Neg) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $usuario_id, $lei_id, $voto);

        if ($stmt->execute()) {
            // Atualize a contagem de votos na tabela votacoes_leis_complementares
            $update_sql = "UPDATE votacoes_leis_complementares SET ";
            if ($voto === "Positivo") {
                $update_sql .= "Votos_Positivos = Votos_Positivos + 1";
            } else {
                $update_sql .= "Votos_Negativos = Votos_Negativos + 1";
            }
            // Atualize a contagem total de votos
            $update_sql .= ", Total_Votos = Total_Votos + 1 WHERE ID = ?";
            
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("i", $lei_id);
            $stmt->execute();

            echo "<script>alert('Voto registrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao registrar o voto. Por favor, tente novamente.');</script>";
        }
    } else {
        echo "<script>alert('Você já votou nessa lei. Você só pode votar uma vez.'); </script>";
    }
}

echo "<script>location.href='../dashboard.php';</script>";
?>
