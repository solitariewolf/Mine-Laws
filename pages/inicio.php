<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');
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
        <div class="brasao">
            <img src="./img/brasao.png" alt="">
        </div>
        <div class="presidencia">
            <?php
                // Consulta SQL para buscar o texto
        $sql = "SELECT texto FROM mensagem_presidencia";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Saída dos dados de cada linha
            while($row = $result->fetch_assoc()) {
                echo $row["texto"];
            }
        } else {
            echo "0 resultados";
        }
        $conn->close();
        ?>
        </div>

    </div><!--corpo-->
</body>
</html>