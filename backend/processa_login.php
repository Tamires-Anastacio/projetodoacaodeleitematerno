<?php
session_start();
require "conexao.php"; // certifique-se de que $pdo está definido neste arquivo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pegando os dados do formulário
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        die("Email ou senha não podem ser vazios.");
    }

    try {
        // Consulta segura com PDO
        $stmt = $pdo->prepare("SELECT id_user, nome_completo, senha_hash FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha_hash'])) {
            // Login válido
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nome_completo'] = $user['nome_completo'];
            $_SESSION['email'] = $email;

            header("Location: sucesso.php");
            exit;
        } else {
            echo "<script>alert('Email ou senha inválidos!');window.location='../frontend/form_login.html';</script>";
        }
    } catch (PDOException $e) {
        // Em caso de erro no banco
        die("Erro no banco de dados: " . $e->getMessage());
    }
} else {
    header("Location: ../frontend/form_login.html");
    exit;
}
?>
