<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['form_name'] == 'iva_form') {
        $iva = $_POST["percentual"];

    include('../../config.php');
    
            // A consulta SQL para atualizar o valor do iva
            $sql = "UPDATE imposto SET iva = $iva WHERE id = 1";

            // Execute a consulta SQL
            if ($conn->query($sql) === TRUE) {
                echo "Valor de IVA atualizado com sucesso!";
            } else {
                echo "Erro ao atualizar o valor de IVA: " . $conn->error;
            }

            // Feche a conexão com o banco de dados
            $conn->close();
        }
        // Adicione mais condições aqui para outros formulários
    }
?>