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
    <link rel="stylesheet" href="../css/style-main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Perfil do Usuário</title>
</head>
<body>
<div class="corpo">
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

</div>
</body>
</html>
