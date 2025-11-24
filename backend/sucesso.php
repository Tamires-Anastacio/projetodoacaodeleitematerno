<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../frontend/form_login.html");
    exit;
}

$nome = $_SESSION['nome_completo'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login bem-sucedido</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container text-center mt-5">
  <h1>Bem-vindo, <?php echo htmlspecialchars($nome); ?>!</h1>
  <p>Login realizado com sucesso.</p>
  <a href="../frontend/map_user.php" class="btn btn-primary">Ver Mapa</a>
  <a href="logout.php" class="btn btn-danger">Sair</a>
</div>
</body>
</html>
