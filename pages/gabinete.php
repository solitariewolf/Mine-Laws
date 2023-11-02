<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');
// Consulta SQL para buscar o tipo do usuário
if ($_SESSION['tipo'] != '2') {
    // Se o tipo de usuário não for 2, redirecionar para
    print "<script>location.href='pages/nopermission.php'</script>";
}


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

// Consulta SQL para obter as leis da tabela "leis complementares"
$sql = "SELECT ID, Texto FROM complementar";
$result_leis2 = $conn->query($sql);

// Recupere as leis que receberam pelo menos 3 votos
$stmt = $conn->prepare("SELECT * FROM votacoes_leis_complementares WHERE Total_Votos >= 2 AND Arquivado = 'não' AND Promulgado = 'não'");
$stmt->execute();
$result_votacoes2 = $stmt->get_result();

// Consulta SQL para obter os decretos
$sql = "SELECT ID, Texto, Votos_Derrubar FROM decretos";
$resultdec = $conn->query($sql);
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
<body onload="openCity('Tab1')">
    <div class="corpo">

    <h2>Tabs Example</h2>

<div class="tab" onload="openCity('Tab1')">
  <button class="tablinks" onclick="openCity(event, 'Tab1')">Leis e Decretos</button>
  <button class="tablinks" onclick="openCity(event, 'Tab2')">Medalhas</button>
</div>

<div id="Tab1" class="tabcontent">
        <div class="formularios">
<!--==============início da seção de abertura presidencial==============-->
<div class="brasao-gabinete">
<h1 style="font-size:24px; text-align:center;">Bem vindo a área restrita governamental do gabinete presidencial!</h1>
<img src="img/brasao.png" alt="">
</div>
    <div style="border: 3px solid #ccc; margin-top: 15px">
        <h3 style="margin-top: 10px; color: #3277a8">Ações Presidencias Diretas &#128204</h3>
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
                    echo "<textarea name='texto' rows='5' cols='70'>" . $row["texto"] . "</textarea>";
                    echo "<input type='submit' value='Alterar Pronunciamento' class='btn btn-primary'>";
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
                <button type="submit" class="btn btn-danger">Entregar Presidência</button>
            </form>
            <h5 style="margin-top: 5px; font-size: 10px; text-align: left;">* Cuidado essa ação não pode ser desfeita</h5>
        </div>  
</div>      
<!--==============fim da seção de abertura presidencial==============-->
<!--==============início da seção dos formulários constitucionais==============-->
        <div style="border: 3px solid #ccc; margin-top: 15px">
            <h3 style="margin-top: 10px; color: #a84a32">Constituição &#128211</h3>
            <!--seção de envio de nova lei-->
                <div class="container">
                <h1>Nova Lei Constitucional</h1>
                    <form action="pages/enviar_lei.php" method="post">
                        <div class="mb-3">
                            <label for="textoLei" class="form-label">Caso deseje fazer uma nova lei insira o texto abaixo, para que uma nova lei constitucional entre em vigor é necessário 3 votos positivos dos membros fundadores:</label>
                            <textarea class="form-control" id="textoLei" name="textoLei" rows="5"></textarea>
                        </div>
                    <button type="submit" class="btn btn-primary">Enviar Ao Plenário</button>
                    </form>
                </div>

            <!--seção de alterar lei existente-->
            <div class="container">
                <h1>Modificar Lei Constitucional Existente</h1>
                <form method="post" action="pages/modificar.php">
                    <label for="lei_existente">Selecione uma lei existente para alterar, para modificar lei existente na constituição são necessários 3 votos positivos dos membros fundadores:</label>
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
        </div>
<!--==============fim da seção dos formulários constitucionais==============-->
<!--==============Início da seção dos formulários de leis complementares==============-->
        <div style="border: 3px solid #ccc; margin-top: 15px">
            <!--seção de envio de nova lei complementar-->
            <h3 style="margin-top: 10px; color: #a89d32">Leis complementares &#128216</h3>
            <div class="container">
                <h1>Nova Lei Complementar</h1>
                    <form action="pages/enviar_lei_complementar.php" method="post">
                        <div class="mb-3">
                            <label for="textoLei" class="form-label">Caso deseje fazer uma nova lei complementar insira o texto abaixo, para que uma nova lei complementar entre em vigor é necessário 2 votos positivos dos membros fundadores:</label>
                            <textarea class="form-control" id="textoLei" name="textoLei" rows="5"></textarea>
                        </div>
                    <button type="submit" class="btn btn-primary">Enviar Ao Plenário</button>
                    </form>
                </div>


                        <!--seção de alterar lei existente-->
            <div class="container">
                <h1>Modificar Lei Complementar Existente</h1>
                <form method="post" action="pages/modificar_complementar.php">
                    <label for="lei_existente">Selecione uma lei existente para alterar, para modificar lei complementar existente são necessários 2 votos positivos dos membros fundadores:</label>
                    <select class="form-control bg-light rounded" name="lei_existente" id="lei_existente">
                        <option value="" style="max-width: 250px"; disabled selected>Escolha uma lei</option>
                        <?php while ($row = $result_leis2->fetch_assoc()) { ?>
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
                <?php while ($row = $result_votacoes2->fetch_assoc()) { ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Lei ID: <?php echo $row['ID']; ?></h5>
                            <p class="card-text">Texto Original: <?php echo $row['Texto_Original']; ?></p>
                            <p class="card-text">Novo Texto: <?php echo $row['Novo_Texto']; ?></p>
                            <p class="card-text">Votos Positivos: <?php echo $row['Votos_Positivos']; ?></p>
                            <p class="card-text">Votos Negativos: <?php echo $row['Votos_Negativos']; ?></p>
                            <?php if ($row['Votos_Positivos'] >= 2) { ?>
                                <button type="button" class="btn-success2" data-id="<?php echo $row['ID']; ?>">Promulgar</button>
                            <?php } else { ?>
                                <button type="button" class="btn-danger2" data-id="<?php echo $row['ID']; ?>">Arquivar</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                </div><!--promulgação-->
            </div>
        </div>
<!--==============fim da seção dos formulários de leis complementares==============-->
<!--==============Início da seção dos formulários de Decretos==============-->
    <!--seção sobre decretos-->
<div style="border: 3px solid #ccc; margin-top: 15px; margin-bottom: 15px">
<h3 style="margin-top: 10px; color: #128221">Decretos Presidenciais &#128220</h3>
    <div class="container">
        <p>O decreto presidencial é o maior poder do presidente, decretos tem validade imediata como lei, mas podem ser derrubados se dois dos membros fundadores decidirem derrubar o decreto, observe que o decreto não pode passar por cima das leis complementares nem da constituição.</p>
        <form method="post" action="pages/inserir_decreto.php">
            <label for="texto_decreto">Digite o texto do decreto:</label>
            <textarea class="form-control" name="texto_decreto" id="texto_decreto"></textarea>
            <br>
            <button type="submit" class="btn btn-primary">Inserir Decreto</button>
        </form>
    </div><!--container-->
<div class="container">
    <h1>Decretos em Vigor</h1>
    <p>Lista de decretos em vigor ou suspenso, observe que ao apertar em suspender o decreto será suspenso imediatamente de modo irreversível.</p>
    <div class="decretos">
        <table class="table">
            <thead>
                <tr>
                    <th>Número Decreto</th>
                    <th>Texto Original</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultdec->fetch_assoc()) { ?>
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
                        <td>
                        <form method="post" action="pages/suspender_decreto.php">
                            <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">
                            <button type="submit" name="voto" value="Positivo" class="btn btn-danger">Suspender Decreto</button>
                        </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div><!--decretos-->

        
    </div>

</div><!--conteudo leis e decretos-->
<!--==============fim da seção dos formulários de Decretos==============-->
        </div><!--formularios-->
    </div><!--corpo-->

    <div id="Tab2" class="tabcontent">
        <h3>Tab 2</h3>
        <p>Content for Tab 2.</p>
    </div><!--conteudo jogadores-->


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
    </script>

    <script>
    $(document).ready(function(){
        $(".btn-danger2").click(function(){
            var lei_id = $(this).data('id');
            $.ajax({
                url: 'pages/arquivar_lei_complementar.php',
                type: 'post',
                data: {id: lei_id},
                success: function(response){
                    alert(response);
                }
            });
        });
    });

        $(document).ready(function(){
        $(".btn-success2").click(function(){
            var lei_id = $(this).data('id');
            $.ajax({
                url: 'pages/promulgar_lei_complementar.php',
                type: 'post',
                data: {id: lei_id},
                success: function(response){
                    alert(response);
                }
            });
        });
    });
    </script>

    <script>
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    </script>
    
</body>
</html>
