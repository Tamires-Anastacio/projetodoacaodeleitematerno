<?php
header('Content-Type: application/json; charset=utf-8');

$cidade = $_GET['cidade'] ?? '';
$uf = $_GET['uf'] ?? '';

if(!$cidade || !$uf){
    echo json_encode([]);
    exit;
}

// Nominatim via backend para evitar CORS
$query = urlencode("banco de leite $cidade $uf");
$url = "https://nominatim.openstreetmap.org/search?format=json&q=$query";

// User-Agent obrigatório
$options = [
    "http" => ["header" => "User-Agent: MeuApp/1.0\r\n"]
];
$context = stream_context_create($options);
$data = @file_get_contents($url, false, $context);

if(!$data){
    echo json_encode([]);
    exit;
}

$places = json_decode($data, true);
$instituicoes = [];

foreach($places as $p){
    $inst = [
        'id_instituicao'=>null,
        'nome'=>$p['display_name'],
        'cidade'=>$cidade,
        'uf'=>$uf,
        'rua'=>$p['address']['road']??'',
        'num'=>$p['address']['house_number']??'',
        'certificacao'=>'',
        'latitude'=>floatval($p['lat']),
        'longitude'=>floatval($p['lon']),
        'origem'=>'osm'
    ];
    $instituicoes[] = $inst;
}

echo json_encode($instituicoes, JSON_UNESCAPED_UNICODE);
?>
