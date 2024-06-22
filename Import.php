<?php
// Verificar se a sessão já está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir o arquivo de conexão com a base de dados (ImportSQL.php)
require_once 'ImportSQL.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: Login.php"); // Redirecionar para página de login se não estiver logado
    exit;
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processar os dados do formulário
    $nome_tabela = $_POST['nome_tabela'];

    // Configurações para limitar o acesso à página apenas a administradores e colaboradores E-Redes
    $permissoes_permitidas = ['Admin', 'Colaborador E-Redes'];
    if (!isset($_SESSION['permissao']) || !in_array($_SESSION['permissao'], $permissoes_permitidas)) {
        echo "Permissão insuficiente para realizar esta operação.";
        exit;
    }

    // Verificar se foi enviado um arquivo
    if (isset($_FILES['fileUpload'])) {
        $file_name = $_FILES['fileUpload']['name'];
        $file_tmp = $_FILES['fileUpload']['tmp_name'];

        // Mover o arquivo para o diretório desejado
        $upload_path = 'caminho/para/o/seu/diretorio/' . $file_name;
        move_uploaded_file($file_tmp, $upload_path);

        // Abrir e ler o conteúdo do arquivo CSV
        $handle = fopen($upload_path, "r");
        if ($handle !== FALSE) {
            // Preparar a query de inserção
            $sql = "INSERT INTO $nome_tabela (coluna1, coluna2, coluna3, ...) VALUES (?, ?, ?, ...)";
            $stmt = $mysqli->prepare($sql);

            // Ler cada linha do CSV e inserir na base de dados
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Ajustar para o número correto de colunas e tipos de dados
                $coluna1 = $data[0];
                $coluna2 = $data[1];
                $coluna3 = $data[2];
                // ... continuar para todas as colunas necessárias

                // Bind dos parâmetros e execução da query
                $stmt->bind_param("sss...", $coluna1, $coluna2, $coluna3, ...);
                $stmt->execute();
            }

            fclose($handle);
            $stmt->close();

            // Após a inserção dos dados, redirecionar para a página desejada
            header("Location: index.php"); // Redirecionar para a página inicial
            exit;
        } else {
            echo "Erro ao abrir o arquivo.";
        }
    } else {
        echo "Por favor, selecione um arquivo CSV para importar.";
    }
}
?>

