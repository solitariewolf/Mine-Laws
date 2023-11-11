<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o arquivo foi enviado sem erros
    if (isset($_FILES["img"]) && $_FILES["img"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["img"]["name"];
        $filetype = $_FILES["img"]["type"];
        $filesize = $_FILES["img"]["size"];

        // Verifica a extensão do arquivo
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) die("Erro: Por favor, selecione um formato de arquivo válido.");

        // Verifica o tamanho do arquivo - 5MB máximo
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) die("Erro: O tamanho do arquivo é maior que o permitido.");

        // Verifica o tipo MIME do arquivo
        if (in_array($filetype, $allowed)) {
            // Verifica se o arquivo existe antes de fazer upload
            if (file_exists("upload/" . $_FILES["img"]["name"])) {
                echo $_FILES["img"]["name"] . " já existe.";
            } else {
                move_uploaded_file($_FILES["img"]["tmp_name"], "../img/loja/" . $_FILES["img"]["name"]);
                echo "O arquivo foi enviado com sucesso.";

            // Insere o novo item no banco de dados
            $nome = $_POST['nome'];
            $valor = $_POST['valor'];
            $qtd = $_POST['qtd'];
            $img = "./img/loja/" . $_FILES["img"]["name"];
            $sql = "INSERT INTO itens (nome, valor, img, qtd) VALUES ('$nome', '$valor', '$img', '$qtd')";
            if ($conn->query($sql) === TRUE) {
                echo "<script type='text/javascript'>alert('Novo registro criado com sucesso.'); window.location.href = '../dashboard.php';</script>";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
            }
        } else {
            echo "Erro: Ocorreu um problema ao fazer upload do seu arquivo. Por favor, tente novamente.";
        }
    } else {
        echo "Erro: " . $_FILES["img"]["error"];
    }
}
?>
