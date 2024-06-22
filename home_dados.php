<?php
// Incluir o arquivo de configuração da conexão com o banco de dados
include("ImportSQL.php");

// Verificar se a sessão já está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Definir um nome padrão
$nome_utilizador = "Visitante";

// Definir uma variável de permissão de usuário padrão
$permissao_utilizador = "Visitante";

// Verificar se o usuário está logado
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Query para selecionar o nome e permissão do usuário
    $sql = "SELECT nome, permissao FROM utilizador WHERE email = '$email'";
    $result = mysqli_query($mysqli, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $nome_utilizador = $row['nome'];
        $permissao_utilizador = $row['permissao'];
    } else {
        $nome_utilizador = "Visitante";
        echo "Erro na consulta SQL: " . mysqli_error($mysqli);
    }
}

// Fechar a conexão com o banco de dados (não é necessário neste ponto)
//$mysqli->close();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dados</title>
    <link rel="icon" href="Imagens/favicon.ico" type="image/x-icon">
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
        <form action="search.php" method="GET" class="search-bar">
            <input type="text" name="query" placeholder="Pesquisar">
            <button type="submit" class="search-button">
                <img src="Imagens/search_icon.png" alt="ir">
            </button>
        </form>

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
                <form id="tagsForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> <!-- Adicionando um formulário para enviar os dados -->
                    <ul>
                        <li>
                            <input type="checkbox" id="Consumos e Energia" name="tags[]" value="Consumos e Energia">
                            <label for="Consumos e Energia">Consumos e Energia</label>
                        </li>
                        <li>
                            <input type="checkbox" id="Mobilidade Elétrica" name="tags[]" value="Mobilidade Elétrica">
                            <label for="Mobilidade Elétrica">Mobilidade Elétrica</label>
                        </li>
                        <li>
                            <input type="checkbox" id="Operação e Qualidade de Serviço" name="tags[]" value="Operação e Qualidade de Serviço">
                            <label for="Operação e Qualidade de Serviço">Operação e Qualidade de Serviço</label>
                        </li>
                        <li>
                            <input type="checkbox" id="Rede Elétrica" name="tags[]" value="Rede Elétrica">
                            <label for="Rede Elétrica">Rede Elétrica</label>
                        </li>
                        <li>
                            <input type="checkbox" id="Renováveis" name="tags[]" value="Renováveis">
                            <label for="Renováveis">Renováveis</label>
                        </li>
                        <li>
                            <input type="checkbox" id="Externo" name="tags[]" value="Externo">
                            <label for="Externo">Externo</label>
                        </li>
                    </ul>
                    <div class="button-container">
                        <div class="custom-button">
                            <button type="submit">Pesquisar</button>
                        </div>
                        <div class="custom-button">
                            <button onclick="window.location.href='home_dados.php'">Limpar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="button-container">
            <div class="custom-button">
                <?php
                // Verificar se o usuário tem permissão para acessar a página de importação
                if ($permissao_utilizador == 'Admin' || $permissao_utilizador == 'Colaborador E-Redes') {
                    echo '<button onclick="window.location.href=\'formImport.php\'">Importar Dados</button>';
                } else {
                    echo '<button onclick="alert(\'Você não tem permissão para acessar esta página.\')">Importar Dados</button>';
                }
                ?>
            </div>
        </div>
    </div>

    <main>
        <?php
        // Verificar se os filtros foram enviados via POST
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tags'])) {
            // Obtendo os filtros selecionados
            $selected_tags = $_POST['tags'];

            // Preparar a consulta SQL
            $sql = "SELECT * FROM dataset WHERE ";
            $conditions = [];

            // Adicionar as condições para cada filtro selecionado
            foreach ($selected_tags as $tag) {
                $conditions[] = "tags LIKE '%" . $mysqli->real_escape_string($tag) . "%'";
            }

            // Juntar as condições com 'OR'
            $sql .= implode(" OR ", $conditions);

            // Executar a consulta
            $result = $mysqli->query($sql);

            // Verificar se a consulta retornou resultados
            if ($result->num_rows > 0) {
                // Inicializar um array para armazenar os resultados
                $output = [];

                // Loop através dos resultados e adicioná-los ao array de saída
                while ($row = $result->fetch_assoc()) {
                    $output[] = $row;
                }

                // Exibir os resultados como HTML
                echo "<div class='lista-datasets'>";

                foreach ($output as $row) {
                    echo "<section class='dataset-details'>";
                    // Adicionando parâmetros de nome e linkAPI à URL do link
                    echo "<a href='dados.php?nomeTabela=" . urlencode($row["nomeTabela"]) . "&linkAPI=" . urlencode($row["linkAPI"]) . "'> <h2 class='titulo'>" . htmlspecialchars($row["nomeTabela"]) . "</h2></a>";
                    echo "<div class='dataset-info'>";
                    echo "<p><strong>Tags:</strong> " . htmlspecialchars($row["tags"]) . "</p>";
                    echo "<p><strong>Tipo de Importação:</strong> " . htmlspecialchars($row["tipoImportacao"]) . "</p>";
                    echo "<p><strong>Número de Dados:</strong> " . htmlspecialchars($row["numeroLinhas"]) . "</p>";
                    echo "<p><strong>Informação sobre os dados:</strong> " . htmlspecialchars($row["informacao"]) . "</p>";
                    echo "</div>";
                    echo "</section>";
                }

                echo "</div>"; // Fechar a div lista-datasets após o loop
            } else {
                // Se não houver resultados, exibir uma mensagem
                echo "<p>Nenhum resultado encontrado</p>";
            }
            // Fechar a conexão com a base de dados
            $mysqli->close();
        } else {
            // Consulta SQL para buscar os dados
            $sql = "SELECT nomeTabela, tags, numeroLinhas, tipoImportacao, informacao, linkAPI FROM dataset";
            $result = mysqli_query($mysqli, $sql);

            if ($result) {
                // Exibir os dados encontrados
                echo "<div class='lista-datasets'>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<section class='dataset-details'>";
                    // Adicionando parâmetros de nome e linkAPI à URL do link
                    echo "<a href='dados.php?nomeTabela=" . urlencode($row["nomeTabela"]) . "&linkAPI=" . urlencode($row["linkAPI"]) . "'> <h2 class='titulo'>" . htmlspecialchars($row["nomeTabela"]) . "</h2></a>";
                    echo "<div class='dataset-info'>";
                    echo "<p><strong>Tags:</strong> " . htmlspecialchars($row["tags"]) . "</p>";
                    echo "<p><strong>Tipo de Importação:</strong> " . htmlspecialchars($row["tipoImportacao"]) . "</p>";
                    echo "<p><strong>Número de Dados:</strong> " . htmlspecialchars($row["numeroLinhas"]) . "</p>";
                    echo "<p><strong>Informação sobre os dados:</strong> " . htmlspecialchars($row["informacao"]) . "</p>";
                    echo "</div>";
                    echo "</section>";
                }

                echo "</div>"; // Fechar a div lista-datasets após o loop
            } else {
                echo "Erro na consulta: " . mysqli_error($mysqli);
            }
        }
        ?>
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

    <script src="script.js"></script>
    <script>
        function toggleMenu() {
            var menu = document.getElementById("menu");
            menu.classList.toggle("visible");
        }
    </script>
</body>

</html>