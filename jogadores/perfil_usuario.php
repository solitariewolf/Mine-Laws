<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

// Recupere os dados do usuário
$stmt = $conn->prepare("SELECT nome, email, usuario, tipo FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
} else {
echo "ID de usuário não fornecido.";
exit;
}

$stmt_medalhas = $conn->prepare("SELECT medalhas.nome, medalhas.imagem, medalhas.descricao FROM usuarios_medalhas JOIN medalhas ON usuarios_medalhas.medalha_id = medalhas.id WHERE usuarios_medalhas.usuario_id = ?");
$stmt_medalhas->bind_param("i", $usuario_id);
$stmt_medalhas->execute(); // Adicione esta linha
$result_medalhas = $stmt_medalhas->get_result();
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
            <a href=".">Jogadores</a>
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
<div class="clear"></div>

<div class="conteudo">
    
<div class="container">
        <h1 class="my-4">Perfil do Usuário</h1>
        <p><strong>Nome:</strong> <?php echo $row['nome']; ?></p>
        <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
        <p><strong>Usuário:</strong> <?php echo $row['usuario']; ?></p>
        <p><strong>Cargo:</strong> <?php echo $row['tipo'] == 2 ? 'Presidente' : ($row['tipo'] == 1 ? 'Membro Fundador' : ''); ?></p>
    </div>
    
    <div class="exibir-perfil-medalhas">
    <h5>Quadro de Medalhas</h5>    
        <div class="medalhas-perfil-container">
            <?php
            while ($row_medalhas = $result_medalhas->fetch_assoc()) {
                echo "<div class='medalha-interno'>";
                echo "<h2 class='nome-medalha'>" . $row_medalhas['nome'] . "</h2>";
                echo "<img src='" . $row_medalhas['imagem'] . "' alt='" . $row_medalhas['nome'] . "' class='corpo-medalhas-perfil'><br>";
                echo "</div>";
            }
            ?>
        </div>
    </div><!--exibir-perfil-medalhas-->
    
</div><!--conteudo-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/script.js"></script>

</body>
</html>

