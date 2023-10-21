<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='index.php'</script>";
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
    <div class="botao-sair">
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand"></a>
            <?php
                print "Olá, " . $_SESSION["nome"];
                print "<a href='logout.php' class='btn btn-danger'>Sair</a>";
            ?>
            </div>
        </nav>
    </div><!--botao-sair-->
                <aside id="painel-lateral" class="painel-lateral">
                    <a href="#" onclick="exibirConteudo('pages/inicio.php')">Início</a>
                    <a href="#" onclick="exibirConteudo('pages/constituicao.html')">Constituição</a>
                    <a href="#" onclick="exibirConteudo('pages/complementares.html')">Leis Complementares</a>
                    <a href="#" onclick="exibirConteudo('pages/decretos.html')">Decretos</a>
                    <a href="#" onclick="exibirConteudo('pages/gabiente.html')">Gabiente do Presidente</a>
                    <a href="#" onclick="exibirConteudo('pages/controladoria.html')">Controladoria</a>
                    <a href="#" onclick="exibirConteudo('pages/perfil.html')">Meu Perfil</a>
                </aside><!--painel-lateral-->

                    <!-- Conteúdo -->
                    <div id="conteudo"></div>
    <script type="text/javascript" src="js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>
