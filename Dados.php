<?php
// Incluir o arquivo de configuração da conexão com o banco de dados
include("ImportSQL.php");

if (isset($_GET['nomeTabela']) && isset($_GET['linkAPI'])) {
    $nomeTabelaAtual = htmlspecialchars($_GET['nomeTabela']);
    $linkAPI = htmlspecialchars($_GET['linkAPI']); // Adicionado

    // Definir o título da página com base no nome da tabela atual
    $tituloPagina = "Dados - " . $nomeTabelaAtual;
} else {
    die('Parâmetros necessários não foram passados pela URL.');
}

// Verificar se a sessão já está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
            curl_setopt($ch, CURLOPT_TIMEOUT, 180); // Tempo limite total da operação em segundos
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
    <div class="topo">
        <div class="menu-container">
            <img src="https://www.svgrepo.com/show/509382/menu.svg" alt="Menu Icon" class="menu-icon" onclick="toggleMenu()">
            <div class="menu" id="menu">
                <h2>Filtros</h2>
                <?php
                $nomeTabelaFormatado = str_replace(array(" ", "-"), "_", $nomeTabelaAtual);
                $checkTableSQL = "SHOW TABLES LIKE '{$nomeTabelaFormatado}'";
                $checkResult = mysqli_query($mysqli, $checkTableSQL);

                if (mysqli_num_rows($checkResult) == 0) {
                    die("A tabela {$nomeTabelaFormatado} não existe.");
                } else {
                    $sqla = "SHOW COLUMNS FROM `{$nomeTabelaFormatado}`";
                    $resultas = mysqli_query($mysqli, $sqla);
                    $colunas = array();

                    if ($resultas) {
                        while ($row = mysqli_fetch_assoc($resultas)) {
                            $colunas[] = $row['Field'];
                        }
                    } else {
                        echo "Erro ao obter as colunas da tabela: " . mysqli_error($mysqli);
                    }
                }
                ?>
                <ul class="menu-lista">
                    <?php foreach ($colunas as $coluna) : ?>
                        <li>
                            <div class="dropdown">
                                <a href="#" class="dropbtn"><?php echo ucwords(str_replace('_', ' ', $coluna)); ?></a>
                                <div class="dropdown-content">
                                    <a href='?nomeTabela=<?php echo $nomeTabelaAtual; ?>&linkAPI=<?php echo $linkAPI; ?>&order=<?php echo $coluna; ?>&direction=ASC'>Ordenação Ascendente</a>
                                    <a href='?nomeTabela=<?php echo $nomeTabelaAtual; ?>&linkAPI=<?php echo $linkAPI; ?>&order=<?php echo $coluna; ?>&direction=DESC'>Ordenação Descendente</a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="button-container">
            <div class="custom-button">
                <button onclick="window.location.href='Export.php'">Exportar Dados</button>
                <?php
                if (isset($nomeTabelaAtual)) {
                    $nomeTabelaFormatado = str_replace(array(" ", "-"), "_", $nomeTabelaAtual);
                    echo "<a href='AnaliseDados.php?nomeTabelaAtual=" . urlencode($nomeTabelaFormatado) . "'><button>Análise</button></a>";
                } else {
                    // Caso "nomeTabela" não esteja definido, você pode tratar isso aqui, se necessário
                    echo "Erro: Nome da tabela não definido";
                }
                ?>
            
            <button onclick="window.location.href='Mapa.php'">Mapa</button>
            <!-- nao esta iterativo -->
            </div>
        </div>
    </div>

    <?php
    // Configurações iniciais
    echo "<h1>"  . $tituloPagina . "</h1>";
    $limit = 35; // Número de registros a serem exibidos por vez
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0; // Ponto de início inicial

    // Obter parâmetros de ordenação
    $order = isset($_GET['order']) ? $_GET['order'] : 'id'; // Ordenar por 'id' por padrão
    $direction = isset($_GET['direction']) && in_array($_GET['direction'], ['ASC', 'DESC']) ? $_GET['direction'] : 'ASC';

    // Consulta SQL para obter dados da tabela com ordenação
    $sql = "SELECT * FROM `{$nomeTabelaFormatado}` ORDER BY {$order} {$direction} LIMIT {$offset}, {$limit}";
    $result = mysqli_query($mysqli, $sql);

    // Verificar se a consulta foi bem-sucedida
    if ($result && mysqli_num_rows($result) > 0) {
        // Obter o número total de registros
        $countSQL = "SELECT COUNT(*) AS total FROM `{$nomeTabelaFormatado}`";
        $countResult = mysqli_query($mysqli, $countSQL);
        $totalRecords = mysqli_fetch_assoc($countResult)['total'];

        // Função para calcular o número total de páginas
        function getTotalPages($totalRecords, $limit)
        {
            return ceil($totalRecords / $limit);
        }

        // Obtém o número total de páginas
        $totalPages = getTotalPages($totalRecords, $limit);

        // Exibe o menu de navegação com o número total de páginas
        displayNavigationMenu($offset, $limit, $totalPages, $nomeTabelaAtual, $linkAPI);

        echo "<div class='table-container'>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        // Criar cabeçalhos da tabela com base nos nomes das colunas
        $colunas = mysqli_fetch_fields($result);
        foreach ($colunas as $coluna) {
            echo "<th>" . htmlspecialchars($coluna->name) . "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        // Iterar sobre os registros
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";

        // Exibir o menu de navegação
        displayNavigationMenu($offset, $limit, $totalPages, $nomeTabelaAtual, $linkAPI);
    } else {
        echo "<p>Nenhum registro encontrado ou erro na consulta SQL.</p>";
        if ($result === false) {
            echo "<p>Erro: " . mysqli_error($mysqli) . "</p>";
        }
    }

    // Fechar a conexão com o banco de dados
    mysqli_close($mysqli);
    ?>
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
<script>
    function toggleMenu() {
        var menu = document.getElementById("menu");
        menu.classList.toggle("visible");
    }
</script>

</html>