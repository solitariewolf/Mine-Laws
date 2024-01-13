<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

// Buscar a taxa de juros da tabela de impostos
$query = "SELECT iva FROM imposto";
$result = mysqli_query($conn, $query);

if ($result) {
  $row = mysqli_fetch_assoc($result);
  $taxa_juros = $row['iva'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.scss">
    <link rel="stylesheet" href="../css/conteudo.css">
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
            <a href="../controladoria">Controladoria</a>
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
    <div class="logo-banco"> <img src="../img/banco/bank-logo.png" alt=""></div>
    <div id="mensagem"></div>
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
            <form id="form-transferir" action="transferir.php" method="post">
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
            <textarea id="mensagem-texto" name="mensagem"></textarea><br>
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

        </div><!--form-extrato-->
    </div><!--duas primeiras-->
</div><!--formularios-banco-->

<div class="formularios-banco">
    <div class="duas-primeiras">
        <div class="secao-emprestimo">

            <div class="emprestimo-container">
                <h1>Empréstimo Bancário</h1>
                <form id="form-emprestimo" action="emprestimo.php" method="post">
                Valor do Empréstimo: <input type="number" id="valor_emprestimo" name="valor_emprestimo" oninput="calcularValorTotal()" required><br>
                Taxa de Juros: <input type="number" id="taxa_juros" name="taxa_juros" value="<?php echo $taxa_juros; ?>" readonly><br>
                Prazo de Pagamento: 
                <select id="prazo_pagamento" name="prazo_pagamento" oninput="calcularValorTotal()" required>
                    <option value="7">7 dias</option>
                    <option value="14">14 dias</option>
                    <option value="21">21 dias</option>
                    <option value="30">30 dias</option>
                </select><br>
                Valor Total a Pagar: <input type="number" id="valor_total" name="valor_total" readonly><br>
                <input type="submit" value="Solicitar Empréstimo">
                </form>
            </div><!--emprestimo-container-->

        <div class="emprestimo-direito">
            <span>
                <h1>Regras sobre empréstimos</h1>
                <p>A taxa de juros é definida pelo presidente com autorização em lei complementar.</p>
                <p>Ao solicitar um <b>empréstimo</b> o valor deverá ser integralmente pago na data de vencimento, sob risco de multa e bloqueio de conta bancária.</p>
            </span>

            <div class="container-emprestados">
                <p>Seus Empréstimos</p>
                <div class="emprestados">
                <?php
                $id_jogador = $_SESSION['id'];

                // Buscar empréstimos
                $query = "SELECT * FROM emprestimos WHERE id_jogador = ? ORDER BY quitado DESC";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $id_jogador);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "Valor Emprestado: " . $row['valor_emprestado'] . "<br>";
                    echo "Valor Total: " . $row['valor_total'] . "<br>";
                    echo "Prazo de Pagamento: " . $row['prazo_pagamento'] . "<br>";
                    echo "Dia de Vencimento: " . $row['dia_vencimento'] . "<br>";
                    
                    // Se o empréstimo não estiver quitado, mostrar o botão "Quitar Empréstimo"
                    // Caso contrário, mostrar uma mensagem de que o empréstimo já foi quitado
                    if ($row['quitado'] == 'não') {
                        echo "<button onclick=\"quitarEmprestimo(" . $row['id_emprestimo'] . ")\">Quitar Dívida</button><br>";
                    } else {
                        echo "Esta dívida já foi quitada.<br>";
                    }
                    
                    echo "<hr>";
                }


                $stmt->close();
                ?>
                </div>
            </div>

        </div><!--emprestido-direito-->

        </div><!--secao-emprestimo-->
    </div><!--duas primeiras-->
</div><!--formularios-banco-->

</div><!--conteudo-->

    <script>
        feather.replace();
    </script>
    <script type="text/javascript" src="../js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
        $("#form-transferir").on("submit", function(event){
            event.preventDefault();

            $.ajax({
                url: 'transferir.php',
                type: 'post',
                data: $(this).serialize(),
                success: function(response){
                    $("#mensagem").html(response);
                }
            });
        });
    });

    $("#form-emprestimo").on("submit", function(event){
        event.preventDefault();
        $.ajax({
                url: 'emprestimo.php',
                type: 'post',
                data: $(this).serialize(),
                success: function(response){
                    $("#mensagem").html(response);
                }
            });
        });
    </script>
</body>
</html>

