<?php
// Verificar se a sessão já está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = "root";
$pass = "";
$host = "localhost";
$db = "lsis1_g04"; // MUDAR PARA O NOME DA BASE DE DADOS
$mysqli = mysqli_connect($host, $user, $pass);
if ($mysqli) {
    mysqli_select_db($mysqli, $db);
} else {
    echo "<script>alert('Erro ao conectar ao banco de dados')</script>";
}
?>
