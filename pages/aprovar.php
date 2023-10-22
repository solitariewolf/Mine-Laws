<?php
session_start();
if (empty($_SESSION)) {
    print "<script>location.href='../index.php'</script>";
}
include('../config.php');

if (isset($_POST['id']) && isset($_SESSION['id'])) {
    $lei_id = $_POST['id'];
    $user_id = $_SESSION['id']; // Você precisa definir isso com a identificação do usuário

    // Verifique se o usuário já votou nesta lei
    $check_query = "SELECT COUNT(*) FROM user_votes WHERE user_id = $user_id AND lei_id = $lei_id";
    $check_result = mysqli_query($conn, $check_query);

    if ($check_result) {
        $row = mysqli_fetch_array($check_result);
        $count = $row[0];

        if ($count == 0) {
            // O usuário não votou nesta lei, pode continuar com a votação
            $query = "UPDATE leis_em_votacao SET votos_positivos = votos_positivos + 1 WHERE id = $lei_id";
            mysqli_query($conn, $query);
            // Registre o voto do usuário na tabela user_votes
            $insert_vote_query = "INSERT INTO user_votes (user_id, lei_id) VALUES ($user_id, $lei_id)";
            mysqli_query($conn, $insert_vote_query);
            echo "Lei aprovada com sucesso!";
        } else {
            echo "Você já votou nesta lei.";
        }
    }
}
?>