<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
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
    <div class="container">
        <div class="alterar-form">
        <span><h3>Meu perfil</h3><h5>Alterar dados</h5></span>
            <form class="form-alterar" method="post" action="pages/alterar.php">
                <label for="usuario">Alterar usuário:</label><br>
                <input type="text" id="usuario" name="usuario"><br>
                <label for="senha">Alterar Senha:</label><br>
                <input type="password" id="senha" name="senha"><br>
                <input type="submit" value="Alterar">
            </form>
        </div>
    </div>
</body>
</html>