<?php
session_start();
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Function to determine the SQL data type based on PHP variable type
function determineDataType($value) {
    if (is_numeric($value)) {
        return 'INT';
    } elseif (is_float($value) || is_double($value)) {
        return 'FLOAT';
    } elseif (strtotime($value)) {
        return 'DATETIME';
    } else {
        return 'VARCHAR(255)';
    }
}

// Check if the form is submitted
if (isset($_POST['import'])) {
    // Check if a file is selected
    if ($_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
        // Set session variable for error message
        $_SESSION['import_error'] = "Por favor escolha o ficheiro que deseja importar.";
    } else {
        // Get the file extension
        $file_info = pathinfo($_FILES['file']['name']);
        $extension = strtolower($file_info['extension']);

        // Validate file extension
        if (!in_array($extension, ['csv', 'xlsx'])) {
            // Set session variable for error message
            $_SESSION['import_error'] = "Por favor escolha um ficheiro com extensão CSV ou XLSX.";
        } else {
            // Get the table name from the form
            $table_name = $_POST['table'];

            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "lsis1_g04";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Read the uploaded file
            $file = $_FILES['file']['tmp_name'];
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();

            // Get the first row to determine column names
            $header = $sheet->rangeToArray('A1:' . $sheet->getHighestColumn() . '1')[0];

            // Determine column data types
            $dataTypes = [];
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                if ($rowIndex == 1) continue; // Skip header row
                $rowData = $sheet->rangeToArray('A' . $rowIndex . ':' . $sheet->getHighestColumn() . $rowIndex)[0];
                foreach ($rowData as $colIndex => $value) {
                    $colName = $header[$colIndex];
                    if (!isset($dataTypes[$colName])) {
                        $dataTypes[$colName] = determineDataType($value);
                    }
                }
            }

            // Create table SQL query
            $columns = [];
            foreach ($header as $colName) {
                $columns[] = "`$colName` " . ($dataTypes[$colName] ?? 'VARCHAR(255)');
            }
            $createTableSQL = "CREATE TABLE `$table_name` (" . implode(", ", $columns) . ")";

            // Execute create table query
            if ($conn->query($createTableSQL) === TRUE) {
                // Use prepared statements to insert data
                $columns_str = implode(", ", $header);
                $placeholders = implode(", ", array_fill(0, count($header), "?"));
                $stmt = $conn->prepare("INSERT INTO $table_name ($columns_str) VALUES ($placeholders)");

                // Dynamically bind parameters
                $types = str_repeat('s', count($header));
                $params = array_fill(0, count($header), '');

                // Iterate through each row in the sheet
                foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                    if ($rowIndex == 1) continue; // Skip header row
                    $rowData = $sheet->rangeToArray('A' . $rowIndex . ':' . $sheet->getHighestColumn() . $rowIndex)[0];
                    if (count($rowData) === count($header)) {
                        for ($i = 0; $i < count($rowData); $i++) {
                            $params[$i] = $rowData[$i];
                        }
                        $stmt->bind_param($types, ...$params);
                        $stmt->execute();
                    } else {
                        $_SESSION['import_error'] = "Número de colunas no ficheiro não corresponde ao da tabela.";
                        break;
                    }
                }

                // Close statement and connection
                $stmt->close();
                $conn->close();

                // Set session variable to indicate successful import
                if (!isset($_SESSION['import_error'])) {
                    $_SESSION['import_success'] = "Importação bem-sucedida.";
                }
            } else {
                $_SESSION['import_error'] = "Erro ao criar a tabela: " . $conn->error;
            }
        }
    }
    header("Location: Dados.php");
    exit();
}
?>
