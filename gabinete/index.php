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
    <h3>Bem vindo Sr. Presidente, administre sua nação aqui.</h3>

    <div class="cards-container">

        <div class="banco-form">
            <div class="banco-home">
                <h3>Propor/Alterar Constituição</h3>
                <img src="../img/constituicao.png" alt="logo banco">
                <a href="constituicao.php" class="btn btn-primary">Acessar</a>
            </div>
        </div>

        <div class="banco-form">
            <div class="loja-home">
                <h3>Propor/Alterar LC</h3>
                <img src="../img/complementares.png" alt="logo banco">
                <a href="complementar.php" class="btn btn-primary">Acessar</a>
            </div><!--loja-home-->
        </div><!--banco-form-->

        <div class="banco-form">
            <div class="loja-home" style="background-color: #329ba8">
                <h3>Editar/Suspender Decretos</h3>
                <img src="../img/decretos.png" alt="logo banco">
                <a href="decretos.php" class="btn btn-primary">Acessar</a>
            </div><!--loja-home-->
        </div><!--banco-form-->

        <div class="banco-form">
            <div class="loja-home" style="background-color: #FF5733">
                <h3>Ministério da Comunicação</h3>
                <img src="../img/comunicacao.png" alt="logo banco">
                <a href="micom.php" class="btn btn-primary">Acessar</a>
            </div><!--loja-home-->
        </div><!--banco-form-->

        <div class="banco-form">
            <div class="loja-home" style="background-color: #33FF57">
                <h3>Entregar Condecorações</h3>
                <img src="../img/condecoracao.png" alt="logo banco">
                <a href="medalhas.php" class="btn btn-primary">Acessar</a>
            </div><!--loja-home-->
        </div><!--banco-form-->

        <div class="banco-form">
            <div class="loja-home" style="background-color: #3357FF">
                <h3>Gerenciar Loja</h3>
                <img src="../img/loja.png" alt="logo banco">
                <a href="manageloja.php" class="btn btn-primary">Acessar</a>
            </div><!--loja-home-->
        </div><!--banco-form-->
        

        <div class="banco-form">
            <div class="loja-home" style="background-color: #FF33F6">
                <h3>Ministério da Fazenda</h3>
                <img src="../img/fazenda.png" alt="logo banco">
                <a href="fazenda.php" class="btn btn-primary">Acessar</a>
            </div><!--loja-home-->
        </div><!--banco-form-->

        <div class="banco-form">
            <div class="loja-home" style="background-color: #33FFF5">
                <h3>Cadastro de Jogadores</h3>
                <img src="../img/cadastro.png" alt="logo banco">
                <a href="#" class="btn btn-primary">Acessar</a>
            </div><!--loja-home-->
        </div><!--banco-form-->

        <div class="banco-form">
            <div class="loja-home" style="background-color: #FF5733">
                <h3>Ministério do Interior</h3>
                <img src="../img/interior.png" alt="logo banco">
                <a href="minin.php" class="btn btn-primary">Acessar</a>
            </div><!--loja-home-->
        </div><!--banco-form-->
            

    </div><!--cards-container-->
</div><!--conteudo-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/script.js"></script>

</body>
</html>