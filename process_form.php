<?php
// Include the database connection configuration file
include ("ImportSQL.php");

// Function to reconnect to the database
function reconnect(&$mysqli) {
    mysqli_close($mysqli);
    include ("ImportSQL.php");
}

// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $nome_tabela = mysqli_real_escape_string($mysqli, $_POST['nome_tabela']);
    $tag_tabela = $_POST['tag_tabela'];
    $informacao_tabela = mysqli_real_escape_string($mysqli, $_POST['informacao_tabela']);
    $numero_linhas = mysqli_real_escape_string($mysqli, $_POST['numero_linhas']);

    // Process table tags
    $tags = implode(", ", $tag_tabela);

    // Check and process the file upload
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
        $fileName = basename($_FILES['fileUpload']['name']);
        $fileTmpName = $_FILES['fileUpload']['tmp_name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Determine import type based on file extension
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

        // Define the upload directory
        $uploadDir = "uploads/";

        // Create the upload directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . $fileName;

        // Move the file to the upload directory
        if (move_uploaded_file($fileTmpName, $filePath)) {
            // Process the CSV file to create or update the table
            if ($tipoImportacao == 'CSV') {
                // Read the CSV file content
                if (($handle = fopen($filePath, "r")) !== FALSE) {
                    // Get the first line as column names
                    $header = fgetcsv($handle, 1000, ";"); // Use ; as delimiter

                    // Clean column names to avoid syntax issues
                    $cleanedHeader = array_map(function($col) use ($mysqli) {
                        return '`' . mysqli_real_escape_string($mysqli, trim($col)) . '`';
                    }, $header);

                    // Check if the table already exists
                    $checkTableSQL = "SHOW TABLES LIKE '$nome_tabela'";
                    $tableExists = mysqli_query($mysqli, $checkTableSQL);

                    if (mysqli_num_rows($tableExists) == 0) {
                        // Build the CREATE TABLE SQL query if the table does not exist
                        $createTableSQL = "CREATE TABLE `$nome_tabela` (id INT AUTO_INCREMENT PRIMARY KEY, ";
                        foreach ($cleanedHeader as $column) {
                            $createTableSQL .= "$column VARCHAR(255), ";
                        }
                        $createTableSQL = rtrim($createTableSQL, ", ") . ");";

                        // Execute the query to create the table
                        if (!mysqli_query($mysqli, $createTableSQL)) {
                            echo "Error creating table: " . mysqli_error($mysqli);
                            exit();
                        }
                    }

                    // Insert CSV data into the table (new or existing) in chunks
                    $insertSQL = "INSERT INTO `$nome_tabela` (" . implode(", ", $cleanedHeader) . ") VALUES ";
                    $rowCount = 0;
                    $limit = 10000; // Limit the import to 10,000 rows
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE && $rowCount < $limit) {
                        $values = array_map(function($value) use ($mysqli) {
                            return "'" . mysqli_real_escape_string($mysqli, $value) . "'";
                        }, $data);
                        $insertSQL .= "(" . implode(", ", $values) . "), ";
                        $rowCount++;

                        // Execute query every 100 rows to avoid long-running queries
                        if ($rowCount % 100 == 0) {
                            $insertSQL = rtrim($insertSQL, ", ") . ";";

                            if (!mysqli_query($mysqli, $insertSQL)) {
                                echo "Error inserting CSV data into table: " . mysqli_error($mysqli);
                                exit();
                            }
                            $insertSQL = "INSERT INTO `$nome_tabela` (" . implode(", ", $cleanedHeader) . ") VALUES ";
                        }
                    }
                    // Execute remaining rows
                    if ($rowCount % 100 != 0) {
                        $insertSQL = rtrim($insertSQL, ", ") . ";";
                        if (!mysqli_query($mysqli, $insertSQL)) {
                            echo "Error inserting CSV data into table: " . mysqli_error($mysqli);
                            exit();
                        }
                    }
                    fclose($handle);

                    // Insert metadata into the dataset table
                    $sql = "INSERT INTO dataset (nomeTabela, tags, numeroLinhas, tipoImportacao, idDashboard, informacao, linkAPI)
                            VALUES ('$nome_tabela', '$tags', '$rowCount', '$tipoImportacao', 0, '$informacao_tabela', '$filePath')";

                    // Reconnect to the database before inserting metadata
                    reconnect($mysqli);

                    if (mysqli_query($mysqli, $sql)) {
                        // Redirect to home_dados.php after successful insertion
                        header("Location: home_dados.php");
                        exit(); // Ensure the script stops executing after redirection
                    } else {
                        echo "Error inserting metadata: " . mysqli_error($mysqli);
                    }
                } else {
                    echo "Error opening CSV file.";
                }
            } else {
                // Insert metadata for other import types
                $sql = "INSERT INTO dataset (nomeTabela, tags, numeroLinhas, tipoImportacao, idDashboard, informacao, linkAPI)
                        VALUES ('$nome_tabela', '$tags', '$numero_linhas', '$tipoImportacao', 0, '$informacao_tabela', '$filePath')";

                if (mysqli_query($mysqli, $sql)) {
                    // Redirect to home_dados.php after successful insertion
                    header("Location: home_dados.php");
                    exit(); // Ensure the script stops executing after redirection
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

// Close the database connection
mysqli_close($mysqli);
?>


