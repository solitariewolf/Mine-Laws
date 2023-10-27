<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Mine Laws MOD 1.0</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
</head>
<body>
             
    <aside id="painel-lateral" class="painel-lateral">
            <div class="botao-sair">
            <?php
            print "Olá, " . $_SESSION["nome"];
            print "<a href='logout.php' class='btn btn-danger'>Sair</a>";
            ?>
            </div><!--botao-sair-->
        <a href="#" onclick="exibirConteudo('pages/inicio.php')">Início</a>
        <a href="#" onclick="exibirConteudo('pages/constituicao.php')">Constituição</a>
        <a href="#" onclick="exibirConteudo('pages/complementares.html')">Leis Complementares</a>
        <a href="#" onclick="exibirConteudo('pages/decretos.html')">Decretos</a>
        <a href="#" onclick="exibirConteudo('pages/gabinete.php')">Gabiente do Presidente</a>
        <a href="#" onclick="exibirConteudo('pages/controladoria.html')">Controladoria</a>
        <a href="#" onclick="exibirConteudo('pages/votacoes_const.php')">Votações Constituição</a>
        <a href="#" onclick="exibirConteudo('pages/perfil.php')">Meu Perfil</a>
    </aside><!--painel-lateral-->

    <div id="conteudo"></div>

    <script type="text/javascript" src="js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>

