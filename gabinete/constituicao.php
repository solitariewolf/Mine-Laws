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

// Consulta SQL para obter as leis da tabela "leis"
$sql = "SELECT ID, Texto FROM leis";
$result_leis = $conn->query($sql);

// Recupere as leis que receberam pelo menos 3 votos
$stmt = $conn->prepare("SELECT * FROM votacoes_leis WHERE Total_Votos >= 3 AND Arquivado = 'não' AND Promulgado = 'não'");
$stmt->execute();
$result_votacoes = $stmt->get_result();
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
    <h5>Alterar constituição ou propor nova lei constitucional.</h5>
</div>

<!--==============início da seção dos formulários constitucionais==============-->
        <div class="section-leis">
            <h3 style="margin-top: 10px; color: #a84a32">Constituição &#128211</h3>
            <!--seção de envio de nova lei-->
                <div class="container">
                <h1>Nova Lei Constitucional</h1>
                    <form action="pages/enviar_lei.php" method="post">
                        <div class="mb-3">
                            <label for="textoLei" class="form-label">Caso deseje fazer uma nova lei insira o texto abaixo, para que uma nova lei constitucional entre em vigor é necessário 3 votos positivos dos membros fundadores e após isso a promulgação pelo presidente:</label>
                            <textarea class="form-control" id="textoLei" name="textoLei" rows="5"></textarea>
                        </div>
                    <button type="submit" class="btn btn-primary">Enviar Ao Plenário</button>
                    </form>
                </div>

            <!--seção de alterar lei existente-->
            <div class="container">
                <h1>Modificar Lei Constitucional Existente</h1>
                <form method="post" action="pages/modificar.php">
                    <label for="lei_existente">Selecione uma lei existente para alterar, para modificar lei existente na constituição são necessários 3 votos positivos dos membros fundadores:</label>
                    <select class="form-control bg-light rounded" name="lei_existente" id="lei_existente">
                        <option value="" style="max-width: 250px"; disabled selected>Escolha uma lei</option>
                        <?php while ($row = $result_leis->fetch_assoc()) { ?>
                            <option value="<?php echo $row['ID']; ?>"><?php echo $row['Texto']; ?></option>
                        <?php } ?>
                    </select>
                    <br>
                    <label for="nova_lei">Digite a nova lei:</label>
                    <textarea class="form-control" id="nova_lei" name="nova_lei" rows="5"></textarea>
                    <br>
                    <button type="submit" class="btn btn-primary">Enviar Ao Plenário</button>
                </form>
            </div>

            
            <!--seção das leis votadas no plenário-->
            <div class="container">
                <h1>Leis para Promulgação ou Arquivamento</h1>
                <div class="promulgacao">
                <?php while ($row = $result_votacoes->fetch_assoc()) { ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Lei ID: <?php echo $row['ID']; ?></h5>
                            <p class="card-text">Texto Original: <?php echo $row['Texto_Original']; ?></p>
                            <p class="card-text">Novo Texto: <?php echo $row['Novo_Texto']; ?></p>
                            <p class="card-text">Votos Positivos: <?php echo $row['Votos_Positivos']; ?></p>
                            <p class="card-text">Votos Negativos: <?php echo $row['Votos_Negativos']; ?></p>
                            <?php if ($row['Votos_Positivos'] >= 3) { ?>
                                <button type="button" class="btn btn-success" data-id="<?php echo $row['ID']; ?>">Promulgar</button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-danger" data-id="<?php echo $row['ID']; ?>">Arquivar</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                </div><!--promulgação-->
            </div>
        </div>
<!--==============fim da seção dos formulários constitucionais==============-->
</div><!--conteudo-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/script.js"></script>

</body>
</html>