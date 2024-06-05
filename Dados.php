<?php
// Incluir o arquivo de configuração da conexão com o banco de dados
include("ImportSQL.php");

// Função para obter dados da API
function fetchData($offset, $limit)
{
    $url = "https://e-redes.opendatasoft.com/api/explore/v2.1/catalog/datasets/26-centrais/records?limit={$limit}&offset={$offset}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        // Tratar erros de cURL de forma mais explícita
        die('Erro cURL: ' . curl_error($ch));
    }
    curl_close($ch);
    return json_decode($response, true);
}

// Função para exibir o menu de navegação
function displayNavigationMenu($offset, $limit, $totalPages)
{
    echo "<div class='pagination'>";
    // Link para a página anterior
    if ($offset > 0) {
        $prevOffset = max(0, $offset - $limit);
        echo "<a href=\"?offset={$prevOffset}\">&lt;&lt; Página Anterior</a>";
    }
    // Número da página atual e número total de páginas
    $currentPage = ($offset / $limit) + 1;
    echo " Página {$currentPage} de {$totalPages} ";
    // Link para a próxima página
    $nextOffset = $offset + $limit;
    echo "<a href=\"?offset={$nextOffset}\">Próxima Página &gt;&gt;</a>";
    echo "</div>";
}

// Verificar se a sessão já está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Dados</title>
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
        <div class="dropdown">
            <button class="user-info">
                <img src="Imagens/user_icon.png" alt="User Icon">
                <span>Name</span>
            </button>
            <div class="dropdown-content">
                <a href="Login.php">Login</a>
                <a href="Register.php">Registo</a>
                <a href="User.php">Perfil</a>
                <a href="Logout.php">Sair</a>
            </div>
        </div>
    </header>

    <div class="topo">
        <div class="menu-container">
            <img src="https://www.svgrepo.com/show/509382/menu.svg" alt="Menu Icon" class="menu-icon">
            <div class="menu">
                <h2>Filtros</h2>
                <ul>
                    <li>
                        <input type="checkbox" id="Ano">
                        <label for="Ano">Ano</label>
                    </li>
                    <li>
                        <input type="checkbox" id="Semestre">
                        <label for="Semestre">Semestre</label>
                    </li>
                    <li>
                        <input type="checkbox" id="Concelho">
                        <label for="Concelho">Concelho</label>
                    </li>
                    <li>
                        <input type="checkbox" id="Potência de Ligação (kW)">
                        <label for="Potência de Ligação (kW)">Potência de Ligação (kW)</label>
                    </li>

                </ul>
                <div class="button-container">
                    <div class="custom-button">
                        <button onclick="window.location.href='Export.php'">Pesquisar</button>
                        /** botao ainda nao esta operacional */
                    </div>
                </div>
            </div>
        </div>
        <div class="button-container">
            <div class="custom-button">
                <button onclick="window.location.href='Export.php'">Exportar Dados</button>
            </div>
        </div>
    </div>

        
    <?php
    // Função para obter dados da API
    // Verifica se a função fetchData já foi definida antes de defini-la novamente
    if (!function_exists('fetchData')) {
        // Função para obter dados da API
        function fetchData($offset, $limit)
        {
            $url = "https://e-redes.opendatasoft.com/api/explore/v2.1/catalog/datasets/26-centrais/records?limit={$limit}&offset={$offset}";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                // Tratar erros de cURL de forma mais explícita
                die('Erro cURL: ' . curl_error($ch));
            }
            curl_close($ch);
            return json_decode($response, true);
        }
    }
    ?>

<?php
            // Consulta SQL para buscar os dados
            $sql = "SELECT nomeTabela FROM dataset";
            $result = mysqli_query($mysqli, $sql);

            if ($result) {
                // Inicializar a variável $nomeDataset como um array para armazenar os resultados
                $nomeDataset = array();

                // Fetch results and store in the array
                while ($row = mysqli_fetch_assoc($result)) {
                    $nomeDataset[] = $row['nomeTabela'];
                }

                // Mostrar os resultados dentro de tags <h1>
                foreach ($nomeDataset as $nome) {
                    echo "<h1>" . htmlspecialchars($nome) . "</h1>";

                    // Obtém os dados da API
                    $data = fetchData(0, 60); // Ajust

                    // Verifica se os dados foram obtidos corretamente e se a resposta é válida
                    if (isset($data['results']) && is_array($data['results'])) {
                        // Crie uma nova tabela com o nome do título
                        $nomeTabela = htmlspecialchars($nome); // Obtém o nome da tabela

                        // Primeira linha dos dados, que contém os nomes das colunas
                        $columns = array_keys($data['results'][0]);
                        $createTableSQL = "CREATE TABLE IF NOT EXISTS `{$nomeTabela}` (id INT AUTO_INCREMENT PRIMARY KEY, ";
                        foreach ($columns as $column) {
                            $createTableSQL .= "`{$column}` VARCHAR(255), ";
                        }
                        $createTableSQL = rtrim($createTableSQL, ", ") . ")";
                        if (mysqli_query($mysqli, $createTableSQL)) {
                            // Insira os dados na nova tabela
                            foreach ($data['results'] as $record) {
                                $insertValues = array_map(function ($value) use ($mysqli) {
                                    return mysqli_real_escape_string($mysqli, $value);
                                }, $record);
                                $insertValues = "'" . implode("','", $insertValues) . "'";
                                $insertSQL = "INSERT INTO `{$nomeTabela}` (`" . implode("`, `", $columns) . "`) VALUES ({$insertValues})";
                                if (!mysqli_query($mysqli, $insertSQL)) {
                                    echo "Erro ao inserir dados: " . mysqli_error($mysqli);
                                }
                            }
                        } else {
                            echo "Erro ao criar tabela: " . mysqli_error($mysqli);
                        }
                    } else {
                        // Caso não haja registros ou erro na resposta
                        if (empty($data['results'])) {
                            echo "<p>Nenhum registro encontrado.</p>";
                        } else {
                            echo "<p>Erro ao obter dados da API.</p>";
                            echo "<pre>";
                            print_r($data); // Exibir a resposta completa para depuração
                            echo "</pre>";
                        }
                    }
                }
            } else {
                echo "Erro na consulta: " . mysqli_error($mysqli);
            }
            ?>
    <main>
        <div class="table-container">
            <?php
            // Configurações iniciais
            $limit = 60; // Número de registros a serem exibidos por vez
            $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0; // Ponto de início inicial

            // Obtém os dados da API
            $data = fetchData($offset, $limit);

            // Verifica se os dados foram obtidos corretamente e se a resposta é válida
            if (isset($data['total_count'])) {
                // Obtém o número total de registros
                $totalRecords = intval($data['total_count']);

                // Função para calcular o número total de páginas
                function getTotalPages($totalRecords, $limit)
                {
                    return ceil($totalRecords / $limit);
                }

                // Obtém o número total de páginas
                $totalPages = getTotalPages($totalRecords, $limit);

                // Exibe o menu de navegação com o número total de páginas
                displayNavigationMenu($offset, $limit, $totalPages);
            } else {
                // Caso não haja registros ou erro na resposta
                if (empty($data['results'])) {
                    echo "<p>Nenhum registro encontrado.</p>";
                } else {
                    echo "<p>Erro ao obter dados da API.</p>";
                    echo "<pre>";
                    print_r($data); // Exibir a resposta completa para depuração
                    echo "</pre>";
                }
            }

            // Verificar se os dados foram obtidos corretamente e se a resposta é válida
            if (isset($data['results']) && is_array($data['results'])) {
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                // Criar cabeçalhos da tabela com base nas chaves do primeiro registro
                foreach ($data['results'][0] as $key => $value) {
                    echo "<th>" . htmlspecialchars($key) . "</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                // Iterar sobre os registros
                foreach ($data['results'] as $record) {
                    echo "<tr>";
                    foreach ($record as $key => $value) {
                        // Utilizar htmlspecialchars apenas quando necessário
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";

                // Exibir o menu de navegação
                displayNavigationMenu($offset, $limit, $totalPages);
            } else {
                // Caso não haja registros ou erro na resposta
                if (empty($data['results'])) {
                    echo "<p>Nenhum registro encontrado.</p>";
                } else {
                    echo "<p>Erro ao obter dados da API.</p>";
                    echo "<pre>";
                    print_r($data); // Exibir a resposta completa para depuração
                    echo "</pre>";
                }
            }
            ?>
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
</body>

</html>