<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <div class="button-container">
        <div class="custom-button">
            <button onclick="window.location.href='Import.php'">Importar Dados</button>
        </div>
    </div>

    <div class="menu">
        <div class="button-container">
            <div class="custom-button">
                <button onclick="window.location.href='Import.php'">Pesquisar</button>
            </div>
        </div>
        <h2>Filtros</h2>
        <ul>
            <li><input type="checkbox" id="filtro1"><label for="filtro1">Filtro 1</label></li>
            <li><input type="checkbox" id="filtro2"><label for="filtro2">Filtro 2</label></li>
            <li><input type="checkbox" id="filtro3"><label for="filtro3">Filtro 3</label></li>
        </ul>
    </div>
    </div>
    <main class="l">
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
</body>

</html>