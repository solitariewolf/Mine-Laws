<?php
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

// Consulta SQL para obter os decretos
$sql = "SELECT ID, Texto, Votos_Derrubar FROM decretos";
$resultdec = $conn->query($sql);

// Recupere as honrarias que receberam pelo menos 3 votos
$stmt = $conn->prepare("SELECT votacoes_medalhas.id, usuarios.Nome AS Usuario, medalhas.nome AS Medalha, Votos_Positivos, Votos_Negativos, Total_Votos 
FROM votacoes_medalhas 
JOIN usuarios ON votacoes_medalhas.Usuario = usuarios.id
JOIN medalhas ON votacoes_medalhas.Medalha = medalhas.id
WHERE Total_Votos >= 2 AND Arquivado = 'não' AND Promulgado = 'não'");
$stmt->execute();
$result_votacoes_medalhas = $stmt->get_result();

// Consulta SQL para obter os itens
$sql = "SELECT ID, nome, qtd, valor FROM itens";
$resultitens = $conn->query($sql);

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

    <div class="brasao-gabinete">
        <h1 style="font-size:24px; text-align:center; color: white">Bem vindo a área restrita governamental do gabinete presidencial!</h1>
        <img src="img/brasao.png" alt="">
    </div>

<div class="tab" onload="openCity('Tab1')">
  <button class="tablinks" onclick="openCity(event, 'Tab1')">Leis e Decretos</button>
  <button class="tablinks" onclick="openCity(event, 'Tab2')">Medalhas</button>
  <button class="tablinks" onclick="openCity(event, 'Tab3')">Loja</button>
  <button class="tablinks" onclick="openCity(event, 'Tab4')">Recursos</button>
</div>

<div id="Tab1" class="tabcontent">
        <div class="formularios">
<!--==============início da seção de abertura presidencial==============-->
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

    <h4>Medalhas - São honrarias dadas aos jogadores pelo presidente com aprovação dos membros fundadores</h4>
    <div class="medalhas-plenario-geral">
        <div class="medalhas-plenario">
            <h2>Enviar Honraria Para o Plenário</h2>

            <form action="pages/enviar_medalha.php" method="post">
            <label for="usuario">Jogador:</label><br>
            <select id="usuario" name="usuario" class="form-control bg-light rounded">
                <?php
                $usuarios3 = $conn->query("SELECT * FROM usuarios");
                foreach ($usuarios3 as $usuario) {
                    echo "<option value=\"" . $usuario['id'] . "\">" . $usuario['nome'] . "</option>";
                }
                ?>
            </select><br>
            <label for="medalha">Medalha:</label><br>
            <select id="medalha" name="medalha" class="form-control bg-light rounded">
                <?php
                $medalhas = $conn->query("SELECT * FROM medalhas");
                foreach ($medalhas as $medalha) {
                    echo "<option value=\"" . $medalha['id'] . "\">" . $medalha['nome'] . "</option>";
                }
                ?>
            </select><br>
            <input class="btn btn-danger" type="submit" value="Enviar a Plenário">
            </form>
        </div><!--medalhas-plenario-->

        <div class="medalhas-plenario">
                <h2>Medalhas já votadas</h2>
                <div class="promulgacao">
                <?php while ($row = $result_votacoes_medalhas->fetch_assoc()) { ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Votação N° <?php echo $row['id']; ?></h5>
                            <p class="card-text">Usuário: <?php echo $row['Usuario']; ?></p>
                            <p class="card-text">Medalha: <?php echo $row['Medalha']; ?></p>
                            <?php if ($row['Votos_Positivos'] >= 2) { ?>
                                <button type="button" class="btn-success3" data-id="<?php echo $row['id']; ?>">Promulgar</button>
                            <?php } else { ?>
                                <button type="button" class="btn-danger3" data-id="<?php echo $row['id']; ?>">Arquivar</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                </div><!--promulgação-->
        </div><!--medalhas-plenario-->
    </div><!--medalhas-plenario-geral-->

        <div class="tabela-medalhas">
            <div class="corpo-medalhas">
                <h4>Medalha Presidenciável</h4>
                <img src="img/medalha-presi.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que já foi presidente.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Fundador</h4>
                <img src="img/medalha-fundador.png" alt="text">
                <h2>Medalha fornecida aos membros fundadores.</h2>
            </div>
            
            <div class="corpo-medalhas">
                <h4>Medalha Do Sem Vida</h4>
                <img src="img/medalha-semvida.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que mais passa tempo jogando.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Cruz De Ferro</h4>
                <img src="img/medalha-cruzferro.jpg" alt="text">
                <h2>Medalha fornecida ao jogador veterano.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Marinheiro</h4>
                <img src="img/medalha-agua.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que gosta de explorar os oceanos.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do General</h4>
                <img src="img/medalha-exercito.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que tem um exército forte.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Da Honra</h4>
                <img src="img/medalha-honraria.jpg" alt="text">
                <h2>Medalha fornecida ao jogador em agradecimento a seus serviços prestados.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Bondoso</h4>
                <img src="img/medalha-ajudante.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que ajuda os outros sem nada em troca.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Ás Dos Céus</h4>
                <img src="img/medalha-asdoceu.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que domina os céus.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Cientista</h4>
                <img src="img/medalha-cientista.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que desenvolve novas ideias.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Construtor</h4>
                <img src="img/medalha-construtor.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que se destaca por ter muitas construções.</h2>
            </div>

            <div class="corpo-medalhas">
                <h4>Medalha Do Minerador</h4>
                <img src="img/medalha-minerador.jpg" alt="text">
                <h2>Medalha fornecida ao jogador que se destaca na mineração.</h2>
            </div>

            
        </div><!--tabela medalhas-->
    </div><!--tabcontent - conteudo jogadores-->

    <div id="Tab3" class="tabcontent">

        <div class="display-grid">
            <div class="form-gabinete-geral-loja">
                <form id="form-enviar-loja" action="pages/upload.php" method="post" enctype="multipart/form-data">
                    <label for="nome">Nome:</label><br>
                    <input type="text" id="nome" name="nome"><br>
                    <label for="valor">Valor em MC$:</label><br>
                    <input type="number" id="valor" name="valor" step="0.01" min="0"><br>
                    <label for="qtd">Quantidade:</label><br>
                    <input type="number" id="qtd" name="qtd"><br>
                    <label for="img">Selecione a imagem:</label><br>
                    <input type="file" id="img" name="img" accept="image/*"><br>
                    <input type="submit" value="Cadastrar Item">
                </form>
            </div><!--form-gabinete-geral-loja-->

            <div class="form-gabinete-geral-loja">
                <form method="post" action="pages/updtitem.php">
                    <label for="item_existente">Selecione um item</label>
                    <select class="form-control bg-light rounded" name="item_existente" id="item_existente">
                        <option value="" style="max-width: 250px"; disabled selected>Escolha uma item</option>
                        <?php while ($row = $resultitens->fetch_assoc()) { ?>
                            <option value="<?php echo $row['ID']; ?>"><?php echo $row['nome']; ?></option>
                        <?php } ?>
                    </select>
                    <br>
                    <label for="nova_item">Alterar Nome:</label>
                    <input class="form-control" type="text" name="nova_item" id="nova_item">
                    <br>
                    <label for="nova_qtd">Alterar Quantidade:</label>
                    <input class="form-control" type="number" name="nova_qtd" id="nova_qtd">
                    <br>
                    <label for="novo_valor">Alterar Valor:</label>
                    <input class="form-control" type="number" name="novo_valor" id="novo_valor" step="0.01" min="0">
                    <br>
                    <button type="submit" class="btn btn-primary">Atualizar item</button>
                </form>
            </div>
        </div><!--display-grid-->
        <h3>Itens cadastrados a venda</h3>
        <div class="gabinete-geral-loja">
            <?php   
                $sql = "SELECT nome, img, qtd, valor FROM itens";
                $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Saída dos dados de cada linha
                while($row = $result->fetch_assoc()) {
                    echo "<div class='item'>";
                    echo "<h2>" . $row["nome"] . "</h2>";
                    echo "<img src='" . $row["img"] . "' alt='" . $row["nome"] . "'>";
                    echo "<p>Quantidade: " . $row["qtd"] . "</p>";
                    echo "<p>Valor MC$: " . $row["valor"] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "Nenhum item encontrado.";
            }
            ?>
        </div><!--gabinete-geral-loja-->
    </div><!--tab3-->

    <div id="Tab4" class="tabcontent">
            <div class="logo-banco"> <img src="img/banco/bank-logo.png" alt=""></div>

<?php
// Pega o ID do usuário da sessão
$id_usuario = 1;

// Prepara a consulta SQL
$consulta = $conn->prepare("SELECT u.nome, b.money FROM usuarios u INNER JOIN banco b ON u.id = b.usuario WHERE u.id = ?");
$consulta->bind_param('i', $id_usuario);

// Executa a consulta
$consulta->execute();

// Fetch the result
$resultado = $consulta->get_result()->fetch_assoc();

// Formata o valor do dinheiro com pontos como separadores de milhares
$money_formatado = number_format($resultado['money'], 2, ',', '.');

// Exibe a mensagem
echo "<p class='saldo-inicio'>Olá " . $resultado['nome'] . ", seu saldo é de MC$: " . $money_formatado . "</p>";
?>
<div class="duas-primeiras">

<div class="form-transferir">
<h1>Relizar transferência entre contas</h1>
<form action="banco/transferir-governo.php" method="post">
<label for="usuario">Usuário:</label><br>
<select class="form-control bg-light rounded" name="usuario" id="usuario">
    <option value="" disabled selected>Escolha um membro</option>
    <?php
        $sql = "SELECT id, nome FROM usuarios WHERE id != 1";
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

</select><br>
<label for="valor">Valor:</label><br>
<input type="number" id="valor" name="valor" min="0" step="0.01"><br>
<label for="mensagem">Mensagem:</label><br>
<textarea id="mensagem" name="mensagem"></textarea><br>
<input type="submit" value="Transferir">
</form>
</div><!--form-transferir-->

<div class="form-extrato">
<h1>Extrato De Transações</h1>
    <?php
// Consulta SQL para buscar as transações
$sql = "
SELECT 
    c.nome AS nome_credito, 
    d.nome AS nome_debito, 
    e.valor, 
    e.mensagem 
FROM banco_extrato e
INNER JOIN usuarios c ON e.user_c = c.id
INNER JOIN usuarios d ON e.user_d = d.id
WHERE e.user_c = ? OR e.user_d = ?
ORDER BY e.id DESC -- Adicione esta linha para ordenar por data em ordem decrescente
LIMIT 10 -- Adicione esta linha para limitar o resultado a 10 registros
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id, $id);
$id = 1;
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Crédito</th><th>Débito</th><th>Valor</th><th>Mensagem</th></tr>";
    // Saída dos dados de cada linha
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['nome_credito'] . "</td><td>" . $row['nome_debito'] . "</td><td>" . $row['valor'] . "</td><td>" . $row['mensagem'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Nenhuma transação encontrada";
}
?>

</div><!--form-transferir-->
</div><!--duas primeiras-->

    </div><!--tab4-->


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <script>//leis complementares

    </script>

<script>//medalhas
    $(document).ready(function(){
        $(".btn-danger3").click(function(){
            var lei_id = $(this).data('id');
            $.ajax({
                url: 'pages/arquivar_medalha.php',
                type: 'post',
                data: {id: lei_id},
                success: function(response){
                    alert(response);
                }
            });
        });
    });

        $(document).ready(function(){
        $(".btn-success3").click(function(){
            var lei_id = $(this).data('id');
            $.ajax({
                url: 'pages/promulgar_medalha.php',
                type: 'post',
                data: {id: lei_id},
                success: function(response){
                    alert(response);
                }
            });
        });
    });

    //função das abas
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
            //buscar itens
                document.getElementById('item_existente').addEventListener('change', function() {
                    var id = this.value;

                    // Cria um novo objeto XMLHttpRequest
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // Quando a resposta do servidor estiver pronta, preencha os campos do formulário
                            var item = JSON.parse(this.responseText);
                            document.getElementById("nova_item").value = item.nome;
                            document.getElementById("novo_valor").value = item.valor;
                            document.getElementById("nova_qtd").value = item.qtd;
                        }
                    };
                    // Envia a solicitação ao servidor
                    xhttp.open("GET", "pages/getItem.php?id=" + id, true);
                    xhttp.send();
                });
    </script>
    
</body>
</html>