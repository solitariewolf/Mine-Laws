<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor_emprestimo = $_POST["valor_emprestimo"];
    $prazo_pagamento = $_POST["prazo_pagamento"];
    $valor_total = $_POST["valor_total"];
    $id_jogador = $_SESSION["id_jogador"]; // Substitua isso pelo ID do jogador da sessão
  
    // Debitar o valor da conta de id 8
    $query = "UPDATE banco SET money = money - $valor_emprestimo WHERE id = 8";
    mysqli_query($conn, $query);
  
    // Creditar na conta do usuário da sessão
    $query = "UPDATE banco SET money = money + $valor_emprestimo WHERE id = $id_jogador";
    mysqli_query($conn, $query);
  
    // Cadastrar a transação na tabela emprestimos
    $query = "INSERT INTO emprestimos (id_jogador, valor_emprestimo, valor_total, prazo_pagamento, dia_vencimento) VALUES ($id_jogador, $valor_emprestimo, $valor_total, $prazo_pagamento, DATE_ADD(CURDATE(), INTERVAL $prazo_pagamento DAY))";
    mysqli_query($conn, $query);
  
    // Cadastrar a transação na tabela banco_extrato
    $query = "INSERT INTO banco_extrato (data, valor, tipo, user_c, user_d, mensagem) VALUES (NOW(), $valor_emprestimo, 'D', 8, $id_jogador, 'Empréstimo')";
    mysqli_query($conn, $query);
  }
  ?>

