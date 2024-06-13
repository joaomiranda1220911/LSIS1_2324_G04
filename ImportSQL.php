<?php
// Verificar se a sessão já está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = "root";
$pass = "";
$host = "localhost";
$db = "lsis1_g04"; // Nome da sua base de dados

// Conectar ao MySQL
$mysqli = new mysqli($host, $user, $pass, $db);

// Verificar conexão
if ($mysqli->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $mysqli->connect_error);
}

// Definir o charset para UTF-8 (opcional)
if (!$mysqli->set_charset("utf8")) {
    echo "Erro ao definir o charset para UTF-8: " . $mysqli->error;
}

?>
