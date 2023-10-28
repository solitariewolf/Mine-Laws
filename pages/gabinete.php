<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['lei_existente'])) {
        // O usuário selecionou uma lei existente
        $lei_id = $_POST['lei_existente'];
    } elseif (isset($_POST['nova_lei'])) {
        // O usuário inseriu uma nova lei
        $nova_lei = $_POST['nova_lei'];

        // Insira a nova lei na tabela "leis" como "Texto_Original"
        $sql = "INSERT INTO leis (Texto_Original) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([$nova_lei])) {
            echo "<script>alert('Nova lei inserida com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao inserir a nova lei. Por favor, tente novamente.');</script>";
        }
    }

    // Outras ações com base no $lei_id (por exemplo, envio de votação)
}

// Consulta SQL para obter as leis da tabela "leis"
$sql = "SELECT ID, Texto FROM leis";
$result_leis = $conn->query($sql);

// Recupere as leis que receberam pelo menos 3 votos
$stmt = $conn->prepare("SELECT * FROM votacoes_leis WHERE Total_Votos >= 3 AND Arquivado = 'não' AND Promulgado = 'não'");
$stmt->execute();
$result_votacoes = $stmt->get_result();
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
        <div class="formularios">
<!--==============início da seção de abertura presidencial==============-->
    <h1 style="font-size:24px; text-align:center;">Bem vindo a área restrita governamental do gabinete presidencial!</h1>
            <!--alterar mensagem inicial-->
            <div class="container">
            <h1>Pronunciamento a nação minecraftniana.</h1>
                <div class="alterar-presidente">
                <?php
                // Consulta SQL para buscar o texto
                $sql = "SELECT texto FROM mensagem_presidencia";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Saída dos dados de cada linha
                    while($row = $result->fetch_assoc()) {
                        echo "<form method='post' action='pages/update.php'>";
                        echo "<textarea name='texto' rows='4' cols='50'>" . $row["texto"] . "</textarea>";
                        echo "<input type='submit' value='Alterar' class='btn btn-primary'>";
                        echo "</form>";
                    }
                } else {
                    echo "0 resultados";
                }
                
                ?>
                </div><!--alterarpresidente-->
            </div><!--container--> 
            
            <div class="container">
                <h1>Entregar Presidência</h1>
                <form method="post" action="pages/entregar_presidencia.php">
                    <label for="usuario">Caso deseja renunciar da presidencia ou seu mandato terminou, então entregue para outro membro:</label>
                    <select class="form-control bg-light rounded" name="usuario" id="usuario">
                        <option value="" disabled selected>Escolha um membro</option>
                        <?php
                        // Consulta SQL para buscar os usuários
                        $sql = "SELECT id, nome FROM usuarios";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Saída dos dados de cada linha
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Nenhum usuário encontrado</option>";
                        }
                        ?>
                    </select>
                    <br>
                    <button type="submit" class="btn btn-primary">Entregar Presidência</button>
                </form>
                <h5 style="margin-top: 5px; font-size: 10px; text-align: left;">* Cuidado essa ação não pode ser desfeita</h5>
            </div>        
<!--==============fim da seção de abertura presidencial==============-->
<!--==============início da seção dos formulários constitucionais==============-->
            <!--seção de envio de nova lei-->
                <div class="container">
                <h1>Nova Lei Constitucional</h1>
                    <form action="pages/enviar_lei.php" method="post">
                        <div class="mb-3">
                            <label for="textoLei" class="form-label">Texto da Lei:</label>
                            <textarea class="form-control" id="textoLei" name="textoLei" rows="5"></textarea>
                        </div>
                    <button type="submit" class="btn btn-primary">Enviar Ao Plenário</button>
                    </form>
                </div>

            <!--seção de alterar lei existente-->
            <div class="container">
                <h1>Modificar Lei Constitucional Existente</h1>
                <form method="post" action="pages/modificar.php">
                    <label for="lei_existente">Selecione uma lei existente:</label>
                    <select class="form-control bg-light rounded" name="lei_existente" id="lei_existente">
                        <option value="" style="max-width: 250px"; disabled selected>Escolha uma lei</option>
                        <?php while ($row = $result_leis->fetch_assoc()) { ?>
                            <option value="<?php echo $row['ID']; ?>"><?php echo $row['Texto']; ?></option>
                        <?php } ?>
                    </select>
                    <br>
                    <label for="nova_lei">Digite a nova lei:</label>
                    <input class="form-control type="text" name="nova_lei" id="nova_lei">
                    <br>
                    <button type="submit" class="btn btn-primary">Enviar Ao Plenário</button>
                </form>
            </div>
            
            <!--seção das leis votadas no plenário-->
            <div class="container">
                <h1>Leis para Promulgação ou Arquivamento</h1>
                <div class="promulgacao">
                <?php while ($row = $result_votacoes->fetch_assoc()) { ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Lei ID: <?php echo $row['ID']; ?></h5>
                            <p class="card-text">Texto Original: <?php echo $row['Texto_Original']; ?></p>
                            <p class="card-text">Novo Texto: <?php echo $row['Novo_Texto']; ?></p>
                            <p class="card-text">Votos Positivos: <?php echo $row['Votos_Positivos']; ?></p>
                            <p class="card-text">Votos Negativos: <?php echo $row['Votos_Negativos']; ?></p>
                            <?php if ($row['Votos_Positivos'] >= 3) { ?>
                                <button type="button" class="btn btn-success" data-id="<?php echo $row['ID']; ?>">Promulgar</button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-danger" data-id="<?php echo $row['ID']; ?>">Arquivar</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                </div><!--promulgação-->
            </div>
<!--==============fim da seção dos formulários constitucionais==============-->


<!--==============fim da seção dos formulários de leis complementares==============-->
        </div><!--formularios-->
    </div><!--corpo-->


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
        $(".btn-danger").click(function(){
            var lei_id = $(this).data('id');
            $.ajax({
                url: 'pages/arquivar_lei.php',
                type: 'post',
                data: {id: lei_id},
                success: function(response){
                    alert(response);
                }
            });
        });
    });

        $(document).ready(function(){
        $(".btn-success").click(function(){
            var lei_id = $(this).data('id');
            $.ajax({
                url: 'pages/promulgar_lei.php',
                type: 'post',
                data: {id: lei_id},
                success: function(response){
                    alert(response);
                }
            });
        });
    });
    $conn->close();
    </script>
</body>
</html>