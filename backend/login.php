<?php
session_start();
require "../backend/includes/conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Substitua pela sua lógica de verificação do banco
    $stmt = $conn->prepare("SELECT id_user, nome, email, tipo_user, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($senha, $user['senha'])) { // se estiver usando hash
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['tipo_user'] = $user['tipo_user'];

            header('Location: ../frontend/map_user.php');
            exit;
        }
    }

    $error = "E-mail ou senha inválidos!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login</title>
</head>
<body>
<form method="POST">
    <input type="email" name="email" placeholder="E-mail" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <button type="submit">Entrar</button>
</form>
<?php if (!empty($error)) echo "<p>$error</p>"; ?>
</body>
</html>
