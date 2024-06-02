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

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
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
                    echo "<th colspan='" . count($data['results']) . "'>Nenhum registro encontrado.</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($data['results'])) {
                foreach ($data['results'] as $record) {
                    echo "<tr>";
                    foreach ($record as $key => $value) {
                        // Adicione formatação para valores específicos, se necessário
                        // Exemplo: if ($key === 'data') { $value = date('d/m/Y', strtotime($value)); }
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
    <footer>
        <div class="footer-content">
            <img src="Imagens/isep_logo.png" alt="ISEP Logo">
            <p>Projeto realizado no âmbito de Laboratório de Sistemas 1</p>
        </div>
    </footer>
</body>
<footer>
    <div class="footer-content">
        <div class="footer-left">
            <img src="Imagens/isep_logo.png" alt="ISEP Logo" class="isep_img" onclick="window.open('https://www.isep.ipp.pt', '_blank');">
            <img src="Imagens/e-redes.jpg" alt="E-Redes Logo" class="eredes_img" onclick="window.open('https://www.e-redes.pt/pt-pt', '_blank');">
        </div>
        <div class="footer-right">
            <p>Projeto realizado no âmbito de Laboratório de Sistemas 1</p>
        </div>
    </div>
</footer>

</html>