<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

// ID do jogador
$id_jogador = 1;

// Obter o id do empréstimo da URL
$id_emprestimo = $_GET['id_emprestimo'];

// Buscar o valor total do empréstimo
$query = "SELECT valor_total FROM emprestimos WHERE id_emprestimo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_emprestimo);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$valor_total = $row['valor_total'];
$stmt->close();

// Verificar se o usuário tem saldo suficiente
$query = "SELECT money FROM banco WHERE usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_jogador);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$saldo = $row['money'];
$stmt->close();

if ($saldo < $valor_total) {
    echo "Saldo insuficiente para quitar o empréstimo.";
    exit;
}

// Debitar da conta do usuário
$query = "UPDATE banco SET money = money - ? WHERE usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('di', $valor_total, $id_jogador);
$stmt->execute();
$stmt->close();

// Creditar na conta de id 8
$query = "UPDATE banco SET money = money + ? WHERE usuario = 8";
$stmt = $conn->prepare($query);
$stmt->bind_param('d', $valor_total);
$stmt->execute();
$stmt->close();

// Inserir a transação na tabela banco_extrato
$query = "INSERT INTO banco_extrato (data, valor, tipo, user_c, user_d, mensagem) VALUES (NOW(), ?, 'D', 8, ?, 'Dívida Paga')";
$stmt = $conn->prepare($query);
$stmt->bind_param('di', $valor_total, $id_jogador);
$stmt->execute();
$stmt->close();

// Atualizar o status do empréstimo para quitado
$query = "UPDATE emprestimos SET quitado = 'sim' WHERE id_emprestimo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_emprestimo);
$result = $stmt->execute();
$stmt->close();

if ($result) {
    echo "<script>alert('Empréstimo quitado com sucesso!'); window.location.href='fazenda.php';</script>";
} else {
    echo "<script>alert('Houve um erro ao quitar o empréstimo.'); window.location.href='fazenda.php';</script>";
}

?>