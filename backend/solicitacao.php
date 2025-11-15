<?php
session_start();
require 'includes/conexao.php'; // conexão com o banco

$id_instituicao = $_SESSION['id_instituicao']; // vem do login da instituição

$query = "SELECT s.id_solicitacao, u.nome_completo, s.tipo_solicitacao, s.observacao, s.status, s.data_solicitacao
          FROM solicitacao s
          INNER JOIN usuario u ON s.id_user = u.id_user
          WHERE s.id_instituicao = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_instituicao);
$stmt->execute();
$result = $stmt->get_result();
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
      <?php while($row = $result->fetch_assoc()): ?>
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
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
