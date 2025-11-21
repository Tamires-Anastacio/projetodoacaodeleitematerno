<?php
// backend/buscar_instituicoes.php
require 'includes/conexao.php';
header('Content-Type: application/json; charset=utf-8');

// Parâmetros: uf & cidade OR lat & lng & raio_km
$uf = $_GET['uf'] ?? null;
$cidade = $_GET['cidade'] ?? null;
$lat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
$lng = isset($_GET['lng']) ? (float)$_GET['lng'] : null;
$raio_km = isset($_GET['raio']) ? (float)$_GET['raio'] : 5.0;

// Função simples para geocodificar via Nominatim (com User-Agent)
function geocode_nominatim($endereco) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($endereco) . "&limit=1&addressdetails=0";
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: SeuAppNome/1.0 (seu-email@dominio) \r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $res = @file_get_contents($url, false, $context);
    if ($res === false) return null;
    $json = json_decode($res, true);
    if (!$json || !isset($json[0])) return null;
    return ['lat' => $json[0]['lat'], 'lon' => $json[0]['lon']];
}

// Monta query base
if ($lat !== null && $lng !== null) {
    // Busca por raio usando Haversine
    $sql = "SELECT id_instituicao, nome, rua, num, cidade, uf, telefone, email, latitude, longitude,
            (6371 * acos(
                cos(radians(:lat)) * cos(radians(COALESCE(latitude,0))) *
                cos(radians(COALESCE(longitude,0)) - radians(:lng)) +
                sin(radians(:lat)) * sin(radians(COALESCE(latitude,0)))
            )) AS distancia_km
            FROM instituicao
            HAVING distancia_km <= :raio
            ORDER BY distancia_km ASC
            LIMIT 200";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':lat'=>$lat, ':lng'=>$lng, ':raio'=>$raio_km]);
    $insts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Busca por cidade e uf
    if (!$uf || !$cidade) {
        echo json_encode([]);
        exit;
    }
    $sql = "SELECT id_instituicao, nome, rua, num, cidade, uf, telefone, email, latitude, longitude
            FROM instituicao
            WHERE uf = :uf AND cidade = :cidade
            LIMIT 500";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':uf'=>$uf, ':cidade'=>$cidade]);
    $insts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Para cada instituição, se falta lat/lon -> geocodifica e atualiza DB (caching)
$updated = 0;
foreach ($insts as &$i) {
    if (empty($i['latitude']) || empty($i['longitude'])) {
        $end = trim(($i['rua'] ?? '') . ' ' . ($i['num'] ?? '') . ', ' . ($i['cidade'] ?? '') . ' - ' . ($i['uf'] ?? ''));
        if ($end) {
            $geo = geocode_nominatim($end);
            if ($geo) {
                // grava no banco
                $upd = $pdo->prepare("UPDATE instituicao SET latitude = :lat, longitude = :lon WHERE id_instituicao = :id");
                $upd->execute([':lat'=>$geo['lat'], ':lon'=>$geo['lon'], ':id'=>$i['id_instituicao']]);
                $i['latitude'] = $geo['lat'];
                $i['longitude'] = $geo['lon'];
                $updated++;
                // Respeite Nominatim: não faça bursts. Se muitos registros, considere fila/cron.
                usleep(200000); // 200ms entre requisições
            }
        }
    }
}

// Retorna só com lat/lon válidos; inclui distância se calculada
$out = [];
foreach ($insts as $i) {
    if (!empty($i['latitude']) && !empty($i['longitude'])) {
        $out[] = $i;
    }
}

echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
