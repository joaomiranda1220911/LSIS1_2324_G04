<?php
// Incluir o arquivo de configuração da conexão com o banco de dados
include ("ImportSQL.php");

// Verificar se a sessão já está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os dados do formulário
    $nome_tabela = mysqli_real_escape_string($mysqli, $_POST['nome_tabela']);
    $tag_tabela = $_POST['tag_tabela'];
    $informacao_tabela = mysqli_real_escape_string($mysqli, $_POST['informacao_tabela']);
    $numero_linhas = mysqli_real_escape_string($mysqli, $_POST['numero_linhas']);

    // Processar as tags da tabela
    $tags = implode(", ", $tag_tabela);

    // Verificar e processar o upload do ficheiro
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
        $fileName = basename($_FILES['fileUpload']['name']);
        $fileTmpName = $_FILES['fileUpload']['tmp_name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Determinar o tipo de importação com base na extensão do arquivo
        $tipoImportacao = '';
        switch ($fileExtension) {
            case 'csv':
                $tipoImportacao = 'CSV';
                break;
            case 'xlsx':
            case 'xls':
                $tipoImportacao = 'Excel';
                break;
            default:
                $tipoImportacao = 'Upload';
        }

        // Definir o diretório de upload
        $uploadDir = "uploads/";

        // Criar o diretório de upload se não existir
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . $fileName;

        // Mover o ficheiro para o diretório de uploads
        if (move_uploaded_file($fileTmpName, $filePath)) {
            // Inserir os dados na base de dados
            $sql = "INSERT INTO dataset (nomeTabela, tags, numeroLinhas, tipoImportacao, idDashboard, informacao, linkAPI)
                    VALUES ('$nome_tabela', '$tags', '$numero_linhas', '$tipoImportacao', 0, '$informacao_tabela', '$filePath')";

            if (mysqli_query($mysqli, $sql)) {
                // Redirecionar para a página home_dados.php após a inserção bem-sucedida
                header("Location: home_dados.php");
                exit(); // Certifique-se de que o script pare a execução após o redirecionamento
            } else {
                echo "Erro ao importar dados: " . mysqli_error($mysqli);
            }
        } else {
            echo "Erro ao fazer upload do ficheiro.";
        }
    } else {
        echo "Por favor, selecione um ficheiro para fazer upload.";
    }
}

// Fechar a conexão com o banco de dados
mysqli_close($mysqli);
?>