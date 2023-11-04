<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

$sql = "SELECT nome, email, tipo, id FROM usuarios";
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
<div class="corpo">
        <div class="container">
        <h1 class="my-4">Jogadores</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Cargo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    if ($row['tipo'] == 2) {
                        echo "<td>Presidente</td>";
                    } else if ($row['tipo'] == 1) {
                        echo "<td>Membro Fundador</td>";
                    } else {
                        echo "<td></td>";
                    }
                    echo "<td><a href='#' onclick=\"exibirConteudo('pages/perfil_usuario.php?id=" . $row['id'] . "')\" class='btn btn-primary'>Exibir Perfil</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div><!--corpo-->
</body>
</html>