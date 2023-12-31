<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Mine Laws MOD 1.0</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
</head>
<body>

    <div class="logo-banco"> <img src="img/banco/bank-logo.png" alt=""></div>

        <?php
        // Pega o ID do usuário da sessão
        $id_usuario = $_SESSION['id'];

        // Prepara a consulta SQL
        $consulta = $conn->prepare("SELECT u.nome, b.money FROM usuarios u INNER JOIN banco b ON u.id = b.usuario WHERE u.id = ?");
        $consulta->bind_param('i', $id_usuario);

        // Executa a consulta
        $consulta->execute();

        // Fetch the result
        $resultado = $consulta->get_result()->fetch_assoc();

        // Formata o valor do dinheiro com pontos como separadores de milhares
        $money_formatado = number_format($resultado['money'], 2, ',', '.');

        // Exibe a mensagem
        echo "<p class='saldo-inicio'>Olá " . $resultado['nome'] . ", seu saldo é de MC$: " . $money_formatado . "</p>";
        ?>
<div class="duas-primeiras">

    <div class="form-transferir">
        <h1>Relizar transferência entre contas</h1>
        <form action="banco/transferir.php" method="post">
        <label for="usuario">Usuário:</label><br>
        <select class="form-control bg-light rounded" name="usuario" id="usuario">
            <option value="" disabled selected>Escolha um membro</option>
            <?php
                $sql = "SELECT id, nome FROM usuarios WHERE id != ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $_SESSION['id']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Saída dos dados de cada linha
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum usuário encontrado</option>";
                }
            ?>
        </select><br>
        <label for="valor">Valor:</label><br>
        <input type="number" id="valor" name="valor" min="0" step="0.01"><br>
        <label for="mensagem">Mensagem:</label><br>
        <textarea id="mensagem" name="mensagem"></textarea><br>
        <input type="submit" value="Transferir">
        </form>
    </div><!--form-transferir-->

    <div class="form-extrato">
        <h1>Extrato De Transações</h1>
            <?php
        // Consulta SQL para buscar as transações
        $sql = "
        SELECT 
            c.nome AS nome_credito, 
            d.nome AS nome_debito, 
            e.valor, 
            e.mensagem 
        FROM banco_extrato e
        INNER JOIN usuarios c ON e.user_c = c.id
        INNER JOIN usuarios d ON e.user_d = d.id
        WHERE e.user_c = ? OR e.user_d = ?
        ORDER BY e.id DESC -- Adicione esta linha para ordenar por data em ordem decrescente
        LIMIT 10 -- Adicione esta linha para limitar o resultado a 10 registros
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $_SESSION['id'], $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Crédito</th><th>Débito</th><th>Valor</th><th>Mensagem</th></tr>";
            // Saída dos dados de cada linha
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row['nome_credito'] . "</td><td>" . $row['nome_debito'] . "</td><td>" . $row['valor'] . "</td><td>" . $row['mensagem'] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "Nenhuma transação encontrada";
        }
        ?>

    </div><!--form-transferir-->
</div><!--duas primeiras-->
</body>
</html>

<!-- arrumar o saldo negativo, arrumar o overflow do extratp-->