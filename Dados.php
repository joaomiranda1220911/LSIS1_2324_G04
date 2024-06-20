<?php
include("ImportSQL.php");

if (isset($_GET['nomeTabela']) && isset($_GET['linkAPI'])) {
    $nomeTabelaAtual = htmlspecialchars($_GET['nomeTabela']);
    $linkAPI = htmlspecialchars($_GET['linkAPI']);
    $tituloPagina = "Dados - " . $nomeTabelaAtual;
} else {
    die('Parâmetros necessários não foram passados pela URL.');
}

function fetchData($offset, $limit, $mysqli, $nomeTabelaAtual)
{
    $sql = "SELECT linkAPI FROM dataset WHERE nomeTabela = '{$nomeTabelaAtual}'";
    $result = mysqli_query($mysqli, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $url = $row['linkAPI'];

            if (!empty($url)) { // Verifica se linkAPI não está vazio
                $url = str_replace('{$limit}', $limit, $url);
                $url = str_replace('{$offset}', $offset, $url);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                $response = curl_exec($ch);

                if (curl_errno($ch)) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    if (strpos($error_message, 'Could not resolve host') !== false) {
                        die("Erro ao conectar à API: URL inválido ou host não encontrado.");
                    } else {
                        die("Erro cURL: " . $error_message);
                    }
                }

                curl_close($ch);

                if ($response === false) {
                    die("Erro ao obter resposta da API.");
                }

                $data = json_decode($response, true);
                if ($data === null) {
                    die("Erro ao decodificar a resposta da API.");
                }

                return $data;
            } else {
                die("A coluna linkAPI está vazia. Não é possível importar dados da API.");
            }
        } else {
            // Caso não haja URL definido na coluna linkAPI
            die("A coluna linkAPI está vazia. Não é possível importar dados da API.");
        }
    } else {
        die('Erro na consulta SQL: ' . mysqli_error($mysqli));
    }
}



function displayNavigationMenu($offset, $limit, $totalPages, $nomeTabelaAtual, $linkAPI)
{
    echo "<div class='pagination'>";
    if ($offset > 0) {
        $prevOffset = max(0, $offset - $limit);
        echo "<a href=\"?nomeTabela={$nomeTabelaAtual}&linkAPI={$linkAPI}&offset={$prevOffset}\">&lt;&lt; Página Anterior</a>";
    }
    $currentPage = ($offset / $limit) + 1;
    echo " Página {$currentPage} de {$totalPages} ";
    $nextOffset = $offset + $limit;
    echo "<a href=\"?nomeTabela={$nomeTabelaAtual}&linkAPI={$linkAPI}&offset={$nextOffset}\">Próxima Página &gt;&gt;</a>";
    echo "</div>";
}

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
                    </div>
                </div>
            </div>
        </div>
        <div class="button-container">
            <div class="custom-button">
                <?php
                $nomeTabelaFormatado = str_replace(array(" ", "-"), "_", $nomeTabelaAtual);
                echo "<a href='Export.php?nomeTabelaAtual=" . urlencode($nomeTabelaFormatado) . "'><button>Exportar Dados</button></a>";
                echo "<a href='AnaliseDados.php?nomeTabelaAtual=" . urlencode($nomeTabelaFormatado) . "'><button>Análise</button></a>";
                ?>
            </div>
        </div>
    </div>

    <?php
    if (isset($tituloPagina)) {
        echo "<h1>" . $tituloPagina . "</h1>";
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        $limit = 60;

        // Verifica se a coluna tipoImportacao é igual a "API"
        $checkAPI = isset($_GET['tipoImportacao']) && $_GET['tipoImportacao'] == 'API';

        if ($checkAPI) {
            $data = fetchData($offset, $limit, $mysqli, $nomeTabelaAtual);
            if (isset($data['results']) && is_array($data['results'])) {
                if ($offset == 0 && !empty($data['results'])) {
                    $columns = array_keys($data['results'][0]); // Inicializa $columns com os nomes das colunas
                }
                // Loop foreach para inserção de dados
                foreach ($data['results'] as $record) {
                    if (isset($columns) && is_array($columns)) {
                        $insertValues = array_map(function ($value) use ($mysqli) {
                            return mysqli_real_escape_string($mysqli, $value);
                        }, $record);
                        $insertValues = "'" . implode("','", $insertValues) . "'";

                        $verifica = "SELECT * FROM `{$nomeTabelaFormatado}` WHERE ";
                        $dataOrigem = explode(",", $insertValues);
                        for ($i = 0; $i < count($columns); $i++) {
                            $verifica .= "`{$columns[$i]}` = {$dataOrigem[$i]}";
                            if ($i < count($columns) - 1) {
                                $verifica .= " AND ";
                            }
                        }
                        $res = mysqli_query($mysqli, $verifica);

                        if (mysqli_num_rows($res) == 0) {
                            $insertSQL = "INSERT INTO `{$nomeTabelaFormatado}` (`" . implode("`, `", $columns) . "`) VALUES ({$insertValues})";
                            if (!mysqli_query($mysqli, $insertSQL)) {
                                echo "Erro ao inserir dados: " . mysqli_error($mysqli);
                            }
                        }
                    }
                }

                $offset += $limit;

                // Exibição da tabela
                echo '<main>';
                echo '<div class="table-container">';
                if (isset($data['total_count'])) {
                    $totalRecords = intval($data['total_count']);
                    $totalPages = ceil($totalRecords / $limit);
                    displayNavigationMenu($offset, $limit, $totalPages, $nomeTabelaAtual, $linkAPI);
                } else {
                    echo "<p>Nenhum registro encontrado.</p>";
                }

                if (isset($data['results']) && is_array($data['results'])) {
                    echo "<table>";
                    echo "<thead><tr>";
                    foreach ($data['results'][0] as $key => $value) {
                        echo "<th>" . htmlspecialchars($key) . "</th>";
                    }
                    echo "</tr></thead><tbody>";
                    foreach ($data['results'] as $record) {
                        echo "<tr>";
                        foreach ($record as $key => $value) {
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                    displayNavigationMenu($offset, $limit, $totalPages, $nomeTabelaAtual, $linkAPI);
                } else {
                    echo "<p>Nenhum registro encontrado.</p>";
                }

                echo '</div>';
                echo '</main>';
            } else {
                echo "<p>Nenhum registro encontrado.</p>";
            }
        } else {
            // Se não for 'API', apenas exibe a tabela da base de dados com o nome recebido
            $nomeTabelaFormatado = mysqli_real_escape_string($mysqli, $nomeTabelaAtual);
            $query = "SELECT * FROM `{$nomeTabelaFormatado}` LIMIT {$offset}, {$limit}";
            $result = mysqli_query($mysqli, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<main>';
                echo '<div class="table-container">';
                echo "<table>";
                $row = mysqli_fetch_assoc($result);
                echo "<thead><tr>";
                foreach ($row as $key => $value) {
                    echo "<th>" . htmlspecialchars($key) . "</th>";
                }
                echo "</tr></thead><tbody>";

                // Exibição dos dados
                mysqli_data_seek($result, 0); // Retorna o ponteiro do resultado para o início
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }

                echo "</tbody></table>";
                displayNavigationMenu($offset, $limit, $totalPages, $nomeTabelaAtual, $linkAPI);
                echo '</div>';
                echo '</main>';
            } else {
                echo "<p>Nenhum registro encontrado.</p>";
            }
        }
    }
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
    <script>
        function toggleMenu() {
            var menu = document.getElementById("menu");
            menu.classList.toggle("visible");
        }
    </script>
</body>

</html>