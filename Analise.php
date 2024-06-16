<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Estatísticas</title>
    <link rel="icon" href="Imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <style>
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            height: auto;
            /* Permitir altura automática para acomodar todos os gráficos */
            padding: 20px;
            margin-top: 20px;
            background-color: #f4f4f4;
            box-shadow: none;
            border: none;
        }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 1000px;
            height: 400px;
            min-width: 300px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
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

    <main class="dashboard">
        <h1>Análise de Correlação</h1>
        <div class="chart-container">
            <canvas id="correlationChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="pibCpesChart"></canvas>
        </div>
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
        // Carregar e processar o arquivo CSV para o primeiro gráfico
        Papa.parse('regiao_pib_novasinst.csv', {
            download: true,
            header: true,
            delimiter: ';',
            complete: function(results) {
                const data = results.data;

                // Extrair nomes das regiões e correlações
                const regionNames = data.map(item => item['Região']);
                const correlationData = data.map(item => parseFloat(item['Correlacao_ProcessosConcluidos_PIB'].replace(',', '.'))); // Substituir ',' por '.' para interpretar números corretamente

                // Configurar o gráfico
                const ctx = document.getElementById('correlationChart').getContext('2d');
                const correlationChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: regionNames,
                        datasets: [{
                            label: 'Correlação Processos Concluídos vs PIB',
                            data: correlationData,
                            backgroundColor: '#FFDC00',
                            borderColor: 'black',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Correlação'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Região'
                                }
                            }
                        }
                    }
                });
            }
        });


        // Carregar e processar o arquivo CSV para o primeiro gráfico
        Papa.parse('regiao_pib_novasinst.csv', {
            download: true,
            header: true,
            delimiter: ';',
            complete: function(results) {
                const data = results.data;
                const regionNames = data.map(item => item['Região']);
                const correlationData = data.map(item => parseFloat(item['Correlacao_ProcessosConcluidos_PIB'].replace(',', '.')));

                const ctx = document.getElementById('correlationChart').getContext('2d');
                const correlationChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: regionNames,
                        datasets: [{
                            label: 'Correlação Processos Concluídos vs PIB',
                            data: correlationData,
                            backgroundColor: '#FFDC00',
                            borderColor: 'black',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Correlação'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Região'
                                }
                            }
                        }
                    }
                });
            }
        });

        // Carregar e processar o arquivo CSV para o segundo gráfico
        Papa.parse('escolaridade_instalacoes.csv', {
            download: true,
            header: true,
            delimiter: ';',
            complete: function(results) {
                const data = results.data;
                const educationLevels = data.map(item => item['Variável de Educação']);
                const correlationData = data.map(item => parseFloat(item['Correlação'].replace(',', '.')));

                const ctx2 = document.getElementById('pibCpesChart').getContext('2d');
                const pibCpesChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: educationLevels,
                        datasets: [{
                            label: 'Correlação Nível de Escolaridade vs Total de UPAC',
                            data: correlationData,
                            backgroundColor: '#FFDC00',
                            borderColor: 'black',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Correlação'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Nível de Escolaridade'
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>

</body>

</html>