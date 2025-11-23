<?php
require '../backend/includes/conexao.php';
session_start();

if (!isset($_SESSION['id_user'])) {
    die("Acesso negado.");
}

$id_remetente = $_SESSION['id_user'];
$id_destinatario = $_POST['id_destinatario'];
$id_solicitacao = $_POST['id_solicitacao'];
$conteudo = trim($_POST['conteudo']);

if ($conteudo == "") {
    die("Mensagem vazia.");
}

// Inserir mensagem
$sql = "INSERT INTO mensagem (id_solicitacao, id_remetente, id_destinatario, conteudo)
        VALUES (?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_solicitacao, $id_remetente, $id_destinatario, $conteudo]);

// Redireciona de volta ao chat
header("Location: chat.php?id=" . $id_solicitacao);
exit;
