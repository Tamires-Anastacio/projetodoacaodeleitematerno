<?php
require 'backend/includes/conexao.php';
header('Content-Type: application/json; charset=utf-8');

$uf = $_GET['uf'] ?? '';
$cidade = $_GET['cidade'] ?? '';

if (!$uf || !$cidade) {
    echo json_encode([]);
    exit;
}

// Busca apenas quem tem latitude e longitude
$sql = "SELECT nome, cidade, uf, latitude, longitude 
        FROM usuario 
        WHERE uf = :uf AND cidade = :cidade 
        AND latitude IS NOT NULL AND longitude IS NOT NULL";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':uf', $uf);
$stmt->bindParam(':cidade', $cidade);
$stmt->execute();

$pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($pessoas, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
