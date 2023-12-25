<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

// Consulta SQL para obter os decretos
$sql = "SELECT ID, Texto, Votos_Derrubar FROM decretos";
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
            <h1 style="text-align:center; font-size:20px">Decretos</h1>
            <p>Os decretos tem validade imediata e se comparam a leis enquanto estiverem em vigor e devem ser seguidos por todos membros do jogo. Caso dois membros optem por pedir a suspensão do decreto então ele será suspenso de modo irreversível.</p>
            <img src="img/brasao.png" alt="">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Número Decreto</th>
                    <th>Texto Original</th>
                    <th>Votos Para Derrubar</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['ID']; ?></td>
                        <td>
                            <?php 
                            if ($row['Votos_Derrubar'] >= 2) {
                                echo '<b>Decreto Suspenso</b> - <s>' . $row['Texto'] . '</s>';
                            } else {
                                echo $row['Texto'];
                            }
                            ?>
                        </td>
                        <td><?php echo $row['Votos_Derrubar']; ?></td>
                        <td>
                        <form method="post" action="pages/derrubar_decreto.php">
                            <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">
                            <button type="submit" name="voto" value="Positivo" class="btn btn-danger">Derrubar Decreto</button>
                        </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>