<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

// Consulta SQL para obter as leis da tabela votacoes_leis
$sql = "SELECT ID, Texto FROM leis";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Votações - Constituição</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
</head>
<body>

    <div class="container">
        
        <div class="brasao-const">
            <h1 style="text-align:center; font-size:20px">Constituição Minecraftniana</h1>
            <p>A constituição é a lei máxima do jogo, devendo ser aprovada por pelo menos 3 membros fundadores.</p>
            <img src="img/brasao.png" alt="">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Número da Lei</th>
                    <th>Texto</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['ID']; ?></td>
                        <td><?php echo $row['Texto']; ?></td>
                        <td>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div><!--container-->
</body>
</html>