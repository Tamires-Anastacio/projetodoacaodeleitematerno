<?php
header('Content-Type: application/json; charset=utf-8');

$conn = new mysqli("localhost", "root", "", "sistema");
if ($conn->connect_error) {
  echo json_encode([]);
  exit;
}

$uf = strtoupper($_GET['uf'] ?? '');
$cidade = $_GET['cidade'] ?? '';

if (!$uf || !$cidade) {
  echo json_encode([]);
  exit;
}

$stmt = $conn->prepare("SELECT nome, cidade, uf, latitude, longitude FROM usuario WHERE uf = ? AND cidade = ?");
$stmt->bind_param("ss", $uf, $cidade);
$stmt->execute();
$result = $stmt->get_result();

$usuario = [];
while ($row = $result->fetch_assoc()) {
  $usuario[] = $row;
}

echo json_encode($usuario);
