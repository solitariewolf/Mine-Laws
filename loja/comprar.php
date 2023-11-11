<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if (isset($_POST['item_id']) && isset($_POST['quantidade'])) {
    // Obtenha o nome do usuário e o nome do item do banco de dados
    $sql = "SELECT usuarios.usuario, itens.nome, itens.valor, itens.qtd FROM usuarios, itens WHERE usuarios.id = ? AND itens.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $_SESSION['id'], $_POST['item_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $usuario = $row['usuario'];
    $item = $row['nome'];
    $valor_item = $row['valor'];
    $quantidade_disponivel = $row['qtd'];
    $quantidade = $_POST['quantidade'];

    // Verifique se o usuário tem saldo suficiente
    $sql = "SELECT money FROM banco WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $saldo = $row['money'];

    $valor_total = $valor_item * $quantidade;
    if ($saldo < $valor_total) {
        echo "<script>alert('Saldo insuficiente.'); window.location.href = '../dashboard.php';</script>";
        exit();
    }

    // Verifique se a quantidade de itens disponíveis é suficiente
    if ($quantidade_disponivel < $quantidade) {
        echo "<script>alert('Quantidade de itens insuficiente.'); window.location.href = '../dashboard.php';</script>";
        exit();
    }

    // Atualize a quantidade de itens disponíveis
    $sql = "UPDATE itens SET qtd = qtd - ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantidade, $_POST['item_id']);
    $stmt->execute();

    // Debitar o valor da compra da conta do usuário
    $sql = "UPDATE banco SET money = money - ? WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $valor_total, $_SESSION['id']);
    $stmt->execute();

    // Creditar o valor da compra na conta do ID 1
    $sql = "UPDATE banco SET money = money + ? WHERE usuario = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $valor_total);
    $stmt->execute();

    // Registre a transação no extrato
    $mensagem = "Compra de Item";
    $data = date('Y-m-d H:i:s');
    $tipo = 'D'; // D para débito
    $user_c = 1; // ID 1 para todas as compras
    $sql = "INSERT INTO banco_extrato (data, valor, tipo, user_c, user_d, mensagem) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiiis", $data, $valor_total, $tipo, $user_c, $_SESSION['id'], $mensagem);
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
    $url = 'http://144.22.179.248:8080/daemon/server/c45f4969/console';
    $data = "/give " . $usuario . " " . $item . " " . $quantidade;
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
    else {
        echo "<script>alert('Compra efetuada com sucesso.'); window.location.href = '../dashboard.php';</script>";
    }
}
?>
