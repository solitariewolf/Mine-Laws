<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');
// Recupere o ID do usuário da sessão
$usuario_id = $_SESSION['id']; // Usamos 'id' da sessão como identificador do usuário

// Recupere todas as medalhas que o usuário possui
$stmt = $conn->prepare("SELECT medalhas.nome, medalhas.imagem, medalhas.descricao FROM usuarios_medalhas JOIN medalhas ON usuarios_medalhas.medalha_id = medalhas.id WHERE usuarios_medalhas.usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$stmt = $conn->prepare("SELECT nome, email, usuario FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nome_atual = $row['nome'];
$email_atual = $row['email'];
$usuario_atual = $row['usuario'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Mine Laws MOD 1.0</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
</head>
<body>
<div class="corpo">
    <div class="alterar-form">
        <span><h3>Meu perfil</h3><h5>Alterar dados</h5></span>
            <form class="form-alterar" method="post" action="pages/alterar.php">
                <label for="nome">Alterar Nome:</label><br>
                <input type="text" id="nome" name="nome" value="<?php echo $nome_atual; ?>"><br>
                <label for="email">Alterar Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo $email_atual; ?>"><br>
                <label for="usuario">Alterar usuário:</label><br>
                <input type="text" id="usuario" name="usuario" value="<?php echo $usuario_atual; ?>"><br>
                <label for="senha">Alterar Senha:</label><br>
                <input type="password" id="senha" name="senha"><br>
                <input type="submit" value="Alterar">
            </form>
        </div>

    <!--seção das medalhas-->
    <div class="titulo-quadro"><h1>Quadro De Medalhas</h1></div>
    <div class="medalhas-perfil-container">
            <?php
        

        // Recupere o ID do usuário da sessão
        $usuario_id = $_SESSION['id']; // Usamos 'id' da sessão como identificador do usuário

        // Recupere todas as medalhas que o usuário possui
        $stmt = $conn->prepare("SELECT medalhas.nome, medalhas.imagem, medalhas.descricao FROM usuarios_medalhas JOIN medalhas ON usuarios_medalhas.medalha_id = medalhas.id WHERE usuarios_medalhas.usuario_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<div class='medalha-interno'>";
            echo "<h2 class='nome-medalha'>" . $row['nome'] . "</h2>";
            echo "<img src='" . $row['imagem'] . "' alt='" . $row['nome'] . "' class='corpo-medalhas-perfil'><br>";
            echo "</div>";
        }
        ?>
    </div><!--medalhas-perfil-->

</div><!--corpo-->
</body>
</html>