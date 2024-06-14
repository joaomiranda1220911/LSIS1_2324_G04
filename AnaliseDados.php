<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análise aos Dados</title>
    <link rel="icon" href="Imagens/favicon.ico" type="image/x-icon">
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <link rel="stylesheet" href="styles.css">
    <style>
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 200px);
            padding: 20px;
            background-color: #f4f4f4;
            box-shadow: none;
            border: none;
        }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 1500px;
            height: 500px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: white;
            margin: 10px;
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
                <button><a href="Mapa.php">Mapa</a></button>
            </div>
        </nav>
        <div class="search-bar">
            <input type="text" placeholder="Pesquisar">
            <button class="search-button"><img src="Imagens/search_icon.png" alt="ir"></button>
        </div>

        <?php
        // Incluir o arquivo de configuração da conexão com o banco de dados
        include("ImportSQL.php");

        // Verificar se a sessão já está ativa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Definir um nome padrão
        $nome_utilizador = "Utilizador";

        // Verificar se o usuário está logado
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];

            // Query para selecionar o nome do usuário
            $sql = "SELECT nome FROM utilizador WHERE email = '$email'";
            $result = mysqli_query($mysqli, $sql);

            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $nome_utilizador = $row['nome'];
            }
        } else {
            $nome_utilizador = "Visitante";
        }
        ?>

        <div class="dropdown">
            <button class="user-info">
                <img src="Imagens/user_icon.png" alt="User Icon">
                <span><?php echo $nome_utilizador; ?></span>
            </button>
            <div class="dropdown-content">
                <a href="Login.php">Login</a>
                <a href="Register.php">Registo</a>
                <a href="User.php">Perfil</a>
                <a href="Logout.php">Sair</a>
            </div>
        </div>
    </header>

    <?php
    // Verificar se o parâmetro nomeTabelaAtual foi recebido
    if (isset($_GET['nomeTabelaAtual'])) {
        // Receber o valor do parâmetro
        $nomeTabelaAtual = $_GET['nomeTabelaAtual'];

        // Função para obter nomes das colunas da tabela
        function obterColunas($mysqli, $tabela)
        {
            $sql = "SHOW COLUMNS FROM $tabela";
            $result = $mysqli->query($sql);
            $colunas = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($colunas, $row['Field']);
                }
            }
            return $colunas;
        }

        $colunas = obterColunas($mysqli, $nomeTabelaAtual);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $xColuna = $_POST['xColuna'];
            $yColuna = $_POST['yColuna'];

            // Obter dados com base na seleção do utilizador
            function obterDadosSelecionados($mysqli, $tabela, $xColuna, $yColuna)
            {
                $sql = "SELECT $xColuna AS x, $yColuna AS y FROM $tabela";
                $result = $mysqli->query($sql);
                $dataPoints = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($dataPoints, array("label" => $row["x"], "y" => $row["y"]));
                    }
                }
                return $dataPoints;
            }

            $dataPoints1 = obterDadosSelecionados($mysqli, $nomeTabelaAtual, $xColuna, $yColuna);
            $dataPoints2 = obterDadosSelecionados($mysqli, $nomeTabelaAtual, $xColuna, $yColuna);
        } else {
            $dataPoints1 = array();
            $dataPoints2 = array();
        }
    }
    ?>

    <?php
    // Verificar se $nomeTabelaAtual está definido e ajustar o título da página
    $tituloPagina = isset($nomeTabelaAtual) ? str_replace("_", " ", $nomeTabelaAtual) : "Análise de Dados";
    echo "<h1>Análise - " . $tituloPagina . "</h1>";
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?nomeTabelaAtual=' . $nomeTabelaAtual; ?>">
        <label for="xColuna">Escolhe a coluna para o eixo X:</label>
        <select name="xColuna" id="xColuna">
            <?php foreach ($colunas as $coluna) {
                echo "<option value='$coluna'>$coluna</option>";
            } ?>
        </select>
        <br><br>
        <label for="yColuna">Escolhe a coluna para o eixo Y:</label>
        <select name="yColuna" id="yColuna">
            <?php foreach ($colunas as $coluna) {
                echo "<option value='$coluna'>$coluna</option>";
            } ?>
        </select>
        <br><br>
        <input type="submit" value="Gerar Gráficos">
    </form>

    <div class="chart-container">
        <div id="chartContainer1" style="height: 370px; width: 100%;"></div>
        <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
    </div>

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

    <script>
        // Configuração dos gráficos CanvasJS
        window.onload = function() {
            var chart1 = new CanvasJS.Chart("chartContainer1", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Gráfico 1"
                },
                axisX: {
                    title: "Eixo X"
                },
                axisY: {
                    title: "Eixo Y",
                    includeZero: false
                },
                data: [{
                    type: "line",
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart1.render();

            var chart2 = new CanvasJS.Chart("chartContainer2", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Gráfico 2"
                },
                axisX: {
                    title: "Eixo X"
                },
                axisY: {
                    title: "Eixo Y",
                    includeZero: false
                },
                data: [{
                    type: "line",
                    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart2.render();
        };
    </script>
</body>

</html>