<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Estatísticas</title>
    <link rel="icon" href="Imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            height: auto;
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

        .expand-button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px 0 20px;
            width: 1000px;
            min-width: 300px;
            height: 50px;
            border-radius: 8px;
            background-color: #fff;
            margin: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .expand-button-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .expand-button {
            background-color: transparent;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }

        .chart-text {
            width: 1000px;
            min-width: 300px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            margin: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: none;
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
        include("ImportSQL.php");

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $nome_utilizador = "Utilizador";

        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
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
        <div class="expand-button-container" onclick="toggleText('correlationText1')">
            <div class="expand-button-wrapper">
                <button class="expand-button">▼</button>
            </div>
        </div>
        <div id="correlationText1" class="chart-text">
            <h2>Correlação PIB e Processos Concluídos</h2>
            <p style="text-align: justify">
                De acordo com o gráfico de correlação entre os processos concluídos e o PIB por região, observa-se uma correlação significativa entre essas duas variáveis.
                É possível observar que a região onde a correlação é mais forte é o Tâmega e Sousa, seguida pelas regiões do Douro e do Alto Tâmega, respetivamente.
                No caso do Alto Minho, Guimarães destaca-se como o local com a maior quantidade de painéis fotovoltaicos instalados, apresentando uma alta correlação com o PIB. Contudo, o valor de correlação para a região como um todo é baixo, uma vez que os outros concelhos abrangentes não apresentam uma correlação tão elevada.
                Com base nesta análise, podemos concluir que o PIB é um fator influente no número de painéis fotovoltaicos por região. Em geral, regiões com um PIB mais elevado tendem a possuir uma maior quantidade de painéis fotovoltaicos instalados.
            </p>
        </div>

        <div class="chart-container">
            <canvas id="correlationChart2"></canvas>
        </div>
        <div class="expand-button-container" onclick="toggleText('correlationText2')">
            <div class="expand-button-wrapper">
                <button class="expand-button">▼</button>
            </div>
        </div>
        <div id="correlationText2" class="chart-text">
            <h2>Correlação Consumo de Energia e Total de Unidades</h2>
            <p style="text-align: justify">
                Considerando os valores obtidos após a análise de correlação entre o Consumo de Energia e o Número de Instalações por região, concluímos que essa correlação não é muito significativa. Embora, na sua maioria, quanto maior o Consumo de Energia, maior seja a quantidade de painéis fotovoltaicos necessária para suprir as necessidades da região, essa conclusão não é totalmente precisa, pois, os valores de correlação obtidos não foram muito elevados.
            </p>
        </div>

        <div class="chart-container">
            <canvas id="correlationChart3"></canvas>
        </div>
        <div class="expand-button-container" onclick="toggleText('correlationText3')">
            <div class="expand-button-wrapper">
                <button class="expand-button">▼</button>
            </div>
        </div>
        <div id="correlationText3" class="chart-text">
            <h2>Correlação Consumo de Energia e Processos Concluídos</h2>
            <p style="text-align: justify">
                Ao analisar o gráfico obtido pela correlação entre o Consumo de Energia e os Processos Concluídos por região, observa-se uma forte correlação entre essas duas variáveis.
                Em várias regiões do país, os valores de correlação são superiores a 0.8, indicando que, quanto maior o consumo de energia em uma determinada região, maior tende a ser a quantidade de painéis fotovoltaicos instalados.
                Entretanto, essa relação não é observada de forma tão linear nas regiões do Alentejo Litoral, Baixo Alentejo, e nas áreas Metropolitanas de Porto e Lisboa.
            </p>
        </div>


        <div class="chart-container">
            <canvas id="correlationChart4"></canvas>
        </div>
        <div class="expand-button-container" onclick="toggleText('correlationText4')">
            <div class="expand-button-wrapper">
                <button class="expand-button">▼</button>
            </div>
        </div>
        <div id="correlationText4" class="chart-text">
            <h2>Correlação Nível de Escolaridade e Total de Unidades</h2>
            <p style="text-align: justify">
                De acordo com o gráfico de correlação entre o Total de Unidades e o Nível de Escolaridade, observa-se uma ligeira correlação entre essas duas variáveis.
                Verifica-se que indivíduos com formação até ao ensino superior possuem um maior número de unidades de painéis fotovoltaicos, seguidos por aqueles com formação até ao ensino secundário e médio, respetivamente.
                Com base nesta análise, podemos concluir que o Nível de Escolaridade é um fator influente no número de painéis fotovoltaicos. Em geral, quanto mais elevado for o nível de escolaridade alcançado, maior é a tendência de possuírem painéis fotovoltaicos.
            </p>
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
        document.addEventListener('DOMContentLoaded', function() {
            fetch('corr_PCxPIB.json')
                .then(response => response.json())
                .then(data => {
                    // Obter os nomes das regiões distintas
                    const regionNames = [...new Set(data.map(item => item['Regiao']))];

                    // Calcular a média de correlação por região
                    const correlationData = regionNames.map(region => {
                        const regionData = data.filter(item => item['Regiao'] === region);
                        const validData = regionData.filter(item => item['Correlacao_ProcessosConcluidos_PIB'] !== undefined);
                        const averageCorrelation = validData.reduce((sum, item) => {
                            const correlation = parseFloat(item['Correlacao_ProcessosConcluidos_PIB']);
                            return sum + (isNaN(correlation) ? 0 : correlation);
                        }, 0) / validData.length;
                        return averageCorrelation;
                    });

                    // Configurar o gráfico inicial por região
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

                    // Função para calcular média de correlação por concelho
                    function calcularMediaCorrelacaoConcelhos(data, regiao) {
                        const concelhosData = {};
                        const regiaoData = data.filter(item => item['Regiao'] === regiao);

                        regiaoData.forEach(item => {
                            const concelho = item['Concelho'];
                            const correlation = parseFloat(item['Correlacao_ProcessosConcluidos_PIB']);

                            if (!isNaN(correlation)) {
                                if (!concelhosData[concelho]) {
                                    concelhosData[concelho] = {
                                        totalCorrelation: 0,
                                        count: 0
                                    };
                                }

                                concelhosData[concelho].totalCorrelation += correlation;
                                concelhosData[concelho].count++;
                            }
                        });

                        // Calcular média de correlação por concelho
                        const concelhosCorrelationData = Object.keys(concelhosData).map(concelho => {
                            const averageCorrelation = concelhosData[concelho].totalCorrelation / concelhosData[concelho].count;
                            return {
                                concelho: concelho,
                                correlacao: averageCorrelation
                            };
                        });

                        return concelhosCorrelationData;
                    }

                    // Adicionar evento de clique para atualizar o gráfico ao clicar em uma barra de região
                    correlationChart.options.onClick = function(evt, elements) {
                        if (elements.length > 0) {
                            const clickedRegion = correlationChart.data.labels[elements[0].index];
                            const concelhosCorrelationData = calcularMediaCorrelacaoConcelhos(data, clickedRegion);

                            const concelhosLabels = concelhosCorrelationData.map(item => item.concelho);
                            const concelhosCorrelationValues = concelhosCorrelationData.map(item => item.correlacao);

                            // Atualizar o gráfico com dados dos concelhos
                            correlationChart.data.labels = concelhosLabels;
                            correlationChart.data.datasets[0].data = concelhosCorrelationValues;
                            correlationChart.options.scales.x.title.text = 'Concelho';
                            correlationChart.update();
                        }
                    };

                    // Adicionar evento de clique ao documento para voltar ao gráfico por região
                    document.addEventListener('click', function(evt) {
                        const isOutsideChart = !correlationChart.canvas.contains(evt.target);
                        if (isOutsideChart) {
                            correlationChart.data.labels = regionNames;
                            correlationChart.data.datasets[0].data = correlationData;
                            correlationChart.options.scales.x.title.text = 'Região';
                            correlationChart.update();
                        }
                    });
                })
                .catch(error => console.error('Erro ao carregar dados:', error));
        });

        function toggleText(id) {
            const element = document.getElementById(id);
            const button = element.previousElementSibling.firstElementChild.firstElementChild;

            if (element.style.display === "none" || element.style.display === "") {
                element.style.display = "block";
                button.innerHTML = "▲";
            } else {
                element.style.display = "none";
                button.innerHTML = "▼";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Função para carregar e processar os dados do segundo arquivo JSON
            fetch('corr_CExTU.json')
                .then(response => response.json())
                .then(data => {
                    // Processar os dados conforme necessário
                    const regionNames = data.map(item => item['Região']);
                    const correlationData = data.map(item => item['Correlacao_Número_de_instalacões_Total']);

                    // Configurar o segundo gráfico
                    const ctx2 = document.getElementById('correlationChart2').getContext('2d');
                    const correlationChart2 = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: regionNames,
                            datasets: [{
                                label: 'Correlação Consumo de Energia vs Total de Unidades',
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
                })
                .catch(error => console.error('Erro ao carregar dados do segundo arquivo:', error));
        });

        // Script para carregar e configurar o terceiro gráfico
        document.addEventListener('DOMContentLoaded', function() {
            fetch('corr_CExPC.json')
                .then(response => response.json())
                .then(data => {
                    const regionNames = data.map(item => item['Região']);
                    const correlationData = data.map(item => item['Correlacao_Número_de_instalacões_Total']);

                    const ctx3 = document.getElementById('correlationChart3').getContext('2d');
                    const correlationChart3 = new Chart(ctx3, {
                        type: 'bar',
                        data: {
                            labels: regionNames,
                            datasets: [{
                                label: 'Correlação Consumo de Energia vs Processos Concluídos',
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
                })
                .catch(error => console.error('Erro ao carregar dados do terceiro arquivo:', error));
        });

        // Script para carregar e configurar o quarto gráfico
        document.addEventListener('DOMContentLoaded', function() {
            fetch('corr_NExTU.json')
                .then(response => response.json())
                .then(data => {
                    // Extrair chaves e valores do JSON
                    const educationLevels = Object.keys(data);
                    const correlationValues = Object.values(data);

                    // Configurar o quarto gráfico
                    const ctx4 = document.getElementById('correlationChart4').getContext('2d');
                    const correlationChart4 = new Chart(ctx4, {
                        type: 'bar',
                        data: {
                            labels: educationLevels,
                            datasets: [{
                                label: 'Correlação Nível de Escolaridade vs Total de Unidades',
                                data: correlationValues,
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
                })
                .catch(error => console.error('Erro ao carregar dados do quarto arquivo:', error));
        });
    </script>


</body>

</html>