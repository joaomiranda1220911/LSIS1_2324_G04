<?php
// Verificar se a sessão já está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = "root";
$pass = "";
$host = "localhost";
$db = "lsis1_g04"; 
$mysqli = mysqli_connect($host, $user, $pass);

if ($mysqli) {
    mysqli_select_db($mysqli, $db);
    echo "<script>alert('Conexão ao banco de dados estabelecida com sucesso')</script>";
} else {
    echo "<script>alert('Erro ao conectar ao banco de dados')</script>";
}
?>

