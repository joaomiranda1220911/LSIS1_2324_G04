<?php
// Include the database connection configuration file
include("ImportSQL.php");

// Function to reconnect to the database
function reconnect(&$mysqli) {
    mysqli_close($mysqli);
    include("ImportSQL.php");
}

// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome_tabela = mysqli_real_escape_string($mysqli, $_POST['nome_tabela']);
    $tag_tabela = $_POST['tag_tabela'];
    $informacao_tabela = mysqli_real_escape_string($mysqli, $_POST['informacao_tabela']);
    $numero_linhas = mysqli_real_escape_string($mysqli, $_POST['numero_linhas']);

    $tags = implode(", ", $tag_tabela);

    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
        $fileName = basename($_FILES['fileUpload']['name']);
        $fileTmpName = $_FILES['fileUpload']['tmp_name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

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

        $uploadDir = "uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpName, $filePath)) {
            if ($tipoImportacao == 'CSV') {
                if (($handle = fopen($filePath, "r")) !== FALSE) {
                    $header = fgetcsv($handle, 1000, ";");

                    $cleanedHeader = array_map(function($col) use ($mysqli) {
                        return '`' . mysqli_real_escape_string($mysqli, trim($col)) . '`';
                    }, $header);

                    $checkTableSQL = "SHOW TABLES LIKE '$nome_tabela'";
                    $tableExists = mysqli_query($mysqli, $checkTableSQL);

                    if (mysqli_num_rows($tableExists) == 0) {
                        $nomeTabelaFormatado = str_replace(array(" ", "-"), "_", $nome_tabela);
                        $createTableSQL = "CREATE TABLE `$nomeTabelaFormatado` (id INT AUTO_INCREMENT PRIMARY KEY, ";
                        foreach ($cleanedHeader as $column) {
                            $createTableSQL .= "$column VARCHAR(255), ";
                        }
                        $createTableSQL = rtrim($createTableSQL, ", ") . ");";

                        if (!mysqli_query($mysqli, $createTableSQL)) {
                            echo "Error creating table: " . mysqli_error($mysqli);
                            exit();
                        }
                    }

                    $insertSQL = "INSERT INTO `$nomeTabelaFormatado` (" . implode(", ", $cleanedHeader) . ") VALUES ";
                    $rowCount = 0;
                    $limit = 10000; // Limita a importação para 10,000 linhas
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE && $rowCount < $limit) {
                        $values = array_map(function($value) use ($mysqli) {
                            return "'" . mysqli_real_escape_string($mysqli, $value) . "'";
                        }, $data);

                        $insertSQL .= "(" . implode(", ", $values) . "), ";
                        $rowCount++;

                        if ($rowCount % 100 == 0) {
                            $insertSQL = rtrim($insertSQL, ", ") . ";";

                            if (!mysqli_query($mysqli, $insertSQL)) {
                                echo "Error inserting CSV data into table: " . mysqli_error($mysqli);
                                exit();
                            }
                            $insertSQL = "INSERT INTO `$nomeTabelaFormatado` (" . implode(", ", $cleanedHeader) . ") VALUES ";
                        }
                    }
                    if ($rowCount % 100 != 0) {
                        $insertSQL = rtrim($insertSQL, ", ") . ";";
                        if (!mysqli_query($mysqli, $insertSQL)) {
                            echo "Error inserting CSV data into table: " . mysqli_error($mysqli);
                            exit();
                        }
                    }
                    fclose($handle);

                    $sql = "INSERT INTO dataset (nomeTabela, tags, numeroLinhas, tipoImportacao, idDashboard, informacao, linkAPI)
                            VALUES ('$nome_tabela', '$tags', '$rowCount', '$tipoImportacao', 0, '$informacao_tabela', '$filePath')";

                    reconnect($mysqli);

                    if (mysqli_query($mysqli, $sql)) {
                        header("Location: home_dados.php");
                    } else {
                        echo "Error inserting metadata: " . mysqli_error($mysqli);
                    }
                } else {
                    echo "Error opening CSV file.";
                }
            } else {
                $sql = "INSERT INTO dataset (nomeTabela, tags, numeroLinhas, tipoImportacao, idDashboard, informacao, linkAPI)
                        VALUES ('$nome_tabela', '$tags', '$numero_linhas', '$tipoImportacao', 0, '$informacao_tabela', '$filePath')";

                if (mysqli_query($mysqli, $sql)) {
                    header("Location: home_dados.php");
                    exit();
                } else {
                    echo "Error inserting metadata: " . mysqli_error($mysqli);
                }
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Please select a file to upload.";
    }
}

mysqli_close($mysqli);
?>

