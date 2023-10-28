<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario'])) {
    $usuario_id = $_POST['usuario'];

    // Iniciar uma transação
    $conn->begin_transaction();

    try {
        // Atualizar o tipo do usuário atual para 1
        $stmt = $conn->prepare("UPDATE usuarios SET tipo='1' WHERE tipo='2'");
        $stmt->execute();

        // Atualizar o tipo do novo usuário para 2
        $stmt = $conn->prepare("UPDATE usuarios SET tipo='2' WHERE id=?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();

        // Se ambas as consultas foram bem-sucedidas, confirmar a transação
        $conn->commit();

        echo "<script>alert('Presidência entregue com sucesso!');</script>";
    } catch (Exception $e) {
        // Se houve um erro, reverter a transação
        $conn->rollback();

        echo "<script>alert('Erro ao entregar a presidência. Por favor, tente novamente.');</script>";
    }
}

echo "<script>location.href='../dashboard.php';</script>";
?>
