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
        <form action="search.php" method="GET" class="search-bar">
            <input type="text" name="query" placeholder="Pesquisar">
            <button type="submit" class="search-button">
                <img src="Imagens/search_icon.png" alt="ir">
            </button>
        </form>
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
            $sql = "SELECT nome FROM utilizador WHERE email = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $row = $result->fetch_assoc()) {
                    $nome_utilizador = $row['nome'];
                }
                $stmt->close();
            }
        } else {
            $nome_utilizador = "Visitante";
        }
        ?>

        <div class="dropdown">
            <button class="user-info">
                <img src="Imagens/user_icon.png" alt="User Icon">
                <span><?php echo htmlspecialchars($nome_utilizador); ?></span>
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
    // Include the database connection file
    include 'ImportSQL.php';

    // Function to fetch all tables
    function getTables($mysqli)
    {
        $tables = [];
        $result = $mysqli->query("SHOW TABLES");
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
        return $tables;
    }

    // Function to fetch all columns from a specific table
    function getColumns($mysqli, $table)
    {
        $columns = [];
        $result = $mysqli->query("SHOW COLUMNS FROM `$table`");
        while ($row = $result->fetch_array()) {
            $columns[] = $row['Field'];
        }
        return $columns;
    }

    // Get the search query
    if (isset($_GET['query'])) {
        $query = htmlspecialchars($_GET['query']);
        $searchTerm = "%" . $query . "%";
        $tables = getTables($mysqli);
        $resultsFound = false;

        foreach ($tables as $table) {
            $tableNameDisplay = str_replace("_", " ", $table); // Replace underscores with spaces

            $columns = getColumns($mysqli, $table);
            foreach ($columns as $column) {
                // Skip email and password columns
                if (in_array($column, ['email', 'password'])) {
                    continue;
                }

                $sql = "SELECT * FROM `$table` WHERE `$column` LIKE ? LIMIT 35"; // Limit to 35 rows
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("s", $searchTerm);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $resultsFound = true;
                        echo "<h2>Resultados da pesquisa na tabela '$tableNameDisplay', coluna '$column' para a pesquisa: '$query':</h2>";
                        echo "<table border='1'>";
                        echo "<tr>";
                        foreach ($result->fetch_fields() as $field) {
                            // Skip email and password columns
                            if (in_array($field->name, ['email', 'password'])) {
                                continue;
                            }
                            echo "<th>" . htmlspecialchars($field->name) . "</th>";
                        }
                        echo "</tr>";
                        $rowCount = 0;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $key => $cell) {
                                // Skip email and password columns
                                if (in_array($key, ['email', 'password'])) {
                                    continue;
                                }
                                echo "<td>" . htmlspecialchars($cell) . "</td>";
                            }
                            echo "</tr>";
                            $rowCount++;
                        }
                        echo "</table>";

                        if ($rowCount >= 20) {
                            echo "<p style='text-align: center;'><strong>Mais do que 20 resultados encontrados. Melhore a sua pesquisa.</strong></p>";
                        }
                    }

                    $stmt->close();
                } else {
                    echo "Erro ao preparar a declaração para a tabela '$table', coluna '$column'.<br>";
                }
            }
        }

        if (!$resultsFound) {
            echo "<p>Sem resultados para: '" . htmlspecialchars($query) . "'</p>";
        }
    }

    $mysqli->close();
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

</html>
