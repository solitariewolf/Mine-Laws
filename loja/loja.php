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

<h3>Itens à venda</h3>

<?php
    // Campo que exibe o valor total
    echo "<label for='valor_total'>Valor Total MC$:</label>";
    echo "<input type='text' name='valor_total' id='valor_total' value='' readonly>";
    ?>

<div class="gabinete-geral-loja">
    <?php    
    $sql = "SELECT id, nome, img, qtd, valor FROM itens";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Saída dos dados de cada linha
        while($row = $result->fetch_assoc()) {
            echo "<div class='item'>";
            echo "<h2>" . $row["nome"] . "</h2>";
            echo "<img src='" . $row["img"] . "' alt='" . $row["nome"] . "'>";
            echo "<p>Disponível: " . $row["qtd"] . "</p>";
            echo "<p>Valor MC$: " . $row["valor"] . "</p>";
            
            // Adiciona um formulário para comprar
            echo "<form action='loja/comprar.php' method='post'>";
            echo "<input type='hidden' name='item_id' value='" . $row["id"] . "'>";
            
            // Campo para a quantidade de itens a comprar
            echo "<label for='quantidade'>Quantidade: </label>";
            echo "<input type='number' name='quantidade' id='quantidade' min='1' max='" . $row["qtd"] . "' required onchange='updateTotal(this.value, " . $row["valor"] . ")'>";
            
            echo "<button class='botao-comprar' type='submit'>Comprar</button>";
            echo "</form>";
            
            echo "</div>";
        }
    } else {
        echo "Nenhum item encontrado.";
    }
    ?>
</div>

</body>
</html>