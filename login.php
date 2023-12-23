<?php
session_start();

if (empty($_POST) || empty($_POST["usuario"]) || empty($_POST["senha"])) {
    print "<script>location.href='index.php';</script>";
    exit; // Saída para evitar a execução adicional do código
}

include('config.php');

$usuario = $_POST["usuario"];
$senha = hash('sha256', $_POST["senha"]); // Hash da senha usando SHA-256

$captcha = $_POST["captcha"];

if ($captcha != $_SESSION["captcha"]) {
    // A resposta do CAPTCHA está incorreta, bloquear a tentativa de login
    print "<script>alert('A resposta do CAPTCHA está incorreta. Por favor, tente novamente.');</script>";
    print "<script>location.href='index.php';</script>";
    exit;
}

// Instruções preparadas para evitar injeção de SQL
$sql = "SELECT * FROM usuarios WHERE usuario = ? AND senha = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die($conn->error); // Tratar erro de preparação da instrução SQL
}

$stmt->bind_param("ss", $usuario, $senha);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $row = $res->fetch_object();
    $_SESSION["id"] = $row->id;
    $_SESSION["usuario"] = $usuario;
    $_SESSION["nome"] = $row->nome;
    $_SESSION["tipo"] = strval($row->tipo);
    print "<script>location.href='dashboard/';</script>";
} else {
    print "<script>alert('Usuário e/ou senha incorretos')</script>";
    print "<script>location.href='index.php';</script>";
}
