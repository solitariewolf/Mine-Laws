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
    <link rel="stylesheet" href="../css/dashboard.scss">
    <link rel="stylesheet" href="../css/conteudo.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/leis.css">
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

<div class="conteudo">
    <div class="conteudo-loja">
        <h3>Itens à venda</h3>
        <p>Certifique-se de ter espaço no inventário antes de adquirir um item, caso não possua espaço o item cairá para fora podendo ainda ser pego, verifique também se o item faz stack antes de adquirir, só pode ser adquirido 64 itens que fazem stack por vez, por exemplo 64 blocos de grama, itens que não fazem stack como exemplo a espada, só podem ser adquiridos 1 por vez.</p>
        <!-- formulário para a barra de pesquisa -->
        <form action="" method="post">
            <input type="text" name="search" placeholder="Pesquisar itens...">
            <input type="submit" value="Pesquisar">
        </form>
    </div><!--conteudo-loja-->

<?php
    // Campo que exibe o valor total da compra
    echo "<label for='valor_total'>Valor Total Compra MC$:</label>";
    echo "<input type='text' name='valor_total' id='valor_total' value='' readonly>";

    // Campo que exibe o valor total da venda
    echo "<label for='valor_total_venda'>Valor Total Venda MC$:</label>";
    echo "<input type='text' name='valor_total_venda' id='valor_total_venda' value='' readonly>";

    // Adicione este código para pesquisar itens
    $search = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = $_POST["search"];
    }

    $sql = "SELECT id, nome, img, qtd, valor FROM itens";
    if ($search != "") {
        $sql .= " WHERE nome LIKE '%$search%'";
    }
    $result = $conn->query($sql);
    ?>

<div class="gabinete-geral-loja">
    <?php    
    if ($result->num_rows > 0) {
        // Saída dos dados de cada linha
        while($row = $result->fetch_assoc()) {
            $valor_venda = $row["valor"];
            echo "<div class='item'>";
            echo "<h2>" . $row["nome"] . "</h2>";
            echo "<img src='" . $row["img"] . "' alt='" . $row["nome"] . "'>";
            echo "<p>Disponível: " . $row["qtd"] . "</p>";
            echo "<p>Compra MC$: " . $row["valor"] . "</p>";
            
            
            // Adiciona um formulário para comprar
            echo "<form action='comprar.php' method='post'>";
            echo "<input type='hidden' name='item_id' value='" . $row["id"] . "'>";
            
            // Campo para a quantidade de itens a comprar
            echo "<label for='quantidade'>Quantidade: </label>";
            echo "<input type='number' name='quantidade' id='quantidade' min='1' max='64'" . $row["qtd"] . "' required onchange='updateTotal(this.value, " . $row["valor"] . ")'>";
            
            echo "<button class='botao-comprar' type='submit'>Comprar</button>";
            echo "</form>";

            echo "<p>Venda MC$: " . $valor_venda . "</p>"; // Exibe o valor de venda

            // Adiciona um formulário para vender
            echo "<form action='vender.php' method='post'>";
            echo "<input type='hidden' name='item_id' value='" . $row["id"] . "'>";
            
            // Campo para a quantidade de itens a vender
            echo "<label for='quantidade_venda'>Quantidade: </label>";
            echo "<input type='number' name='quantidade_venda' id='quantidade_venda' min='1' max='64'" . $row["qtd"] . "' required onchange='updateTotalVenda(this.value, " . $row["valor"] . ")'>";

            
            
            echo "<button class='botao-vender' type='submit'>Vender</button>";
            echo "</form>";
            
            echo "</div>";
        }
    } else {
        echo "Nenhum item encontrado.";
    }
    ?>
</div>



</div><!--conteudo-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/script.js"></script>

</body>
</html>