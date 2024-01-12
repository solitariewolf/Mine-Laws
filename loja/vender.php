<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if (isset($_POST['item_id']) && isset($_POST['quantidade_venda'])) {
    // Obtenha o nome do usuário e o nome do item do banco de dados
    $sql = "SELECT usuarios.usuario, itens.nome, itens.valor FROM usuarios, itens WHERE usuarios.id = ? AND itens.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $_SESSION['id'], $_POST['item_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $usuario = $row['usuario'];
    $item = $row['nome'];
    $valor_item = $row['valor'];
    $quantidade_venda = $_POST['quantidade_venda'];

    // Verifique se a quantidade a ser vendida é válida
    if ($quantidade_venda <= 0) {
        echo "<script>alert('A quantidade a ser vendida deve ser maior que zero.'); window.location.href = '.';</script>";
        exit();
    }

    // Debitar o valor da venda na conta de ID 1
    $valor_total_venda = $valor_item * $quantidade_venda;

    // Verificar se a conta de ID 1 tem saldo suficiente
    $sql_saldo_conta1 = "SELECT money FROM banco WHERE usuario = 7";
    $stmt_saldo_conta1 = $conn->prepare($sql_saldo_conta1);
    $stmt_saldo_conta1->execute();
    $result_saldo_conta1 = $stmt_saldo_conta1->get_result();
    $row_saldo_conta1 = $result_saldo_conta1->fetch_assoc();
    $saldo_conta1 = $row_saldo_conta1['money'];

    if ($saldo_conta1 < $valor_total_venda) {
        echo "<script>alert('Saldo insuficiente na conta de ID 1.'); window.location.href = '.';</script>";
        exit();
    }

    // Atualizar o saldo da conta de ID 1
    $sql_debitar_conta1 = "UPDATE banco SET money = money - ? WHERE usuario = 7";
    $stmt_debitar_conta1 = $conn->prepare($sql_debitar_conta1);
    $stmt_debitar_conta1->bind_param("d", $valor_total_venda);
    $stmt_debitar_conta1->execute();

    // Atualize a quantidade de itens disponíveis
    $sql = "UPDATE itens SET qtd = qtd + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantidade_venda, $_POST['item_id']);
    $stmt->execute();

    // Creditar o valor da venda na conta do usuário
    $sql = "UPDATE banco SET money = money + ? WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $valor_total_venda, $_SESSION['id']);
    $stmt->execute();

    // Registre a transação no extrato
    $mensagem = "Venda de Item";
    $data = date('Y-m-d H:i:s');
    $tipo = 'C'; // C para crédito
    $user_c = $_SESSION['id'];
    $user_d = 7; // ID 1 para todas as vendas
    $sql = "INSERT INTO banco_extrato (data, valor, tipo, user_c, user_d, mensagem) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsiis", $data, $valor_total_venda, $tipo, $user_c, $user_d, $mensagem);
    $stmt->execute();

        // Primeira requisição para obter o token
        $url = 'http://144.22.179.248:8080/oauth2/token';
        $data = array('grant_type' => 'client_credentials', 'client_id' => CLIENT_ID, 'client_secret' => CLIENT_SECRET);
        
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */ }
    
        $result = json_decode($result);
        $token = $result->access_token;
    
        // Segunda requisição para enviar o comando
        $url = 'http://144.22.179.248:8080/daemon/server/b522d495/console';
        $data_clear = "/clear " . $usuario . " " . $item . " " . $quantidade_venda;
        $data_say = "/say O usuário " . $usuario . " vendeu " . $quantidade_venda . " " . $item;
        $data = $data_clear . "\n" . $data_say;
        $options = array(
            'http' => array(
                'header'  => "Content-type: text/plain\r\nAuthorization: Bearer " . $token . "\r\n",
                'method'  => 'POST',
                'content' => $data
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */ }
 
    echo "<script>alert('Venda efetuada com sucesso.'); window.location.href = '.';</script>";
} else {
    echo "<script>alert('Parâmetros inválidos.'); window.location.href = '.';</script>";
}
?>
