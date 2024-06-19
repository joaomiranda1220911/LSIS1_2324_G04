<?php
// Incluir o arquivo de configuração da conexão com o banco de dados
include("ImportSQL.php");
// Consulta SQL para buscar os nomes das tabelas
if (isset($_GET['nomeTabela']) && isset($_GET['linkAPI'])) {
    $nomeTabelaAtual = htmlspecialchars($_GET['nomeTabela']);
    $linkAPI = htmlspecialchars($_GET['linkAPI']); // Adicionado

    // Definir o título da página com base no nome da tabela atual
    $tituloPagina = "Dados - " . $nomeTabelaAtual;
} else {
    // Redirecionar ou mostrar mensagem de erro caso os parâmetros não estejam presentes
    die('Parâmetros necessários não foram passados pela URL.');
}

// Função para obter dados da API
function fetchData($offset, $limit, $mysqli, $nomeTabelaAtual)
{
    // Consultar a base de dados para obter o URL da API com base no nome da tabela atual
    $sql = "SELECT linkAPI FROM dataset WHERE nomeTabela = '{$nomeTabelaAtual}'";
    $result = mysqli_query($mysqli, $sql);
    if ($result) {
        // Verificar se há resultados
        if (mysqli_num_rows($result) > 0) {
            // Obter a URL da API a partir do resultado da consulta
            $row = mysqli_fetch_assoc($result);
            $url = $row['linkAPI'];

            // Substituir os placeholders pelo offset e limit
            $url = str_replace('{$limit}', $limit, $url);
            $url = str_replace('{$offset}', $offset, $url);

            // Iniciar uma requisição cURL
            $ch = curl_init();
            if (!$ch) {
                die("Falha ao inicializar a requisição cURL.");
            }
            // Configurar a URL e outras opções
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Tempo limite de conexão em segundos
            curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Tempo limite total da operação em segundos
            // Executar a requisição cURL
            $response = curl_exec($ch);
            // Verificar erros de cURL
            if (curl_errno($ch)) {
                // Tratar erros de cURL
                $error_message = curl_error($ch);
                curl_close($ch);
                die("Erro cURL: " . $error_message);
            }
            // Fechar a conexão cURL
            curl_close($ch);
            // Verificar se a resposta é válida
            if ($response === false) {
                die("Erro ao obter resposta da API.");
            }
            // Decodificar a resposta como array associativo
            $data = json_decode($response, true);
            // Verificar se houve erro na decodificação
            if ($data === null) {
                die("Erro ao decodificar a resposta da API.");
            }
            // Retornar os dados obtidos da API
            return $data;
        } else {
            // Caso nenhuma URL seja encontrada na tabela
            die('Nenhuma URL encontrada na tabela dataset.');
        }
    } else {
        // Em caso de erro na consulta SQL
        die('Erro na consulta SQL: ' . mysqli_error($mysqli));
    }
}

// Função para exibir o menu de navegação
function displayNavigationMenu($offset, $limit, $totalPages, $nomeTabelaAtual, $linkAPI)
{
    echo "<div class='pagination'>";
    // Link para a página anterior
    if ($offset > 0) {
        $prevOffset = max(0, $offset - $limit);
        echo "<a href=\"?nomeTabela={$nomeTabelaAtual}&linkAPI={$linkAPI}&offset={$prevOffset}\">&lt;&lt; Página Anterior</a>";
    }
    // Número da página atual e número total de páginas
    $currentPage = ($offset / $limit) + 1;
    echo " Página {$currentPage} de {$totalPages} ";
    // Link para a próxima página
    $nextOffset = $offset + $limit;
    echo "<a href=\"?nomeTabela={$nomeTabelaAtual}&linkAPI={$linkAPI}&offset={$nextOffset}\">Próxima Página &gt;&gt;</a>";
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
    <title><?php echo $tituloPagina; ?></title>
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
    <div class="topo">
        <div class="menu-container">
            <img src="https://www.svgrepo.com/show/509382/menu.svg" alt="Menu Icon" class="menu-icon" onclick="toggleMenu()">
            <div class="menu" id="menu">
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
                        <!-- botao ainda nao esta operacional -->
                    </div>
                </div>
            </div>
        </div>
        <div class="button-container">
            <div class="custom-button">
            <?php
                if (isset($nomeTabelaAtual)) {
                    $nomeTabelaFormatado = str_replace(array(" ", "-"), "_", $nomeTabelaAtual);
                    echo "<a href='Export.php?nomeTabelaAtual=" . urlencode($nomeTabelaFormatado) . "'><button>Exportar Dados</button></a>";
                } else {
                    // Caso "nomeTabela" não esteja definido, você pode tratar isso aqui, se necessário
                    echo "Erro: Nome da tabela não definido";
                }
                ?>

                <?php
                if (isset($nomeTabelaAtual)) {
                    $nomeTabelaFormatado = str_replace(array(" ", "-"), "_", $nomeTabelaAtual);
                    echo "<a href='AnaliseDados.php?nomeTabelaAtual=" . urlencode($nomeTabelaFormatado) . "'><button>Análise</button></a>";
                } else {
                    // Caso "nomeTabela" não esteja definido, você pode tratar isso aqui, se necessário
                    echo "Erro: Nome da tabela não definido";
                }
                ?>
            </div>
        </div>

    </div>
    </div>
    <?php
    // Verificar se a variável $tituloPagina está definida
    if (isset($tituloPagina)) {
        echo "<h1>"  . $tituloPagina . "</h1>";
        // Variáveis para controle de offset e limite
        $offset = 0;
        $limit = 60;
        // Loop para buscar e inserir todos os dados
        do {
            // Obtém os dados da API
            $data = fetchData($offset, $limit, $mysqli, $nomeTabelaAtual);
            // Verifica se os dados foram obtidos corretamente e se a resposta é válida
            if (isset($data['results']) && is_array($data['results'])) {
                if ($offset == 0) {
                    // Criar tabela na primeira iteração
                    $columns = array_keys($data['results'][0]);
                    $nomeTabelaFormatado = str_replace(array(" ", "-"), "_", $nomeTabelaAtual);
                    $createTableSQL = "CREATE TABLE IF NOT EXISTS `{$nomeTabelaFormatado}` (id INT AUTO_INCREMENT PRIMARY KEY, ";
                    foreach ($columns as $column) {
                        $createTableSQL .= "`{$column}` VARCHAR(255), ";
                    }
                    $createTableSQL = rtrim($createTableSQL, ", ") . ")";
                    if (!mysqli_query($mysqli, $createTableSQL)) {
                        echo "Erro ao criar tabela: " . mysqli_error($mysqli);
                        break;
                    }
                }
                // Inserir dados na tabela
                foreach ($data['results'] as $record) {
                    $insertValues = array_map(function ($value) use ($mysqli) {
                        return mysqli_real_escape_string($mysqli, $value);
                    }, $record);
                    $insertValues = "'" . implode("','", $insertValues) . "'";

                    // Verificar se o registro já existe
                    $verifica = "SELECT * FROM `{$nomeTabelaFormatado}` WHERE ";
                    $dataOrigem = explode(",", $insertValues);
                    //$dataOrigem = explode(",", $insertValues);

                    for ($i = 0; $i < count($columns); $i++) {

                        if ($i == count($columns) - 1) {
                            $verifica .= "$columns[$i] = $dataOrigem[$i] ";
                            $verifica .= "$columns[$i] = '" . $insertValues[$columns[$i]]  . "'";
                        } else {
                            $verifica .= "$columns[$i] = $dataOrigem[$i] AND ";
                            $verifica .= "$columns[$i] = '" . $insertValues[$columns[$i]]  . "' AND";
                        }

                    }
                    $res = mysqli_query($mysqli, $verifica);

                    $rowcount = mysqli_num_rows($res);
                    if ($rowcount == 0) {
                        $insertSQL = "INSERT INTO `{$nomeTabelaFormatado}` (`" . implode("`, `", $columns) . "`) VALUES ({$insertValues})";
                        $insertSQL = "INSERT INTO `{$nomeTabelaFormatado}` (`" . implode("`, `", $columns) . "`) VALUES ({$new_insertValues})";
                        if (!mysqli_query($mysqli, $insertSQL)) {
                            echo "Erro ao inserir dados: " . mysqli_error($mysqli);
                        }
                    }
                }
                // Incrementar o offset para o próximo lote de dados
                $offset += $limit;
            } else {
                if (empty($data['results'])) {
                    echo "<p>Nenhum registro encontrado.</p>";
                } else {
                    echo "<p>Erro ao obter dados da API.</p>";
                    echo "<pre>";
                    print_r($data);
                    echo "</pre>";
                }
                break;
            }
        } while (count($data['results']) == $limit);
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
            $data = fetchData($offset, $limit, $mysqli, $nomeTabelaAtual);
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
                displayNavigationMenu($offset, $limit, $totalPages, $nomeTabelaAtual, $linkAPI); // Adicionado os parâmetros
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
                displayNavigationMenu($offset, $limit, $totalPages, $nomeTabelaAtual, $linkAPI); // Adicionado os parâmetros
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
    <script>
        function toggleMenu() {
            var menu = document.getElementById("menu");
            menu.classList.toggle("visible");
        }
    </script>
</body>

</html>