<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Instalações Solares</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link rel="stylesheet" href="styles.css">
<style>
    #map {
        height: 600px;
        width: 700px;
        position: absolute;
        top: 50%; /* Center the map vertically */
        left: 50%; /* Center the map horizontally */
        transform: translate(-50%, -50%); /* Apply the centering */
        border: 12px solid #FFDC00; /* Add a yellow border */
        border-radius: 10px; /* Round the corners */
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
    }

    .footer-content {
        position: fixed;
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

    <div id="map"></div>

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
        // Inicializa o mapa
        const map = L.map('map').setView([41.17820882715362, -8.608457297299513], 12); // Coordenadas centrais de Portugal

        // Adiciona a camada de mapa do OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Objeto para armazenar as coordenadas dos concelhos
        const concelhosCoords = {};

        // Função para carregar as coordenadas dos concelhos de Portugal
        async function loadConcelhosCoords() {
            const response = await fetch('concelhos.csv');
            const data = await response.text();
            Papa.parse(data, {
                header: true,
                complete: function (results) {
                    results.data.forEach(row => {
                        concelhosCoords[row.Concelho] = {
                            latitude: parseFloat(row.Latitude),
                            longitude: parseFloat(row.Longitude)
                        };
                    });
                }
            });
        }

        // Função para adicionar marcadores ao mapa
        async function addMarkers() {
            await loadConcelhosCoords();

            // Processa o ficheiro CSV com os dados das instalações solares
            Papa.parse('concelhos.csv', {
                header: true,
                delimiter: ';',
                complete: function (results) {
                    const data = results.data;

                    // Processa cada linha do CSV
                    data.forEach(row => {
                        const concelho = row.Concelho;
                        const numeroPaineis = parseInt(row['Número de instalacões']);

                        // Verifica se há coordenadas para o concelho
                        if (concelhosCoords[concelho]) {
                            const coords = concelhosCoords[concelho];

                            // Adiciona marcador ao mapa
                            L.marker([coords.latitude, coords.longitude]).addTo(map)
                                .bindPopup(`${concelho}: ${numeroPaineis} painéis solares`);
                        }
                    });
                }
            });
        }

        // Chama a função para adicionar marcadores
        addMarkers();
    </script>
</body>

</html>
