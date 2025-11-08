<?php
require 'conexao.php';
header('Content-Type: application/json; charset=utf-8');

// Verifica se veio cidade e UF
if (!isset($_GET['cidade']) || !isset($_GET['uf'])) {
    echo json_encode([]);
    exit;
}

$cidade = trim($_GET['cidade']);
$uf = trim($_GET['uf']);

// Busca as instituições cadastradas no banco
$stmt = $conn->prepare("
    SELECT 
        id_instituicao,
        nome,
        cidade,
        uf,
        rua,
        num,
        certificacao,
        latitude,
        longitude
    FROM instituicao
    WHERE cidade = ? AND uf = ?
");
$stmt->bind_param("ss", $cidade, $uf);
$stmt->execute();
$result = $stmt->get_result();

$instituicoes = [];

while ($row = $result->fetch_assoc()) {
    $row['origem'] = 'banco';
    $instituicoes[] = $row;
}

// Se não encontrar nenhuma, busca na API do Google Places
if (empty($instituicoes)) {
    $apiKey = "SUA_API_KEY_DO_GOOGLE"; // substitua pela sua chave Google Maps
    $cidadeEncoded = urlencode("$cidade, $uf, Brasil");
    $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=banco+de+leite+$cidadeEncoded&key=$apiKey";

    $response = file_get_contents($url);
    $places = json_decode($response, true);

    if (!empty($places['results'])) {
        foreach ($places['results'] as $p) {
            $instituicoes[] = [
                'id_instituicao' => null,
                'nome' => $p['name'],
                'cidade' => $cidade,
                'uf' => $uf,
                'rua' => $p['formatted_address'] ?? '',
                'num' => '',
                'certificacao' => '',
                'latitude' => $p['geometry']['location']['lat'],
                'longitude' => $p['geometry']['location']['lng'],
                'origem' => 'google'
            ];
        }
    }
}

echo json_encode($instituicoes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
$stmt->close();
$conn->close();
?>
