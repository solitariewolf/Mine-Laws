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

// Recupere as honrarias que receberam pelo menos 3 votos
$stmt = $conn->prepare("SELECT votacoes_medalhas.id, usuarios.Nome AS Usuario, medalhas.nome AS Medalha, Votos_Positivos, Votos_Negativos, Total_Votos 
FROM votacoes_medalhas 
JOIN usuarios ON votacoes_medalhas.Usuario = usuarios.id
JOIN medalhas ON votacoes_medalhas.Medalha = medalhas.id
WHERE Total_Votos >= 2 AND Arquivado = 'não' AND Promulgado = 'não'");
$stmt->execute();
$result_votacoes_medalhas = $stmt->get_result();
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

<div class="container">

    <h4>Medalhas - São honrarias dadas aos jogadores pelo presidente com aprovação dos membros fundadores</h4>
    <div class="medalhas-plenario-geral">
        <div class="medalhas-plenario">
            <h2>Enviar Honraria Para o Plenário</h2>

            <form action="pages/enviar_medalha.php" method="post">
            <label for="usuario">Jogador:</label><br>
            <select id="usuario" name="usuario" class="form-control bg-light rounded">
                <?php
                $usuarios3 = $conn->query("SELECT * FROM usuarios");
                foreach ($usuarios3 as $usuario) {
                    echo "<option value=\"" . $usuario['id'] . "\">" . $usuario['nome'] . "</option>";
                }
                ?>
            </select><br>
            <label for="medalha">Medalha:</label><br>
            <select id="medalha" name="medalha" class="form-control bg-light rounded">
                <?php
                $medalhas = $conn->query("SELECT * FROM medalhas");
                foreach ($medalhas as $medalha) {
                    echo "<option value=\"" . $medalha['id'] . "\">" . $medalha['nome'] . "</option>";
                }
                ?>
            </select><br>
            <input class="btn btn-danger" type="submit" value="Enviar a Plenário">
            </form>
        </div><!--medalhas-plenario-->

        <div class="medalhas-plenario">
                <h2>Medalhas já votadas</h2>
                <div class="promulgacao">
                <?php while ($row = $result_votacoes_medalhas->fetch_assoc()) { ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Votação N° <?php echo $row['id']; ?></h5>
                            <p class="card-text">Usuário: <?php echo $row['Usuario']; ?></p>
                            <p class="card-text">Medalha: <?php echo $row['Medalha']; ?></p>
                            <?php if ($row['Votos_Positivos'] >= 2) { ?>
                                <button type="button" class="btn-success3" data-id="<?php echo $row['id']; ?>">Promulgar</button>
                            <?php } else { ?>
                                <button type="button" class="btn-danger3" data-id="<?php echo $row['id']; ?>">Arquivar</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                </div><!--promulgação-->
        </div><!--medalhas-plenario-->
    </div><!--medalhas-plenario-geral-->
</div>

</div><!--conteudo-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/script.js"></script>

</body>
</html>