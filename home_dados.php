<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Lateral com Filtros</title>
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
                        <input type="checkbox" id="Consumos e Energia">
                        <label for="Consumos e Energia">Consumos e Energia</label>
                    </li>
                    <li>
                        <input type="checkbox" id="Mobilidade Elétrica">
                        <label for="Mobilidade Elétrica">Mobilidade Elétrica</label>
                    </li>
                    <li>
                        <input type="checkbox" id="Operação e Qualidade de Serviço">
                        <label for="Operação e Qualidade de Serviço">Operação e Qualidade de Serviço</label>
                    </li>
                    <li>
                        <input type="checkbox" id="Rede Elétrica">
                        <label for="Rede Elétrica">Rede Elétrica</label>
                    </li>
                    <li>
                        <input type="checkbox" id="Renováveis">
                        <label for="Renováveis">Renováveis</label>
                    </li>
                </ul>
                <div class="button-container">
                    <div class="custom-button">
                        <button onclick="window.location.href='Import.php'">Pesquisar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="button-container">
            <div class="custom-button">
                <button onclick="window.location.href='Import.php'">Importar Dados</button>
            </div>
        </div>
    </div>

    <main>
        <section class="dataset-details">
            <h2>Título</h2>
            <div class="dataset-info">
                <p><strong>Tags:</strong> tag1, tag2, tag3</p>
                <p><strong>Tipo de Importação:</strong> Tipo A</p>
                <p><strong>Número de Dados:</strong> 1000</p>
                <p><strong>Dashboards que utilizam este dataset:</strong></p>
                <ul>
                    <li>Dashboard 1</li>
                    <li>Dashboard 2</li>
                </ul>
            </div>
        </section>
        <section class="dataset-details">
            <h2>Título</h2>
            <div class="dataset-info">
                <p><strong>Tags:</strong> tag1, tag2, tag3</p>
                <p><strong>Tipo de Importação:</strong> Tipo B</p>
                <p><strong>Número de Dados:</strong> 2000</p>
                <p><strong>Dashboards que utilizam este dataset:</strong></p>
                <ul>
                    <li>Dashboard 3</li>
                    <li>Dashboard 4</li>
                </ul>
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


    <script src="script.js"></script>
</body>

</html>