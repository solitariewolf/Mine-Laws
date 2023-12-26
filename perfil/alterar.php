<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
?>
<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $usuario = $_POST["usuario"];
    $senha = !empty($_POST["senha"]) ? hash('sha256', $_POST["senha"]) : null;
    
    $sql = "UPDATE usuarios SET ";
    $params = array();
    $types = "";
    
    if (!empty($nome)) {
        $sql .= "nome=?, ";
        array_push($params, $nome);
        $types .= "s";
    }
    
    if (!empty($email)) {
        $sql .= "email=?, ";
        array_push($params, $email);
        $types .= "s";
    }
    
    if (!empty($usuario)) {
        $sql .= "usuario=?, ";
        array_push($params, $usuario);
        $types .= "s";
    }
    
    if (!empty($senha)) {
        $sql .= "senha=?, ";
        array_push($params, $senha);
        $types .= "s";
    }
    
    // Remove a última vírgula e espaço
    $sql = rtrim($sql, ", ");
    
    $sql .= " WHERE id=?";
    array_push($params, $_SESSION["id"]);
    $types .= "i";
    
    $stmt= $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        echo "<script>alert('Dados alterados com sucesso!'); location.href='.';</script>";
    } else {
        echo "<script>alert('Erro: " . $stmt->error . "');</script>";
    }    
}
?>
