<?php
require '../backend/includes/conexao.php';
session_start();

// Usuário deve estar logado
if (!isset($_SESSION['id_user'])) {
    die("Acesso negado");
}

$id_user = $_SESSION['id_user'];

// Verifica se id_solicitacao existe
if (!isset($_GET['id'])) {
    die("ID da solicitação inválido.");
}

$id_solicitacao = (int) $_GET['id'];

// Busca dados da solicitação para validar acesso
$sql = "SELECT id_user, id_instituicao 
        FROM solicitacao 
        WHERE id_solicitacao = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_solicitacao]);
$sol = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sol) {
    die("Solicitação não encontrada.");
}

// Verifica se usuário tem permissão
if ($id_user !== $sol['id_user'] && $id_user !== $sol['id_instituicao']) {
    die("Acesso negado.");
}

// Define destinatário automaticamente
$destinatario = ($id_user == $sol['id_user']) 
                ? $sol['id_instituicao'] 
                : $sol['id_user'];

// Busca mensagens
$sqlMsg = "SELECT * FROM mensagem
           WHERE id_solicitacao = ?
           ORDER BY data_envio ASC";
$stmtMsg = $pdo->prepare($sqlMsg);
$stmtMsg->execute([$id_solicitacao]);
$mensagens = $stmtMsg->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Chat de Doação</title>
<style>
body { 
font-family: Arial; background: #f4f4f9; }
.chat-box { 
  width: 70%; margin: 20px auto; background: #fff;
  padding: 20px; border-radius: 10px; 
  box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
}
.msg { 
  padding: 10px 14px; 
  margin: 8px 0; 
  border-radius: 15px; 
  width: fit-content; 
}
.enviada { background: #5e17eb; color: #fff; margin-left: auto; }
.recebida { background: #eaeaea; color: #333; }
form { display: flex; gap: 10px; margin-top: 20px; }
input[type="text"] { flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #ccc; }
button { padding: 10px 15px; background: #5e17eb; color: #fff;
         border: none; border-radius: 8px; cursor: pointer; }
</style>
</head>
<body>

<div class="chat-box">
    <h3>💬 Conversa da Solicitação #<?= $id_solicitacao ?></h3>

    <!-- MENSAGENS -->
    <div id="mensagens">
        <?php foreach ($mensagens as $m): ?>
            <?php $classe = ($m['id_remetente'] == $id_user) ? 'enviada' : 'recebida'; ?>

            <div class="msg <?= $classe ?>">
                <?= htmlspecialchars($m['conteudo']) ?><br>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- FORMULÁRIO DE ENVIO -->
    <form action="enviar_mensagem.php" method="POST">
        <input type="hidden" name="id_solicitacao" value="<?= $id_solicitacao ?>">
        <input type="hidden" name="id_destinatario" value="<?= $destinatario ?>">
        <input type="text" name="conteudo" placeholder="Digite sua mensagem..." required>
        <button type="submit">Enviar</button>
    </form>

</div>

</body>
</html>
