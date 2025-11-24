<?php
session_start();
 require_once '../backend/includes/conexao.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: form_login.html");
    exit;
}

$user_nome = $_SESSION['nome'] ?? 'Usuário';

$instituicoes = [];

try {
    $instituicoes = [
        ['nome' => 'Banco de Leite Humano Maternidade Escola', 'lat' => -22.9094, 'lng' => -43.1729, 'cidade' => 'Rio de Janeiro'],
        ['nome' => 'Hospital das Clínicas - BLH', 'lat' => -23.5505, 'lng' => -46.6333, 'cidade' => 'São Paulo'],
        ['nome' => 'Instituto de Medicina Integral', 'lat' => -8.0476, 'lng' => -34.8770, 'cidade' => 'Recife'],
    ];

} catch (Exception $e) {
    $erro = "Erro ao buscar instituições.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Instituições - Doação de Leite</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Leaflet CSS (Mapa) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6a0dad;
            --secondary: #e91e63;
            --bg-color: #f3e5f5;
        }

        body {
            font-family: "Inter", sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding-bottom: 40px;
        }

        /* Navbar Estilizada */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(106, 13, 173, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary) !important;
        }

        .btn-back {
            
            border: 1px solid var(--secondary);
            border-radius: 20px;
            padding: 5px 15px;
            transition: 0.3s;
            text-decoration: none;
        }

        .btn-back:hover {
            background: var(--secondary);
            color: white;
        }

        /* Container do Mapa */
        .map-container {
            background: white;
            padding: 15px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 30px;
            position: relative;
        }

        h2 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 10px;
        }

        #map {
            height: 600px;
            width: 100%;
            border-radius: 15px;
            z-index: 1;
        }

        /* Botão Localização */
        .btn-location {
            position: absolute;
            bottom: 30px;
            right: 30px;
            z-index: 999; /* Acima do mapa */
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-location:hover {
            background-color: var(--secondary);
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            #map { height: 70vh; } /* Altura maior no mobile */
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar fixed-top px-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-3">
                <a href="dashboard_user.php" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <span class="navbar-brand mb-0 h1">Rede de Apoio</span>
            </div>
            <div class="d-none d-md-block text-muted">
                Olá, <strong><?php echo htmlspecialchars($user_nome); ?></strong>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container" style="margin-top: 80px;">
        <div class="text-center mb-4">
            <h2>Encontre um Banco de Leite</h2>
            <p class="text-muted">Visualize as instituições parceiras próximas a você.</p>
        </div>

        <div class="map-container">
            <div id="map"></div>
            
            <!-- Botão para pegar localização atual -->
            <button class="btn-location" onclick="getLocation()" title="Minha Localização">
                <i class="bi bi-crosshair"></i>
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    
    <script>
        // 1. Inicializa o Mapa (Centro do Brasil padrão)
        let map = L.map('map').setView([-14.2350, -51.9253], 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // 2. Ícone Personalizado (Opcional - Roxo para combinar)
        var purpleIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // 3. Recebe dados do PHP (Instituições)
        const instituicoes = <?php echo json_encode($instituicoes); ?>;

        // 4. Loop para criar marcadores
        instituicoes.forEach(inst => {
            // Verifica se tem lat/long (Ajuste as chaves conforme seu banco real: latitude/longitude)
            // No exemplo usei 'lat' e 'lng'
            if (inst.lat && inst.lng) {
                L.marker([inst.lat, inst.lng], {icon: purpleIcon})
                .addTo(map)
                .bindPopup(`
                    <div style="text-align:center;">
                        <strong>${inst.nome}</strong><br>
                        <span style="color:#666;">${inst.cidade}</span><br>
                        <a href="#" class="btn btn-sm btn-primary mt-2" style="font-size:0.8rem;">Ver Detalhes</a>
                    </div>
                `);
            }
        });

        // 5. Função de Geolocalização do Usuário
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocalização não é suportada pelo seu navegador.");
            }
        }

        function showPosition(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            // Move o mapa para a posição do usuário com Zoom mais próximo
            map.setView([lat, lon], 13);

            // Adiciona um marcador diferente para "Você"
            L.marker([lat, lon]).addTo(map)
                .bindPopup("<b>Você está aqui!</b>")
                .openPopup();
        }

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("Usuário negou a solicitação de Geolocalização.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Informação de local indisponível.");
                    break;
                case error.TIMEOUT:
                    alert("A requisição expirou.");
                    break;
                default:
                    alert("Erro desconhecido.");
                    break;
            }
        }
    </script>

</body>
</html>