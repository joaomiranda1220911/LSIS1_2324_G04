<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }

        .logo img {
            height: 40px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #555;
            border-radius: 4px;
        }

        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar input[type="text"] {
            padding: 5px;
            border: none;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-bar .search-button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            background-color: #555;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar .search-button:hover {
            background-color: #777;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            height: 30px;
            margin-right: 10px;
        }

        main {
            padding: 20px;
        }

        .hero {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
        }

        .hero img {
            width: 100%;
            height: auto;
        }

        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        .dashboards {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .dashboard {
            text-align: center;
            background-color: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            margin: 10px;
        }

        .dashboard img {
            width: 100%;
            height: auto;
            border-radius: 10px 10px 0 0;
        }

        .dashboard p {
            padding: 10px;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 20px;
            margin-top: 20px;
        }

        .footer-content {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .footer-content img {
            height: 30px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="Imagens/home_icon.png" alt="Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Sobre Nós</a></li>
                <li><a href="#">Dados</a></li>
                <li><a href="#">Análise</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <a href="https://www.e-redes.pt/pt-pt" class="official-site" target="_blank">Site oficial E-redes</a>
            <input type="text" placeholder="Pesquisar">
            <button class="search-button">Ir</button>
        </div>
        <div class="user-info">
            <img src="Imagens/user_icon.png" alt="User Icon">
            <span>Name</span>
        </div>
    </header>
    <main>
        <section class="hero">
            <img src="Imagens/homepage_image.png" alt="Hero Image">
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
