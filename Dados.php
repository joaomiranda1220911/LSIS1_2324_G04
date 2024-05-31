<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Dados</title>
</head>

<body>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $url = "https://e-redes.opendatasoft.com/api/explore/v2.1/catalog/datasets/26-centrais/records?limit=20";

            $options = array(
                "http" => array(
                    "header" => "Content-Type: application/json"
                )
            );

            $context = stream_context_create($options);
            $response = file_get_contents($url, false, $context);

            if ($response === FALSE) {
                die('Error occurred');
            }

            $data = json_decode($response, true);

            if (isset($data["records"]) && is_array($data["records"])) {
                foreach ($data["records"] as $record) {
                    echo "<tr>";
                    echo "<td>" . $record["recordid"] . "</td>";
                    echo "<td>" . $record["fields"]["nome"] . "</td>";
                    echo "<td>" . $record["fields"]["latitude"] . "</td>";
                    echo "<td>" . $record["fields"]["longitude"] . "</td>";
                    echo "<td>" . $record["fields"]["estado"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nenhum dado disponível</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>

