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

    <div class="tab" onload="openCity('Tab1')">
        <button class="tablinks" onclick="openCity(event, 'Tab1', 'pages/mensagempresid.php')">Ínicio</button>
        <button class="tablinks" onclick="openCity(event, 'Tab1', 'loja/loja.php')">Loja</button>
        <button class="tablinks" onclick="openCity(event, 'Tab1', 'banco/banco.php')">Banco</button>
    </div><!--tab-->
        <div id="Tab1" class="tabcontent">
        </div><!--tabcontent-->

        <div id="Tab2" class="tabcontent">
        </div><!--tabcontent-->

        <div id="Tab3" class="tabcontent">
        </div><!--tabcontent-->

</div><!--corpo-->
            <script>
                function openCity(evt, cityName, url) {
                    var i, tabcontent, tablinks;
                    tabcontent = document.getElementsByClassName("tabcontent");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablinks");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }

                    // Cria um novo objeto XMLHttpRequest
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // Quando a resposta do servidor estiver pronta, altere o conteúdo da div
                            document.getElementById(cityName).innerHTML = this.responseText;
                            document.getElementById(cityName).style.display = "block";
                        }
                    };
                    // Envia a solicitação ao servidor
                    xhttp.open("GET", url, true);
                    xhttp.send();

                    evt.currentTarget.className += " active";
                }
            </script>
</body>
</html>