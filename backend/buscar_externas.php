<?php
// backend/buscar_externas.php
header('Content-Type: application/json; charset=utf-8');

// Parâmetros recebidos
$cidade = $_GET['cidade'] ?? '';
$uf     = $_GET['uf'] ?? '';
$term   = $_GET['term'] ?? ''; // opcional: maternidade / hospital / banco de leite

// Se a cidade não foi enviada, retorna vazio
if (empty($cidade)) {
    echo json_encode([]);
    exit;
}

// Lista de termos padrão
$terms = $term ? [$term] : [
    'maternidade',
    'hospital',
    'banco de leite',
    'posto de saude',
    'upa',
];

// Resultado final
$results = [];

// Faz busca para cada termo
foreach ($terms as $t) {

    // Termo + cidade + estado
    $q = "$t $cidade $uf";

    // URL de busca no Nominatim
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($q) . "&limit=10";

    // Cabeçalho obrigatório — sem isso a API bloqueia
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: DoacaoLeiteApp/1.0 (contato@seusite.com)\r\n"
        ]
    ];

    $context = stream_context_create($opts);

    // Executa busca
    $res = @file_get_contents($url, false, $context);
    if (!$res) continue;

    $json = json_decode($res, true);
    if (!$json) continue;

    // Salva dados
    foreach ($json as $item) {
        $results[] = [
            'nome' => $item['display_name'],
            'lat'  => $item['lat'],
            'lon'  => $item['lon'],
            'type' => $t
        ];
    }

    // Delay para evitar bloqueio da API
    usleep(200000); // 0.2s
}

// REMOVE DUPLICATAS por latitude + longitude
$seen = [];
$out = [];

foreach ($results as $r) {
    $key = round((float)$r['lat'], 5) . '|' . round((float)$r['lon'], 5);

    if (!isset($seen[$key])) {
        $seen[$key] = true;
        $out[] = $r;
    }
}

// Retorna JSON final
echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
