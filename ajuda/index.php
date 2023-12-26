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
    <link rel="stylesheet" href="../css/profile.css">
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
            <a href=".">Ajuda</a>
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

    <div class="medalhas-ajuda">

        <h2>Orientações sobre as medalhas</h2>
        <div class="tabela-medalhas">
            <div class="corpo-medalhas">
                <h4>Medalha Presidenciável</h4>
                <img src="../img/medalha-presi.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que já foi presidente.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Fundador</h4>
                <img src="../img/medalha-fundador.png" alt="text">
                <h2>Medalha fornecida aos membros fundadores.</h2>
            </div>
            
            <div class="corpo-medalhas">
                <h4>Medalha Do Sem Vida</h4>
                <img src="../img/medalha-semvida.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que mais passa tempo jogando.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Cruz De Ferro</h4>
                <img src="../img/medalha-cruzferro.jpg" alt="text">
                <h2>Medalha fornecida ao jogador veterano.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Marinheiro</h4>
                <img src="../img/medalha-agua.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que gosta de explorar os oceanos.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do General</h4>
                <img src="../img/medalha-exercito.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que tem um exército forte.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Da Honra</h4>
                <img src="../img/medalha-honraria.jpg" alt="text">
                <h2>Medalha fornecida ao jogador em agradecimento a seus serviços prestados.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Bondoso</h4>
                <img src="../img/medalha-ajudante.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que ajuda os outros sem nada em troca.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Ás Dos Céus</h4>
                <img src="../img/medalha-asdoceu.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que domina os céus.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Cientista</h4>
                <img src="../img/medalha-cientista.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que desenvolve novas ideias.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Construtor</h4>
                <img src="../img/medalha-construtor.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que se destaca por ter muitas construções.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Minerador</h4>
                <img src="../img/medalha-minerador.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que se destaca na mineração.</h2>
            </div>

            
        </div><!--tabela medalhas-->
    </div><!--medalhas-ajuda-->
</div><!--conteudo-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/script.js"></script>

</body>
</html>

