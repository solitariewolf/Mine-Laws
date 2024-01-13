<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}

include('../config.php');

$sql = "SELECT nome, email, tipo, id FROM usuarios";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.scss">
    <link rel="stylesheet" href="../css/conteudo.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/banco.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Mine Laws MOD 1.0</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
</head>
<body>

<div class="menu-principal">
    <nav class="menu" id="nav">
        <span class="nav-item active">
            <span class="icon">
                <i data-feather="home"></i>
            </span>
            <a href="../dashboard">Home</a>
        </span>
        <span class="nav-item">
            <span class="icon">
                <i data-feather="search"></i>
            </span>
            <a href="../leis">Leis</a>
        </span>
        <span class="nav-item">
            <span class="icon">
                <i data-feather="bell"></i>
            </span>
            <a href="../gabinete">Gabinete Presidente</a>
        </span>
        <span class="nav-item">
            <span class="icon">
                <i data-feather="star"></i>
            </span>
            <a href=".">Controladoria</a>
        </span>
        <span class="nav-item">
            <span class="icon">
                <i data-feather="star"></i>
            </span>
            <a href="../plenario">Plenário</a>
        </span>
        <span class="nav-item">
            <span class="icon">
                <i data-feather="star"></i>
            </span>
            <a href="../perfil">Meu Perfil</a>
        </span>
        <span class="nav-item">
            <span class="icon">
                <i data-feather="star"></i>
            </span>
            <a href="../jogadores">Jogadores</a>
        </span>
        <span class="nav-item">
            <span class="icon">
                <i data-feather="star"></i>
            </span>
            <a href="../ajuda">Ajuda</a>
        </span>
        <span class="nav-item">
            <span class="icon">
                <i data-feather="star"></i>
            </span>
            <a href="../dashboard/logout.php">Sair</a>
            <span class="icon">
            <?php
            print "Olá, " . $_SESSION["nome"];
            ?>
        </span>
        </span>
    </nav>
</div><!--menu-principal-->
<div class="clear"></div>

<div class="conteudo">

<div class="formularios-banco">
    <div class="duas-primeiras">

        <div class="brasao-gabinete">
            <h1 style="font-size:24px; text-align:center; color: white">Bem vindo a área de controladoria governamental, verifique se seu presidente não é corrupto!</h1>
            <img src="img/brasao.png" alt="">
        </div>

<div class="extrato-transacoes">
    <?php
    // Pega o ID do usuário da sessão
    $id_usuario = 1;

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
    echo "<p class='saldo-inicio'> "  . "O saldo do disponível em caixa do governo é de MC$: " . $money_formatado . "</p>";
    ?>
<div class="duas-primeiras">

<div class="controladoria">

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
    $stmt->bind_param('ii', $id, $id);
    $id = 1;
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
</div><!--extrato transacoes-->

            <div class="container-emprestados" style="margin: 19px;">
                <p>Empréstimos Realizados Pelo Governo</p>
                <div class="emprestados">
                    <?php
                    $id_jogador = '1';

                    // Buscar empréstimos
                    $query = "SELECT * FROM emprestimos WHERE id_jogador = ? ORDER BY quitado DESC";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('i', $id_jogador);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='controladoria-emprestimos'>";
                        echo "Valor Emprestado: " . $row['valor_emprestado'] . "<br>";
                        echo "Valor Total: " . $row['valor_total'] . "<br>";
                        echo "Prazo de Pagamento: " . $row['prazo_pagamento'] . "<br>";
                        echo "Dia de Vencimento: " . $row['dia_vencimento'] . "<br>";
                        echo "</div>";

                        if ($row['quitado'] == 'não') {
                            echo "Dívida em aberto.<br>";
                        } else {
                            echo "Esta dívida já foi quitada.<br>";
                        }
                        
                        echo "<hr>";
                    
                    }            

                    $stmt->close();
                    ?>
                </div><!--emprestados-->
            </div><!--container-emprestados-->
</div><!--controladoria-->

    </div><!--duas primeiras-->
</div><!--formularios-banco-->

</div><!--conteudo-->


    <script>
        feather.replace();
    </script>
    <script type="text/javascript" src="../js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>

