<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if ($_SESSION['tipo'] != '2') {
    // Se o tipo de usuário não for 2, redirecionar para proibido.php
    header('Location: proibido.php');
    exit();
} else {
    // Coloque aqui o código para usuários do tipo 2
}

// Consulta SQL para obter os itens
$sql = "SELECT ID, nome, qtd, valor FROM itens";
$resultitens = $conn->query($sql);
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
            <a href=".">Gabinete Presidente</a>
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

<div class="inicial">
    <div class="logo"><img src="../img/brasao.png" alt=""></div>
    <h3>Bem vindo Sr. Presidente ao gerenciamento da loja, preço base 1 diamante 1k.</h3>
</div>

<div class="section-leis">

        <div class="display-grid">
            <div class="form-gabinete-geral-loja">
                <form id="form-enviar-loja" action="pages/upload.php" method="post" enctype="multipart/form-data">
                    <label for="nome">Nome:</label><br>
                    <input type="text" id="nome" name="nome"><br>
                    <label for="valor">Valor em MC$:</label><br>
                    <input type="number" id="valor" name="valor" step="0.01" min="0"><br>
                    <label for="qtd">Quantidade:</label><br>
                    <input type="number" id="qtd" name="qtd"><br>
                    <label for="img">Selecione a imagem:</label><br>
                    <input type="file" id="img" name="img" accept="image/*"><br>
                    <input type="submit" value="Cadastrar Item">
                </form>
            </div><!--form-gabinete-geral-loja-->

            <div class="form-gabinete-geral-loja">
                <form method="post" action="pages/updtitem.php">
                    <label for="item_existente">Selecione um item</label>
                    <select class="form-control bg-light rounded" name="item_existente" id="item_existente">
                        <option value="" style="max-width: 250px"; disabled selected>Escolha uma item</option>
                        <?php while ($row = $resultitens->fetch_assoc()) { ?>
                            <option value="<?php echo $row['ID']; ?>"><?php echo $row['nome']; ?></option>
                        <?php } ?>
                    </select>
                    <br>
                    <label for="nova_item">Alterar Nome:</label>
                    <input class="form-control" type="text" name="nova_item" id="nova_item">
                    <br>
                    <label for="nova_qtd">Alterar Quantidade:</label>
                    <input class="form-control" type="number" name="nova_qtd" id="nova_qtd">
                    <br>
                    <label for="novo_valor">Alterar Valor:</label>
                    <input class="form-control" type="number" name="novo_valor" id="novo_valor" step="0.01" min="0">
                    <br>
                    <button type="submit" class="btn btn-primary">Atualizar item</button>
                </form>
            </div>
        </div><!--display-grid-->
            <h3>Itens cadastrados a venda</h3>
            <div class="gabinete-geral-loja">
                <?php   
                    $sql = "SELECT nome, img, qtd, valor FROM itens";
                    $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Saída dos dados de cada linha
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='item'>";
                        echo "<h2>" . $row["nome"] . "</h2>";
                        echo "<img src='" . $row["img"] . "' alt='" . $row["nome"] . "'>";
                        echo "<p>Quantidade: " . $row["qtd"] . "</p>";
                        echo "<p>Valor MC$: " . $row["valor"] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "Nenhum item encontrado.";
                }
                ?>
            </div><!--gabinete-geral-loja-->
</div><!--section-leis-->

  
</div><!--conteudo-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/script.js"></script>

</body>
</html>