<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}

include('../config.php');
//buscar as leis no db
$query = "SELECT id, numero_lei, lei FROM leis";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Erro ao buscar as leis: " . mysqli_error($conexao));
}

$consulta_leis = "SELECT l.id, l.numero_lei, le.nova_lei, le.acao, l.lei, le.votos_positivos, le.votos_negativos
FROM leis l
LEFT JOIN leis_em_votacao le ON l.id = le.id_lei_original
GROUP BY l.id
HAVING (SUM(le.votos_positivos) + SUM(le.votos_negativos)) = 3";
$result_consulta = mysqli_query($conn, $consulta_leis);

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

    <div class="presilogo">
    <img class="" src="img/presilogo.png" alt="">
    <p>Este é o gabiente presidencial, onde você fará administração da ONU Minecraftniana</p>
    </div><!--presilogo-->

    <div class="envio-lei">
    <h4>Alterar a constituição: para alterar a constituição é necessário o voto positivo de todos os membros fundadores</h4>
        <form action="pages/processa_lei.php" method="post">
            <div>
                <label for="select_lei">Selecione uma Lei:</label>
            </div>
        <select name="lei" id="select_lei">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $primeiras_palavras = implode(' ', array_slice(explode(' ', $row['lei']), 0, 5));
                echo "<option value='{$row['id']}'>Lei {$row['numero_lei']} - {$primeiras_palavras}...</option>";
            }
            ?>
        </select>

        <div class="acao-container">
            <label for="acao_lei">Ação:</label>
            <div>
                <input type="radio" name="acao_lei" value="revogar" id="revogar_lei">
                <label for="revogar_lei">Revogar Lei</label>
                <input type="radio" name="acao_lei" value="alterar" id="alterar_lei">
                <label for="alterar_lei">Alterar Lei</label>
            </div>
        </div>

        <div id="texto_lei" style="display: none;">
            <label for="nova_lei">Nova Lei:</label>
            <textarea name="nova_lei" id="nova_lei" rows="4" cols="50"></textarea>
        </div>

            <input type="submit" value="Enviar para Votação">
        </form>
    </div><!--envio-lei-->

    <!--seção sobre as leis com votação encerrada-->

<div class="leis-votacao">
    <h2>Leis constitucionais prontas para promulgação ou arquivamento:</h2>
    <table>
        <tr>
            <th>Texto Atual</th>
            <th>Novo Texto</th>
            <th>Solicitado para</th>
            <th>Votos Positivos</th>
            <th>Votos Negativos</th>
            <th>Ação</th>
        </tr>
        <?php
        while ($row_consulta = mysqli_fetch_assoc($result_consulta)) {
            echo "<tr>";
            echo "<td>{$row_consulta['lei']}</td>";
            echo "<td>{$row_consulta['nova_lei']}</td>";
            echo "<td>{$row_consulta['acao']}</td>";
            echo "<td>{$row_consulta['votos_positivos']}</td>";
            echo "<td>{$row_consulta['votos_negativos']}</td>";
            echo "<td>";
            
            if (($row_consulta['votos_positivos'] - $row_consulta['votos_negativos']) >= 3) {
                echo "<button class='promulgar-btn' data-lei-id='{$row_consulta['id']}'>Promulgar</button>";
            } else {
                echo "<button class='deletar-btn' data-lei-id='{$row_consulta['id']}'>Arquivar</button>";
            }
            
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div><!--leis em votacao-->


  <!-- fim da seção sobre as leis com votação encerrada-->

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
            document.getElementById('alterar_lei').addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('texto_lei').style.display = 'block';
                } else {
                    document.getElementById('texto_lei').style.display = 'none';
                }
            });

            document.getElementById('revogar_lei').addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('texto_lei').style.display = 'none';
                }
            });
                    function checkSubmitButton() {
                var alterarLei = document.getElementById('alterar_lei');
                var revogarLei = document.getElementById('revogar_lei');
                var submitButton = document.querySelector('input[type="submit"]');

                if (alterarLei.checked || revogarLei.checked) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
            }

            //seção leis com votação encerrada
            $(document).ready(function () {
        // Promulgar Lei
        $('.promulgar-btn').click(function () {
            const leiID = $(this).data('lei-id');
            $.ajax({
                type: 'POST',
                url: 'pages/promulgar_lei.php', // Página que trata a promulgação
                data: { id: leiID },
                success: function () {
                    // Atualize a página ou qualquer outra ação desejada
                    alert('Lei promulgada com sucesso!');
                }
            });
        });

        // Deletar Votação
        $('.deletar-btn').click(function () {
            const leiID = $(this).data('lei-id');
            $.ajax({
                type: 'POST',
                url: 'pages/deletar_votacao.php', // Página que trata a exclusão da votação
                data: { id: leiID },
                success: function () {
                    // Atualize a página ou qualquer outra ação desejada
                    alert('Votação deletada com sucesso!');
                }
            });
        });
    });
            </script>

</div><!--corpo-->
</body>
</html>
