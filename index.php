<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
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
                <button><a href="Dados.php">Dados</a></button>
                <button><a href="Analise.php">Análise</a></button>
            </div>
        </nav>
        <div class="search-bar">
            <input type="text" placeholder="Pesquisar">
            <button class="search-button">Ir</button>
        </div>
        <div class="dropdown">
            <div class="user-info">
                <img src="Imagens/user_icon.png" alt="User Icon">
                <span>Name</span>
                <div class="dropdown-content">
                    <a href="Login.php">Login</a>
                    <a href="Register.php">Registo</a>
                    <a href="#">Perfil</a>
                    <a href="#">Sair</a>
                </div>
            </div>
    </header>
    <main>
        <section class="hero">
            <img src="Imagens/paineis.png" alt="Hero Image">
            <div class="hero-text">
                <h1>Principais estatísticas descobertas</h1>
            </div>
        </section>
        <section class="dashboards">
            <div class="dashboard">
                <img src="Imagens/dashboard1.png" alt="Dashboard 1">
                <p>Informação sobre a dashboard</p>
            </div>
            <div class="dashboard">
                <img src="Imagens/dashboard2.png" alt="Dashboard 2">
                <p>Informação sobre a dashboard</p>
            </div>
        </section>
    </main>
    <footer>
        <div class="footer-content">
            <div class="footer-left">
                <img src="Imagens/isep_logo.png" alt="ISEP Logo" class="isep_img" onclick="window.open('https://www.isep.ipp.pt', '_blank');">
                <img src="Imagens/e-redes.jpg" alt="E-Redes Logo" class="eredes_img" onclick="window.open('https://www.e-redes.pt/pt-pt', '_blank');">
            </div>
            <div class="footer-right">
                <p>Projeto realizado no âmbito de Laboratório de Sistemas 1</p>
            </div>
        </div>
    </footer>

</body>

</html>