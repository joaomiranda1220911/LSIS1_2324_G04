<?php
// Verificar se a sessão já está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir o arquivo ImportSQL.php
require_once 'ImportSQL.php';

// Verificar se o usuário está logado e tem permissão adequada
if (!isset($_SESSION['username']) || !in_array($_SESSION['permissao'], ['Admin', 'Colaborador E-Redes'])) {
    echo "Por favor, inicie sessão.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Importar Arquivo</title>
</head>
<body>
    <h2>Importar Arquivo</h2>
    <form method="post" enctype="multipart/form-data" action="ImportSQL.php">
        <label for="file">Escolha um arquivo CSV ou Excel:</label>
        <input type="file" id="file" name="file" required>
        <br>
        <button type="submit">Importar</button>
    </form>
</body>
</html>
