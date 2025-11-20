<?php
require 'includes/conexao.php';
header('Content-Type: application/json; charset=utf-8');

$cidade = $_GET['cidade'] ?? '';
$uf = $_GET['uf'] ?? '';

if (!$cidade || !$uf) {
    echo json_encode(['erro' => 'Cidade ou UF não informados']);
    exit;
}

$stmt = $conn->prepare("
    SELECT id_instituicao, nome, cidade, uf, rua, num, certificacao, latitude, longitude
    FROM instituicao
    WHERE cidade = ? AND uf = ?
");
$stmt->bind_param("ss", $cidade, $uf);

if(!$stmt->execute()){
    echo json_encode(['erro' => $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$instituicoes = [];

while ($row = $result->fetch_assoc()) {
    $row['latitude'] = floatval($row['latitude']);
    $row['longitude'] = floatval($row['longitude']);
    $row['origem'] = 'banco';
    $instituicoes[] = $row;
}

echo json_encode($instituicoes, JSON_UNESCAPED_UNICODE);
$stmt->close();
$conn->close();
?>
