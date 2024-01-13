<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['form_name'] == 'isc_form') {
        $isc = $_POST["percentual"];

    include('../../config.php');
    
            // A consulta SQL para atualizar o valor do isc
            $sql = "UPDATE imposto SET isc = $isc WHERE id = 1";

            // Execute a consulta SQL
            if ($conn->query($sql) === TRUE) {
                echo "Valor de ISC atualizado com sucesso!";
            } else {
                echo "Erro ao atualizar o valor de ISC: " . $conn->error;
            }

            // Feche a conexão com o banco de dados
            $conn->close();
        }
        // Adicione mais condições aqui para outros formulários
    }
?>