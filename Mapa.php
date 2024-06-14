<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Instalações Solares</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 0;
            position: relative;
        }

        #map-container {
            border: 3px solid #FFDC00;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
            text-align: center;
            padding: 10px;
            background: #FFDC00;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        #map {
            height: 500px;
            width: 1000px;
        }

        .footer-content {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #FFDC00;
            bottom: 0;
        }

        .titulo_mapa {
            margin: 0 0 10px 0;
        }

        .titulo_mapa h1 {
            margin: 0;
        }

        .menu-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .menu-icon {
            cursor: pointer;
        }

        .menu {
            display: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
        }

        .menu.visible {
            display: block;
        }

        select {
            width: 100%;
            padding: 8px;
            font-size: 13px;
            border: 1px solid black;
            border-radius: 5px;
            background: #FFDC00;
            cursor: pointer;
        }

        select:focus {
            border-color: #FFDC00;
            outline: none;
            box-shadow: 0 0 5px rgba(255, 220, 0, 0.5);
        }

        select option {
            padding: 10px;
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
    <div class="main-content">
        <div id="map-container">
            <div class="titulo_mapa">
                <h1>Postos de Transformação Distribuição (PTD)</h1>
            </div>
            <div id="map"></div>
        </div>
        <div class="menu-container">
            <img src="https://www.svgrepo.com/show/509382/menu.svg" alt="Menu Icon" class="menu-icon" onclick="toggleMenu()">
            <div class="menu" id="menu">
                <h2>Filtros</h2>
                <select id="districtSelect" onchange="centralizarMapa(this.value)">
                    <option value="" disabled selected>Selecione um distrito</option>
                    <option value="Aveiro">Aveiro</option>
                    <option value="Beja">Beja</option>
                    <option value="Braga">Braga</option>
                    <option value="Braganca">Bragança</option>
                    <option value="CasteloBranco">Castelo Branco</option>
                    <option value="Coimbra">Coimbra</option>
                    <option value="Evora">Évora</option>
                    <option value="Faro">Faro</option>
                    <option value="Guarda">Guarda</option>
                    <option value="Leiria">Leiria</option>
                    <option value="Lisboa">Lisboa</option>
                    <option value="Portalegre">Portalegre</option>
                    <option value="Porto">Porto</option>
                    <option value="Santarem">Santarém</option>
                    <option value="Setubal">Setúbal</option>
                    <option value="Viana do Castelo">Viana do Castelo</option>
                    <option value="VilaReal">Vila Real</option>
                    <option value="Viseu">Viseu</option>
                </select>
            </div>
        </div>
    </div>
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
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <script>
        // Inicializa o mapa
        const map = L.map('map').setView([41.17820882715362, -8.608457297299513], 12); // Coordenadas centrais de Portugal

        // Adiciona a camada de mapa do OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Cria um novo grupo de clusters
        const clusters = L.markerClusterGroup();

        // Adiciona o grupo de clusters ao mapa
        map.addLayer(clusters);

        // Função para carregar os marcadores do ficheiro CSV
        function carregarMarcadores() {
            Papa.parse("postos-transformacao-distribuicao.csv", {
                download: true,
                header: true,
                complete: function(results) {
                    console.log('Dados recebidos:', results.data);

                    // Adiciona os marcadores ao grupo de clusters
                    results.data.forEach(record => {
                        if (record["Coordenadas Geográficas"]) {
                            const [latitude, longitude] = record["Coordenadas Geográficas"].split(',').map(coord => parseFloat(coord.trim()));
                            const nivelUtilizacao = record["Nível de Utilização [%]"] || "Sem Informação";
                            const potenciaInstalada = record["Potência instalada [kVA]"] || "Sem Informação";

                            console.log(`Adicionando marcador: ${nivelUtilizacao}, ${potenciaInstalada} (${latitude}, ${longitude})`);

                            const marcador = L.marker([latitude, longitude])
                                .bindPopup(`<b>Nível de Utilização:</b> ${nivelUtilizacao}<br><b>Potência instalada:</b> ${potenciaInstalada} kVA`);

                            clusters.addLayer(marcador); // Adiciona o marcador ao grupo de clusters
                        } else {
                            console.warn('Registro sem coordenadas:', record);
                        }
                    });
                },
                error: function(error) {
                    console.error('Erro ao carregar marcadores:', error);
                }
            });
        }

        // Carrega os marcadores ao carregar a página
        carregarMarcadores();

        // Coordenadas dos distritos
        const coordenadasDistritos = {
            "Aveiro": [40.6413, -8.6536],
            "Beja": [38.0151, -7.8632],
            "Braga": [41.5454, -8.4265],
            "Braganca": [41.8058, -6.7572],
            "CasteloBranco": [39.8222, -7.4909],
            "Coimbra": [40.2111, -8.4291],
            "Evora": [38.5714, -7.9137],
            "Faro": [37.0179, -7.9307],
            "Guarda": [40.5373, -7.2674],
            "Leiria": [39.7476, -8.8049],
            "Lisboa": [38.7167, -9.1399],
            "Portalegre": [39.2936, -7.4312],
            "Porto": [41.17820882715362, -8.608457297299513],
            "Santarem": [39.2362, -8.6855],
            "Setubal": [38.5244, -8.8882],
            "Viana do Castelo": [41.6918, -8.8345],
            "VilaReal": [41.3006, -7.7441],
            "Viseu": [40.6610, -7.9097]
        };

        // Função para centralizar o mapa no distrito
        function centralizarMapa(distrito) {
            if (distrito === "") return; // Não faz nada se a opção selecionada for inválida

            const coordenadas = coordenadasDistritos[distrito];
            if (coordenadas) {
                map.setView(coordenadas, 12);
            }
        }

        function toggleMenu() {
            var menu = document.getElementById("menu");
            menu.classList.toggle("visible");
        }
    </script>
</body>

</html>
