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
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 3px solid #FFDC00;
            border-radius: 10px;
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

        // Função para carregar os marcadores do dataset
        function carregarMarcadores(offset) {
            // Chama a função fetchData para obter os dados da API com base no offset
            fetch(`get_data.php?offset=${offset}&limit=100&nomeTabela=postos-transformacao-distribuicao`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Erro:', data.error);
                        return;
                    }

                    console.log('Dados recebidos:', data);

                    // Adiciona os marcadores ao mapa
                    data.records.forEach(record => {
                        if (record.geometry && record.geometry.coordinates) {
                            const latitude = record.geometry.coordinates[1];
                            const longitude = record.geometry.coordinates[0];
                            const nome = record.fields.nome || "Sem Nome";
                            const descricao = record.fields.descricao || "Sem Descrição";

                            console.log(`Adicionando marcador: ${nome} (${latitude}, ${longitude})`);

                            L.marker([latitude, longitude])
                                .bindPopup(`<b>${nome}</b><br>${descricao}`)
                                .addTo(map);
                        } else {
                            console.warn('Registro sem coordenadas:', record);
                        }
                    });
                })
                .catch(error => console.error('Erro ao carregar marcadores:', error));
        }

        // Carrega os marcadores ao carregar a página
        carregarMarcadores(0);

        // Função para carregar mais marcadores incrementando o offset
        function carregarMaisMarcadores() {
            // Incrementa o offset para carregar mais registros
            const offset = 100; // Ajustar conforme necessário
            carregarMarcadores(offset);
        }
    </script>
</body>

</html>
