<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Instalações Solares</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script>
        // Inicializa o mapa
        const map = L.map('map').setView([39.5, -8], 7); // Coordenadas centrais de Portugal

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
