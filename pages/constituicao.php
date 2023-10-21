<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
?>

<?php
include_once '../config.php';

$query = "SELECT l.numero_lei, l.lei, i.numero_inciso, i.inciso FROM leis l
LEFT JOIN incisos i on l.id = i.lei_id ORDER BY l.numero_lei, i.numero_inciso";
$result = mysqli_query($conn, $query);

$previous_lei = "";
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

    <div class="container-const">
    <h2 class="title">Constituição Mine Laws - MOD</h2>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['lei'] !== $previous_lei) {
                // Nova lei encontrada, exibe título da lei
                echo "<h2>Lei " . $row['numero_lei'] . ": " . $row['lei'] . "</h2>";
                $previous_lei = $row['lei'];
            }
            // Exibe inciso
            echo "<p>Inciso " . $row['numero_inciso'] . ": " . $row['inciso'] . "</p>";
        }
        ?>
    </div>

</body>
</html>
