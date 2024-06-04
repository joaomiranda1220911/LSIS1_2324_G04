<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós</title>
    <link rel="icon" href="Imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">

    <style>
        .ana img,
        .joao img,
        .jose img,
        .tiago img {
            cursor: pointer;
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
    
    <main>
        <div class="quemsomos_objetivo">
            <div class="quem_somos">
                <h1>Quem somos?</h1>
                <p>Bem-vindo ao site do Grupo 4 do segundo ano da Licenciatura em Engenharia
                    de Sistemas! Somos um grupo de estudantes dedicados e apaixonados por explorar,
                    inovar e aplicar os princípios da engenharia para resolver problemas
                    complexos e desenvolver soluções eficientes.
                </p>
            </div>
            <div class="objetivo">
                <h1>O nosso objetivo</h1>
                <p>O objetivo principal deste trabalho consiste na criação de um sistema de informação para a web
                    que possibilite a extração de informações relevantes, cruzando dados do portal E-Redes com outras
                    fontes de dados. O nosso intuito é compreender a dispersão geográfica de instalações fotovoltaicas
                    em Portugal continental, analisando a quantidade e tipologias dessas instalações.
                </p>
            </div>
        </div>

        <div class="equipa">
            <h1>A nossa equipa</h1>
        </div>
        <div class="fotos_equipa">

            <div class="ana">
                <img src="Imagens/ana.jpeg" alt="Foto Ana" onclick="window.open('https://www.linkedin.com/in/ana-matos-629256255/', '_blank');">
                <p>Ana Matos<br></p>
                <p>1221035@isep.ipp.pt</p>
            </div>

            <div class="joao">
                <img src="Imagens/joao.jpeg" alt="Foto João" onclick="window.open('https://www.linkedin.com/in/-joao-miranda-/', '_blank');">
                <p>João Miranda<br></p>
                <p>1220911@isep.ipp.pt</p>
            </div>

            <div class="jose">
                <img src="Imagens/ze.jpeg" alt="Foto José" onclick="window.open('https://www.linkedin.com/in/-josé-pereira-/', '_blank');">
                <p>José Pereira<br></p>
                <p>1220944@isep.ipp.pt</p>
            </div>

            <div class="tiago">
                <img src="Imagens/tiago.jpeg" alt="Foto Tiago" onclick="window.open('https://www.linkedin.com/in/-tiago-santos-/', '_blank');">
                <p>Tiago Santos<br></p>
                <p>1221040@isep.ipp.pt</p>
            </div>
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
</body>

</html>
