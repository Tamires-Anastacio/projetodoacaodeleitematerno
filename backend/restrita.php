<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../frontend/doacao_leite_materno/index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Página Restrita</title>
</head>
<body>
  <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
  <a href="logout.php">Sair</a>
</body>
</html>
