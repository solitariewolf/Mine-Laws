<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

$idItem = $_GET['id'];

// Busca o item no banco de dados
$sql = "SELECT nome, valor, qtd FROM itens WHERE id = $idItem";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Saída dos dados de cada linha
    while($row = $result->fetch_assoc()) {
        echo json_encode($row);
    }
} else {
    echo "Nenhum item encontrado.";
}
$conn->close();
?>