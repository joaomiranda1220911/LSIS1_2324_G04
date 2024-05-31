<?php
$url = "https://e-redes.opendatasoft.com/api/explore/v2.1/catalog/datasets/26-centrais/records?limit=99";

$options = array(
  "http" => array(
    "header" => "Content-Type: application/json"
  )
);

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    die('Erro ao obter os dados');
}

$data = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Dados</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <?php
                if (isset($data['results'][0])) {
                    foreach ($data['results'][0] as $key => $value) {
                        echo "<th>" . htmlspecialchars($key) . "</th>";
                    }
                } else {
                    echo "<th colspan='2'>Nenhum registro encontrado.</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($data['results'])) {
                foreach ($data['results'] as $record) {
                    echo "<tr>";
                    foreach ($record as $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Nenhum registro encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>

