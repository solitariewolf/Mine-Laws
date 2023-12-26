<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID'])) {
    $ID = $_POST['ID'];
    
    // Recupere o ID do usuário da sessão
    $usuario_id = $_SESSION['id']; // Usamos 'id' da sessão como identificador do usuário
    
    // Verifique se o usuário já votou nesse decreto
    $stmt = $conn->prepare("SELECT Usuario_ID FROM votos_decretos WHERE Usuario_ID = ? AND Decreto_ID = ?");
    $stmt->bind_param("ii", $usuario_id, $ID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // O usuário ainda não votou nesse decreto, registre o voto
        $stmt = $conn->prepare("INSERT INTO votos_decretos (Usuario_ID, Decreto_ID) VALUES (?, ?)");
        $stmt->bind_param("ii", $usuario_id, $ID);
        $stmt->execute();

        // Atualize a contagem de votos na tabela decretos
        $update_sql = "UPDATE decretos SET Votos_Derrubar = Votos_Derrubar + 1 WHERE ID = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $ID);
        $stmt->execute();

        echo "<script>alert('Voto registrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Você já votou nesse decreto. Você só pode votar uma vez.'); </script>";
    }
}

echo "<script>location.href='decretos.php';</script>";
?>
