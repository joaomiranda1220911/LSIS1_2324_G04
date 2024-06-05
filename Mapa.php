<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="icon" href="Imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
            width: 100%;
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
        <div id="map"></div>
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
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script>
        // Inicializa o mapa e define a vista inicial para o centro de Portugal
        var map = L.map('map').setView([39.5, -8], 7);

        // Adiciona a camada do mapa OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Função para adicionar marcadores ao mapa a partir de dados CSV
        function addMarkers(data) {
            data.forEach(function (row) {
                const municipio = row[0];
                const latitude = parseFloat(row[1]);
                const longitude = parseFloat(row[2]);
                const instalacoes = parseInt(row[3]);

                if (!isNaN(latitude) && !isNaN(longitude)) {
                    const marker = L.marker([latitude, longitude]).addTo(map);
                    marker.bindPopup(`<b>${municipio}</b><br>Número de Instalações: ${instalacoes}`);
                }
            });
        }

        // Leitura do CSV de municípios e adição dos marcadores ao mapa
        Papa.parse('cidades.csv', {
            download: true,
            delimiter: ';',
            complete: function (results) {
                addMarkers(results.data);
            }
        });

        // Leitura do CSV de instalações fotovoltaicas e adição dos marcadores ao mapa
        Papa.parse('paineis_fotovoltaicos.csv', {
            download: true,
            delimiter: ';',
            complete: function (results) {
                addMarkers(results.data);
            }
        });
    </script>
</body>

</html>
