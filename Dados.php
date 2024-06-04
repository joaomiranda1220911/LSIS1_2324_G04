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
    <link rel="stylesheet" href="styles.css">
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
    <header>
        <div class="logo" onclick="window.location.href='index.php'">
            <img src="Imagens/casa_icon.png" alt="Logo">
        </div>
        <nav>
            <div class="nav-buttons">
                <button><a href="SobreNos.php">Sobre Nós</a></button>
                <button><a href="home_dados.php">Dados</a></button>
                <button><a href="Analise.php">Análise</a></button>
            </div>
        </nav>
        <div class="search-bar">
            <input type="text" placeholder="Pesquisar">
            <button class="search-button"><img src="Imagens/search_icon.png" alt="ir"></button>
        </div>
        <div class="dropdown">
            <div class="user-info">
                <img src="Imagens/user_icon.png" alt="User Icon">
                <span>Name</span>
                <div class="dropdown-content">
                    <a href="Login.php">Login</a>
                    <a href="Register.php">Registo</a>
                    <a href="User.php">Perfil</a>
                    <a href="#">Sair</a>
                </div>
            </div>
        </div>
    </header>
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
</body>
<footer>
    <div class="footer-content">
        <div class="footer-left">
            <img src="Imagens/isep_logo.png" alt="ISEP Logo" class="isep_img" onclick="window.open('https://www.isep.ipp.pt', '_blank');">
            <img src="Imagens/e-redes.jpeg" alt="E-Redes Logo" class="eredes_img" onclick="window.open('https://www.e-redes.pt/pt-pt', '_blank');">
        </div>
        <div class="footer-right">
            <p>Projeto realizado no âmbito de Laboratório de Sistemas 1</p>
        </div>
    </div>
</footer>

</html>