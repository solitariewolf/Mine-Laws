<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor_emprestado = $_POST["valor_emprestimo"];
    $prazo_pagamento = $_POST["prazo_pagamento"];
    $valor_total = $_POST["valor_total"];
    $id_jogador = 1; // Substitua isso pelo ID do jogador da sessão
  
    // Debitar o valor da conta de id 8
    $query = "UPDATE banco SET money = money - $valor_emprestado WHERE id = 8";
    mysqli_query($conn, $query);
  
    // Creditar na conta do usuário da sessão
    $query = "UPDATE banco SET money = money + $valor_emprestado WHERE id = $id_jogador";
    mysqli_query($conn, $query);
  
    // Cadastrar a transação na tabela emprestimos
    $query = "INSERT INTO emprestimos (id_jogador, valor_emprestado, valor_total, prazo_pagamento, dia_vencimento, quitado) VALUES ($id_jogador, $valor_emprestado, $valor_total, $prazo_pagamento, DATE_ADD(CURDATE(), INTERVAL $prazo_pagamento DAY), 'não')";
    mysqli_query($conn, $query);
  
        try {
            // Cadastrar a transação na tabela banco_extrato
            $query = "INSERT INTO banco_extrato (data, valor, tipo, user_c, user_d, mensagem) VALUES (NOW(), $valor_emprestado, 'D', $id_jogador, 8, 'Empréstimo')";
            mysqli_query($conn, $query);
            // Commit da transação
            $conn->commit();
        
            // Exibe uma mensagem de sucesso e redireciona o usuário
            echo "<script>alert('Empréstimo realizado com sucesso!'); window.location.href='fazenda.php';</script>";
        } catch (Exception $e) {
            // Rollback da transação em caso de erro
            $conn->rollback();
        
            // Exibe uma mensagem de erro e redireciona o usuário
            echo "<script>alert('Erro na transferência: " . $e->getMessage() . "'); window.location.href='fazenda.php';</script>";
        }
}  
  ?>

