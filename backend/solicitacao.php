<?php
require 'includes/conexao.php'; // conexão PDO

// Inicia sessão apenas se não existir
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se é instituição
if (!isset($_SESSION['tipo_user']) || $_SESSION['tipo_user'] !== 'inst') {
    die("Acesso negado.");
}

$id_instituicao = $_SESSION['id_user']; // pois no login você salva id_user ou id_instituicao aqui

$query = "SELECT s.id_solicitacao, u.nome_completo, s.tipo_solicitacao, 
                 s.observacao, s.status, s.data_solicitacao
          FROM solicitacao s
          INNER JOIN usuario u ON s.id_user = u.id_user
          WHERE s.id_instituicao = :id";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':id', $id_instituicao, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Solicitações Recebidas</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h2>Solicitações Recebidas</h2>
  <table class="table table-striped mt-3">
    <thead>
      <tr>
        <th>Usuário</th>
        <th>Tipo</th>
        <th>Observação</th>
        <th>Status</th>
        <th>Data</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($result as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['nome_completo']) ?></td>
        <td><?= ucfirst($row['tipo_solicitacao']) ?></td>
        <td><?= htmlspecialchars($row['observacao']) ?></td>
        <td><?= ucfirst($row['status']) ?></td>
        <td><?= date('d/m/Y H:i', strtotime($row['data_solicitacao'])) ?></td>
        <td>
          <?php if($row['status'] == 'pendente'): ?>
            <form method="POST" action="atualizar_solicitacao.php" style="display:inline;">
              <input type="hidden" name="id" value="<?= $row['id_solicitacao'] ?>">
              <button name="acao" value="aceita" class="btn btn-success btn-sm">Aceitar</button>
              <button name="acao" value="recusada" class="btn btn-danger btn-sm">Recusar</button>
            </form>
          <?php elseif($row['status'] == 'aceita'): ?>
            <a href="chat.php?id=<?= $row['id_solicitacao'] ?>" class="btn btn-primary btn-sm">Abrir Chat</a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
