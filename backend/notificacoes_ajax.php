<?php
require 'includes/conexao.php';
session_start();

if (!isset($_SESSION['id_instituicao'])) {
    http_response_code(403);
    exit;
}

$id_inst = $_SESSION['id_instituicao'];

$sql = "SELECT * FROM notificacao 
        WHERE id_instituicao = ? AND lida = 0 
        ORDER BY data_envio DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_inst);
$stmt->execute();
$result = $stmt->get_result();

$notificacoes = [];
while ($n = $result->fetch_assoc()) {
    $notificacoes[] = $n;
}

header('Content-Type: application/json');
echo json_encode($notificacoes);
?>
