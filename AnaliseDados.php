<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análise aos Dados</title>
    <link rel="icon" href="Imagens/favicon.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles.css">
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

    <main class="l">
        <?php
        // Incluir o arquivo de configuração da conexão com o banco de dados
        include("ImportSQL.php");

        // Verificar se o parâmetro nomeTabelaAtual foi recebido
        if (isset($_GET['nomeTabelaAtual'])) {
            // Receber o valor do parâmetro
            $nomeTabelaAtual = $_GET['nomeTabelaAtual'];
            echo "Parâmetro nomeTabelaAtual: $nomeTabelaAtual"; // Depuração


            // Verificar o título da tabela e definir as colunas correspondentes
            if ($nomeTabelaAtual == "total_de_unidades_de_produção_para_autoconsumo") {
                // Relacionar as colunas para esta tabela
                echo "<h1>Total de Unidades de Produção para Autoconsumo</h1>";
                $coluna1 = "numero_de_instalacoes";
                $coluna2 = "potencia_total_instalada_upac_kw";
            } elseif ($nomeTabelaAtual == "novas_unidades_de_produção_para_autoconsumo") {
                // Relacionar as colunas para esta tabela
                echo "<h1>Novas Unidades de Produção para Autoconsumo</h1>";
                $coluna1 = "ano";
                $coluna2 = "processos_concluidos";
            } elseif ($nomeTabelaAtual == "caracterização_de_pontos_de_consumo_(cpes),_com_contratos ativos") {
                echo "<h1>Caracterização de Pontos de Consumo (CPES) com Contratos Ativos</h1>";
                $coluna1 = "tipo_de_instalacao";
                $coluna2 = "cpes";
            } else {
                // Se o título da tabela não corresponder a nenhum dos casos anteriores
                $titulo = "Título da Tabela Desconhecido";
                $coluna1 = "";
                $coluna2 = "";
            }

            // Consultar o banco de dados para obter os dados necessários para o gráfico
            // Consultar o banco de dados para obter os dados necessários para o gráfico
            $sql = "SELECT $coluna1, $coluna2 FROM $nomeTabelaAtual";
            echo "Consulta SQL: $sql"; // Adicionado para depuração
            $result = mysqli_query($mysqli, $sql);

            if ($result) {
                // Inicializar arrays para armazenar os dados do gráfico
                $dcoluna1 = array();
                $dcoluna2 = array();

                // Iterar sobre os resultados da consulta e armazenar os dados nos arrays
                while ($row = mysqli_fetch_assoc($result)) {
                    $dcoluna1[] = $row[$coluna1];
                    $dcoluna2[] = $row[$coluna2];
                }
            } else {
                // Em caso de erro na consulta SQL
                echo "Erro na consulta SQL: " . mysqli_error($mysqli);
            }
        } else {
            // Caso o parâmetro não tenha sido recebido
            echo "Erro: parâmetro 'nomeTabelaAtual' não foi recebido na URL.";
        }
        ?>



        <main>
            <!-- Conteúdo principal aqui -->
            <canvas id="graficoProcessosConcluidos" width="400" height="400"></canvas>
        </main>


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
            // Obter referência para o elemento canvas
            var ctx = document.getElementById('graficoProcessosConcluidos').getContext('2d');

            // Definir os dados para o gráfico com base nos valores obtidos do PHP
            var data = {
                labels: <?php echo json_encode($dcoluna1); ?>,
                datasets: [{
                    label: '<?php echo $titulo; ?>',
                    data: <?php echo json_encode($dcoluna2); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Cor de fundo do gráfico
                    borderColor: 'rgba(255, 99, 132, 1)', // Cor da borda do gráfico
                    borderWidth: 1
                }]
            };


            // Configurar as opções do gráfico
            var options = {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            };

            // Criar o gráfico de linha
            var myChart = new Chart(ctx, {
                type: 'line',
                data: data,
                options: options
            });
        </script>
</body>

</html>