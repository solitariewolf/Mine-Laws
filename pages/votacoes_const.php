<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

// Consulta SQL para obter as leis da tabela votacoes_leis
$sql = "SELECT ID, Texto_Original, Novo_Texto, Votos_Positivos, Votos_Negativos, Total_Votos FROM votacoes_Leis WHERE Arquivado = 'não' AND Promulgado = 'não'";
$result = $conn->query($sql);

// Consulta SQL para obter as leis da tabela votacoes_leis_complementares
$sql = "SELECT ID, Texto_Original, Novo_Texto, Votos_Positivos, Votos_Negativos, Total_Votos FROM votacoes_Leis_complementares WHERE Arquivado = 'não' AND Promulgado = 'não'";
$result2 = $conn->query($sql);

// Consulta SQL para obter as votacoes de honrarias
$sql = "SELECT votacoes_medalhas.id, usuarios.Nome AS Usuario, medalhas.nome AS Medalha, Votos_Positivos, Votos_Negativos, Total_Votos 
FROM votacoes_medalhas 
JOIN usuarios ON votacoes_medalhas.Usuario = usuarios.id
JOIN medalhas ON votacoes_medalhas.Medalha = medalhas.id
WHERE Arquivado = 'não' AND Promulgado = 'não'";
$result3 = $conn->query($sql);
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
        <h1>Votações - Constituição</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Número da Lei</th>
                    <th>Texto Original</th>
                    <th>Novo Texto</th>
                    <th>Votos Positivos</th>
                    <th>Votos Negativos</th>
                    <th>Total de Votos</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['ID']; ?></td>
                        <td><?php echo $row['Texto_Original']; ?></td>
                        <td><?php echo $row['Novo_Texto']; ?></td>
                        <td><?php echo $row['Votos_Positivos']; ?></td>
                        <td><?php echo $row['Votos_Negativos']; ?></td>
                        <td><?php echo $row['Total_Votos']; ?></td>
                        <td>
                        <form method="post" action="pages/votar.php">
                        <input type="hidden" name="lei_id" value="<?php echo $row['ID']; ?>">
                        <button type="submit" name="voto" value="Positivo" class="btn btn-success">Votar a Favor</button>
                        <button type="submit" name="voto" value="Negativo" class="btn btn-danger">Votar Contra</button>
                        </form>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div><!--container-->
        <!--divisor de seções-->
    <div class="container">
        <h1>Votações - Leis Complementares</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Número da Lei</th>
                    <th>Texto Original</th>
                    <th>Novo Texto</th>
                    <th>Votos Positivos</th>
                    <th>Votos Negativos</th>
                    <th>Total de Votos</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result2->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['ID']; ?></td>
                        <td><?php echo $row['Texto_Original']; ?></td>
                        <td><?php echo $row['Novo_Texto']; ?></td>
                        <td><?php echo $row['Votos_Positivos']; ?></td>
                        <td><?php echo $row['Votos_Negativos']; ?></td>
                        <td><?php echo $row['Total_Votos']; ?></td>
                        <td>
                        <form method="post" action="pages/votar_complementar.php">
                        <input type="hidden" name="lei_id" value="<?php echo $row['ID']; ?>">
                        <button type="submit" name="voto" value="Positivo" class="btn btn-success">Votar a Favor</button>
                        <button type="submit" name="voto" value="Negativo" class="btn btn-danger">Votar Contra</button>
                        </form>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div><!--container-->
        <!--divisor de seções-->

    <div class="container">
        <h1>Votações - Honrarias</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Votação N°</th>
                    <th>Usuário</th>
                    <th>Medalha</th>
                    <th>Votos Positivos</th>
                    <th>Votos Negativos</th>
                    <th>Total de Votos</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result3->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['Usuario']; ?></td>
                        <td><?php echo $row['Medalha']; ?></td>
                        <td><?php echo $row['Votos_Positivos']; ?></td>
                        <td><?php echo $row['Votos_Negativos']; ?></td>
                        <td><?php echo $row['Total_Votos']; ?></td>

                        <td>
                        <form method="post" action="pages/votar_medalha.php">
                        <input type="hidden" name="lei_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="voto" value="Positivo" class="btn btn-success">Votar a Favor</button>
                        <button type="submit" name="voto" value="Negativo" class="btn btn-danger">Votar Contra</button>
                        </form>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div><!--container--> 

</div><!--corpo-->
</body>
</html>


VOTACAO DAS MEDALHAS FUNCIONANDO, DEVO IMPLEMENTAR A LÓGICA PARA PROMULGAÇÃO E INSERIR NA TABELA DE MEDALHAS DOS USUARIOS