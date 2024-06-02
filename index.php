<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="logo" onclick="window.location.href='index.php'">
            <img src="Imagens/casa_icon.png" alt="Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Sobre Nós</a></li>
                <li><a href="Dados.php">Dados</a></li>
                <li><a href="#">Análise</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <button class="eredes_btn" onclick="window.open('https://www.e-redes.pt/pt-pt', '_blank');">Site oficial E-redes</button>
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
            <img src="Imagens/isep_logo.png" alt="ISEP Logo">
            <p>Projeto realizado no âmbito de Laboratório de Sistemas 1</p>
        </div>
    </footer>
</body>

</html>