<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');
// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['item_existente'];
    $nome = $_POST['nova_item'];
    $valor = $_POST['novo_valor'];
    $qtd = $_POST['nova_qtd'];

    // Atualiza o item no banco de dados
    $sql = "UPDATE itens SET nome = '$nome', valor = '$valor', qtd = '$qtd' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>alert('Item atualizado com sucesso.'); window.location.href = '../dashboard.php';</script>";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>