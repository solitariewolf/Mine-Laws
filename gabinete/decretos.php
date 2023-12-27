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

// Consulta SQL para obter os decretos
$sql = "SELECT ID, Texto, Votos_Derrubar FROM decretos";
$resultdec = $conn->query($sql);
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
</div>

<!--==============Início da seção dos formulários de Decretos==============-->
    <!--seção sobre decretos-->
    <div style="border: 3px solid #ccc; margin-top: 15px; margin-bottom: 15px">
<h3 style="margin-top: 10px; color: #128221">Decretos Presidenciais &#128220</h3>
    <div class="container">
        <p>O decreto presidencial é o maior poder do presidente, decretos tem validade imediata como lei, mas podem ser derrubados se dois dos membros fundadores decidirem derrubar o decreto, observe que o decreto não pode passar por cima das leis complementares nem da constituição.</p>
        <form method="post" action="pages/inserir_decreto.php">
            <label for="texto_decreto">Digite o texto do decreto:</label>
            <textarea class="form-control" name="texto_decreto" id="texto_decreto"></textarea>
            <br>
            <button type="submit" class="btn btn-primary">Inserir Decreto</button>
        </form>
    </div><!--container-->
<div class="container">
    <h1>Decretos em Vigor</h1>
    <p>Lista de decretos em vigor ou suspenso, observe que ao apertar em suspender o decreto será suspenso imediatamente de modo irreversível.</p>
    <div class="decretos">
        <table class="table">
            <thead>
                <tr>
                    <th>Número Decreto</th>
                    <th>Texto Original</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultdec->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['ID']; ?></td>
                        <td>
                            <?php 
                            if ($row['Votos_Derrubar'] >= 2) {
                                echo '<b>Decreto Suspenso</b> - <s>' . $row['Texto'] . '</s>';
                            } else {
                                echo $row['Texto'];
                            }
                            ?>
                        </td>
                        <td>
                        <form method="post" action="pages/suspender_decreto.php">
                            <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">
                            <button type="submit" name="voto" value="Positivo" class="btn btn-danger">Suspender Decreto</button>
                        </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div><!--decretos-->

        
    </div>

</div><!--conteudo leis e decretos-->
<!--==============fim da seção dos formulários de Decretos==============-->

</div><!--conteudo-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/script.js"></script>

</body>
</html>