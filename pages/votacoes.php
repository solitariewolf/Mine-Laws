<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

$query = "SELECT * FROM leis_em_votacao";
$result = mysqli_query($conn, $query);
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

<table>
    <tr>
        <th>Votação N°</th>
        <th>Ação</th>
        <th>Nova Lei</th>
        <th>Votos Positivos</th>
        <th>Votos Negativos</th>
        <th>Aprovar</th>
        <th>Reprovar</th>
    </tr>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['acao']}</td>";
        echo "<td>{$row['nova_lei']}</td>";
        echo "<td>{$row['votos_positivos']}</td>";
        echo "<td>{$row['votos_negativos']}</td>";
        echo "<td><button class='aprovar-btn' data-id='{$row['id']}'>Aprovar</button></td>";
        echo "<td><button class='reprovar-btn' data-id='{$row['id']}'>Reprovar</button></td>";
        echo "</tr>";
    }
    ?>
</table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // Aprovar Lei
        $('.aprovar-btn').click(function () {
            const leiID = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: 'pages/aprovar.php', // Página que trata a aprovação
                data: { id: leiID },
                success: function () {
                    // Atualize o conteúdo da tabela ou qualquer outra ação desejada
                    alert('Votou em aprovar com sucesso!');
                }
            });
        });

        // Reprovar Lei
        $('.reprovar-btn').click(function () {
            const leiID = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: 'pages/reprovar.php', // Página que trata a reprovação
                data: { id: leiID },
                success: function () {
                    // Atualize o conteúdo da tabela ou qualquer outra ação desejada
                    alert('Votou em reprovar com sucesso!');
                }
            });
        });
    });
</script>

</div><!--corpo-->
</body>
</html>
