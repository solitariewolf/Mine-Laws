<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}

include('../config.php');

    // Verifique se o ID da lei foi enviado
    if (isset($_POST['id'])) {
        $leiID = $_POST['id'];

        // Consulta SQL para deletar a votação
        $sql = "DELETE FROM leis_em_votacao WHERE id_lei_original = ?";

        // Prepare a consulta SQL
        if ($stmt = $conn->prepare($sql)) {
            // Vincule o ID da lei à consulta SQL
            $stmt->bind_param("i", $leiID);

            // Execute a consulta
            if ($stmt->execute()) {
                echo "Votação deletada com sucesso!";
            } else {
                echo "Erro: " . $stmt->error;
            }
        } else {
            echo "Erro: " . $conn->error;
        }
    }

    $conn->close();
?>
