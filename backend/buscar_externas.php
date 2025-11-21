<?php
// backend/buscar_externas.php
header('Content-Type: application/json; charset=utf-8');

$cidade = $_GET['cidade'] ?? '';
$uf = $_GET['uf'] ?? '';
$term = $_GET['term'] ?? ''; // opcional: 'maternidade' / 'banco de leite' / 'hospital'

if (!$cidade) {
    echo json_encode([]);
    exit;
}

$terms = [];
if ($term) {
    $terms[] = $term;
} else {
    $terms = ['maternidade', 'hospital', 'banco de leite', 'posto de saúde', 'unidade de pronto atendimento'];
}

$results = [];

foreach ($terms as $t) {
    // busca por termo + cidade
    $q = $t . ' ' . $cidade . ' ' . $uf;
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($q) . "&limit=10";
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: SeuAppNome/1.0 (seu-email@dominio) \r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $res = @file_get_contents($url, false, $context);
    if (!$res) continue;
    $json = json_decode($res, true);
    if (!$json) continue;
    foreach ($json as $item) {
        $results[] = [
            'nome' => $item['display_name'],
            'lat' => $item['lat'],
            'lon' => $item['lon'],
            'type' => $t
        ];
    }
    usleep(200000);
}

// reduzir duplicatas simples (mesma lat/lon)
$seen = [];
$out = [];
foreach ($results as $r) {
    $key = round((float)$r['lat'],5) . '|' . round((float)$r['lon'],5);
    if (!isset($seen[$key])) {
        $seen[$key] = true;
        $out[] = $r;
    }
}

echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
