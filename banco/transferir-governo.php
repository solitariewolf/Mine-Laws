<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

// Pega os dados do formulário
$id_usuario = 1;
$usuario_destino = $_POST['usuario'];
$valor = $_POST['valor'];
$mensagem = $_POST['mensagem'];

// Verifica o saldo do usuário atual
$sql = "SELECT money FROM banco WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['money'] < $valor) {
    // Exibe um alerta de erro e redireciona
    echo "<script>alert('Saldo insuficiente para realizar a transferência.'); location.href='../dashboard.php';</script>";
    exit;
}

// Inicia uma transação
$conn->begin_transaction();

try {
    // Atualiza o saldo do usuário atual
    $sql = "UPDATE banco SET money = money - ? WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('di', $valor, $id_usuario);
    $stmt->execute();

    // Atualiza o saldo do usuário de destino
    $sql = "UPDATE banco SET money = money + ? WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('di', $valor, $usuario_destino);
    $stmt->execute();

    // Insere a transação no extrato
    $data = date('Y-m-d H:i:s'); // Adicione a data atual
    $tipo = 'D'; // Adicione o tipo de transação
    $sql = "INSERT INTO banco_extrato (data, valor, tipo, user_c, user_d, mensagem) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdsiis', $data, $valor, $tipo, $id_usuario, $usuario_destino, $mensagem);
    $stmt->execute();

    // Commit da transação
    $conn->commit();

    // Exibe um alerta de sucesso
    echo "<script>alert('Transferência realizada com sucesso!'); window.dispatchEvent(new CustomEvent('transferenciaSucesso'));</script>";
} catch (Exception $e) {
    // Rollback da transação em caso de erro
    $conn->rollback();

    // Exibe um alerta de erro
    echo "<script>alert('Erro na transferência: " . $e->getMessage() . "');</script>";
}

echo "<script>location.href='../dashboard.php';</script>";
?>