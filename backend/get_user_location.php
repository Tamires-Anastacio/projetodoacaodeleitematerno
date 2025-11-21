<?php
// backend/get_user_location.php
session_start();
require 'includes/conexao.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['error' => 'not_logged']);
    exit;
}

$id = (int) $_SESSION['id_user'];

$sql = "SELECT latitude, longitude, cidade, uf, nome_completo, nome FROM usuario WHERE id_user = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['error' => 'user_not_found']);
    exit;
}

echo json_encode($user, JSON_UNESCAPED_UNICODE);
