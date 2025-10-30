<?php
// --- buscar.php ---
include_once 'backend/includes/conexao.php';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die(json_encode(["erro" => "Erro de conexão: " . $e->getMessage()]));
}

// Parâmetros vindos da URL
$uf = $_GET['uf'] ?? '';
$cidade = $_GET['cidade'] ?? '';

// Busca no banco
$sql = "SELECT nome_completo, latitude, longitude, cidade, uf FROM usuario WHERE uf = :uf AND cidade = :cidade";
$stmt = $pdo->prepare($sql);
$stmt->execute(['uf' => $uf, 'cidade' => $cidade]);
$pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retorna em formato JSON
header('Content-Type: application/json');
echo json_encode($pessoas);
