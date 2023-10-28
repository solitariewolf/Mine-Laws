<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

// Pegar o texto do formulário
$texto = $_POST['texto'];

// Consulta SQL para alterar o texto
$stmt = $conn->prepare("UPDATE mensagem_presidencia SET texto=? WHERE id=1");
$stmt->bind_param("s", $texto);

if ($stmt->execute()) {
    echo "<script>alert('Pronunciamento aos membros do jogo alterada com sucesso!');</script>";
} else {
    echo "<script>alert('Erro ao alterar discurso.');</script>";
}


echo "<script>location.href='../dashboard.php';</script>";
?>
