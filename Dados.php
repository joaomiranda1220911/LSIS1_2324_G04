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
                <button onclick="window.location.href='Export.php'">Export Dados</button>
            </div>
        </div>
    </div>
    <div class="button-container">
        <div class="custom-button">
            <button onclick="window.location.href='Export.php'">Informação sobre os dados</button>
        </div>
    </div>

    <?php
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

    // Configurações iniciais
    $limit = 60; // Número de registros a serem exibidos por vez
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0; // Ponto de início inicial

    // Função para exibir o menu de navegação
    function displayNavigationMenu($offset, $limit)
    {
        echo "<div class='pagination'>";
        // Link para a página anterior
        if ($offset > 0) {
            $prevOffset = max(0, $offset - $limit);
            echo "<a href=\"?offset={$prevOffset}\">&lt;&lt; Página Anterior</a>";
        }
        // Número da página atual
        $currentPage = ($offset / $limit) + 1;
        echo " Página {$currentPage} ";
        // Link para a próxima página
        $nextOffset = $offset + $limit;
        echo "<a href=\"?offset={$nextOffset}\">Próxima Página &gt;&gt;</a>";
        echo "</div>";
    }

    // Loop para obter e exibir os dados
    // Obter os dados da API
    $data = fetchData($offset, $limit);

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
        displayNavigationMenu($offset, $limit);
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