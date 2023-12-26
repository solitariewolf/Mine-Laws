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
            <a href="logout.php">Sair</a>
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

<div class="inicial">
    <div class="logo"><img src="../img/brasao.png" alt=""></div>

    <div class="cards-container">
        <div class="banco-form">
            <div class="banco-home">
                <h3>Conta Bancária</h3>
                <img src="../img/banco/bank-logo.png" alt="logo banco">
                <a href="../banco" class="btn btn-primary">Acessar Banco</a>
            </div>
        </div>

        <div class="banco-form">
            <div class="loja-home">
                <h3>Loja</h3>
                <img src="../img/banco/loja-logo.png" alt="logo banco">
                <button type="submit" class="btn btn-primary">Acessar Loja</button>
            </div><!--loja-home-->
        </div><!--banco-form-->

        <div class="jogadores-card">
            <div class="jogadores-home">
                <h3>Jogadores</h3>
                <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Cargo</th>
                </tr>
            </thead>
                    <tbody>
                        <?php
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['nome'] . "</td>";
                            if ($row['tipo'] == 2) {
                                echo "<td>Presidente</td>";
                            } else if ($row['tipo'] == 1) {
                                echo "<td>Membro Fundador</td>";
                            } else {
                                echo "<td></td>";
                            }               
                        }
                        ?>
                    </tbody>
                </table>
            </div><!--loja-home-->
        </div><!--banco-form-->
    </div><!--cards-container-->

</div><!--inicial-->

<div class="box-presidencia">
    <div class="texto-presidencia">
        <div class="presidencia">
            <h3>Mensagem da Presidência</h3>
                <?php
                    // Consulta SQL para buscar o texto
            $sql = "SELECT texto FROM mensagem_presidencia";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Saída dos dados de cada linha
                while($row = $result->fetch_assoc()) {
                    echo $row["texto"];
                }
            } else {
                echo "0 resultados";
            }
            $conn->close();
            ?>
        </div>
    </div>
</div>

</div><!--conteudo-->

    <script>
        feather.replace();
    </script>
    <script type="text/javascript" src="../js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>

