<?php
// Inicia a sessão
session_start();

// Verifica se o utilizador está autenticado (exemplo de verificação básica)
if (!isset($_SESSION['email'])) {
    header("Location: Login.php"); // Redirecionar para página de login se não estiver logado
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("ImportSQL.php");

// Verificar se o parâmetro nomeTabelaAtual foi recebido
if (isset($_GET['nomeTabelaAtual'])) {
    // Receber o valor do parâmetro
    $nomeTabelaAtual = $_GET['nomeTabelaAtual'];

    // Substituir espaços por underscores e permitir caracteres acentuados
    $nomeTabelaAtual = str_replace(' ', '_', $nomeTabelaAtual);
    $nomeTabelaAtual = preg_replace('/[^a-zA-Z0-9_çã]/', '', $nomeTabelaAtual);

    // Fetch data from the selected table
    $query = "SELECT * FROM $nomeTabelaAtual";
    $result = $mysqli->query($query);

    // Create CSV file content
    $csv_content = "";

    // Output data
    if ($result->num_rows > 0) {
        // Get column names and add to CSV
        $columns = array_keys($result->fetch_assoc());
        $csv_content .= implode(",", $columns) . "\n";

        // Reset result pointer and fetch rows
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            $csv_content .= implode(",", $row) . "\n";
        }
    }

    // Set headers for CSV download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $nomeTabelaAtual . '.csv"');

    // Output CSV content
    echo $csv_content;

    // Close connection
    $mysqli->close();
}
?>

