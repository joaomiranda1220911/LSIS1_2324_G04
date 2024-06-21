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
        /* Estilos específicos para esta página */
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
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: white;
        }

        .chart {
            width: 100%;
            height: 370px;
        }

        form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 100%;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        form label,
        form select {
            flex: 1;
        }

        form select {
            width: 100%;
            max-width: 300px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            margin-right: 20px;
        }

        form input[type="submit"] {
            background-color: #FFDC00;
            color: #000;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #333;
            color: #FFDC00;
        }

        footer {
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .footer-content {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #FFDC00;
            bottom: 0;
        }

        .error {
            color: #d3b700;
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
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
        $nomeTabelaAtual = $_GET['nomeTabelaAtual'];

        // Função para obter nomes das colunas da tabela
        function obterColunas($mysqli, $tabela)
        {
            $sql = "SHOW COLUMNS FROM $tabela";
            $result = $mysqli->query($sql);
            $colunas = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $colunas[] = $row['Field'];
                }
            }
            return $colunas;
        }

        $colunas = obterColunas($mysqli, $nomeTabelaAtual);

        // Inicializar variáveis
        $dataPoints1 = array();
        $mensagemErro = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $xColuna1 = isset($_POST['xColuna1']) ? $_POST['xColuna1'] : '';
            $yColuna1 = isset($_POST['yColuna1']) ? $_POST['yColuna1'] : '';

            if (empty($xColuna1) || empty($yColuna1)) {
                $mensagemErro = "Por favor preencha os parâmetros do gráfico";
            } else {
                // Obter dados com base na seleção do utilizador e ordenar pelo eixo X
                function obterDadosSelecionados($mysqli, $tabela, $xColuna, $yColuna)
                {
                    $dataPoints = array();
                    if ($xColuna && $yColuna) {
                        $sql = "SELECT $xColuna AS x, $yColuna AS y FROM $tabela ORDER BY $xColuna ASC";
                        $result = $mysqli->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $dataPoints[] = array("label" => $row["x"], "y" => $row["y"]);
                            }
                        }
                    }
                    return $dataPoints;
                }

                $dataPoints1 = obterDadosSelecionados($mysqli, $nomeTabelaAtual, $xColuna1, $yColuna1);
            }
        }
    }
    // Verificar se $nomeTabelaAtual está definido e ajustar o título da página
    $tituloPagina = isset($nomeTabelaAtual) ? str_replace("_", " ", $nomeTabelaAtual) : "Análise de Dados";
    echo "<h1>Análise - " . $tituloPagina . "</h1>";
    ?>

    <?php if (!empty($mensagemErro)) : ?>
        <p class="error"><?php echo $mensagemErro; ?></p>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?nomeTabelaAtual=' . $nomeTabelaAtual; ?>">
        <label for="xColuna1">Eixo X:</label>
        <select name="xColuna1" id="xColuna1">
            <option value="" disabled selected>-</option>
            <?php foreach ($colunas as $coluna) {
                echo "<option value='$coluna'>$coluna</option>";
            } ?>
        </select>
        <br>
        <label for="yColuna1">Eixo Y:</label>
        <select name="yColuna1" id="yColuna1">
            <option value="" disabled selected>-</option>
            <?php foreach ($colunas as $coluna) {
                echo "<option value='$coluna'>$coluna</option>";
            } ?>
        </select>
        <br>
        <input type="submit" value="Gerar Gráfico">
    </form>

    <?php if (!empty($dataPoints1)) : ?>
        <div class="chart-container">
            <div class="chart" id="chartContainer1"></div>
        </div>

        <script>
            window.onload = function() {
                var chart1 = new CanvasJS.Chart("chartContainer1", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "Gráfico Dinâmico"
                    },
                    axisX: {
                        title: "<?php echo $xColuna1; ?>"
                    },
                    axisY: {
                        title: "<?php echo $yColuna1; ?>"
                    },
                    data: [{
                        type: "column",
                        dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart1.render();
            }
        </script>
    <?php endif; ?>

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

</body>

</html>