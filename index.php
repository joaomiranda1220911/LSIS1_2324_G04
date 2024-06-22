<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
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
    <main>
        <section class="hero">
            <img src="Imagens/paineis.png" alt="Hero Image">
            <div class="hero-text">
                <h1 style="font-size: 30px">Principais Fontes de Dados</h1>
                <div class="hero-images">
                    <img src="Imagens/opendata.png" alt="E-Redes Logo" class="eredes_img" onclick="window.open('https://e-redes.opendatasoft.com/pages/homepage/', '_blank');">
                    <img src="Imagens/pordata.jpg" alt="PORDATA Logo" class="pordata_img" onclick="window.open('https://www.pordata.pt/', '_blank');">
                    <img src="Imagens/ine.jpeg" alt="INE Logo" class="ine_img" onclick="window.open('https://www.ine.pt/xportal/xmain?xpid=INE&xpgid=ine_main', '_blank');">
                </div>
            </div>
        </section>
        <section class="dashboards">
            <div class="dashboard">
                <iframe src="https://e-redes.opendatasoft.com/explore/embed/dataset/16-pedidos-concluidos-plrs/analyze/?dataChart=eyJxdWVyaWVzIjpbeyJjaGFydHMiOlt7InR5cGUiOiJjb2x1bW4iLCJmdW5jIjoiU1VNIiwieUF4aXMiOiJwZWRpZG9zX2RlX2xpZ2FjYW9fYV9yZWRlX2V4ZWN1dGFkb3MiLCJzY2llbnRpZmljRGlzcGxheSI6dHJ1ZSwiY29sb3IiOiIjRkZEQzAwIn1dLCJ4QXhpcyI6ImNvbmNlbGhvIiwibWF4cG9pbnRzIjoyMCwic29ydCI6InNlcmllMS0xIiwiY29uZmlnIjp7ImRhdGFzZXQiOiIxNi1wZWRpZG9zLWNvbmNsdWlkb3MtcGxycyIsIm9wdGlvbnMiOnt9fX1dLCJ0aW1lc2NhbGUiOiIiLCJkaXNwbGF5TGVnZW5kIjp0cnVlLCJhbGlnbk1vbnRoIjp0cnVlfQ%3D%3D&static=false&datasetcard=true" width="600" height="450" frameborder="0"></iframe>
            </div>
            <div class="dashboard">
                <iframe src="https://e-redes.opendatasoft.com/explore/embed/dataset/26-centrais/analyze/?dataChart=eyJxdWVyaWVzIjpbeyJjaGFydHMiOlt7InR5cGUiOiJjb2x1bW4iLCJmdW5jIjoiU1VNIiwieUF4aXMiOiJwcm9jZXNzb3NfY29uY2x1aWRvcyIsInNjaWVudGlmaWNEaXNwbGF5Ijp0cnVlLCJjb2xvciI6IiNGRkRDMDAifV0sInhBeGlzIjoiYW5vIiwibWF4cG9pbnRzIjpudWxsLCJzb3J0IjoiIiwiY29uZmlnIjp7ImRhdGFzZXQiOiIyNi1jZW50cmFpcyIsIm9wdGlvbnMiOnt9fX1dLCJ0aW1lc2NhbGUiOiIiLCJkaXNwbGF5TGVnZW5kIjp0cnVlLCJhbGlnbk1vbnRoIjp0cnVlfQ%3D%3D&static=false&datasetcard=true" width="600" height="450" frameborder="0"></iframe>
            </div>
        </section>
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