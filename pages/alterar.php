<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
?>
<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $senha = hash('sha256', $_POST["senha"]);

    $sql = "UPDATE usuarios SET usuario=?, senha=? WHERE id=?";
    $stmt= $conn->prepare($sql);
    $stmt->bind_param("ssi", $usuario, $senha, $_SESSION["id"]);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário e/ou senha alterado(s) com sucesso!'); location.href='../dashboard.php';</script>";
    } else {
        echo "<script>alert('Erro: " . $stmt->error . "');</script>";
    }
}
?>
