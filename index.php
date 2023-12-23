<!DOCTYPE html>
<?php
session_start();

$_SESSION["num1"] = rand(1, 9); // Gera um número aleatório entre 1 e 9
$_SESSION["num2"] = rand(1, 9); // Gera um número aleatório entre 1 e 9
$_SESSION["captcha"] = $_SESSION["num1"] + $_SESSION["num2"];
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Mine Laws MOD 1.0</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
</head>
<body>

    <div class="container-login">
        <div class="container-form">
            <h3>Área De Membros</h3>
            <img src="img/logo.png" alt="">
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label>Usuário:</label>
                    <input type="text" name="usuario" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Senha:</label>
                    <input type="password" name="senha" class="form-control">
                </div>
                <div class="captcha">
                    <label>CAPTCHA: Quanto é <?php echo $_SESSION["num1"]; ?> + <?php echo $_SESSION["num2"]; ?>?</label>
                    <input type="text" name="captcha" class="form-control">
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
            <p class="copy">©Todos os direitos reservados a <a class="copy" href="https://github.com/solitariewolf/" target="_blank">@Solitariewolf</a></p>
        </div>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>
<!--Developer @Solitariewolf - Github-->
