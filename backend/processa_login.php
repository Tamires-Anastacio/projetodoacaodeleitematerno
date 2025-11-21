<?php
session_start();
require 'includes/conexao.php';

// Recebe dados do formulário
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifica campos obrigatórios
if (empty($email) || empty($senha)) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Ops!',
            text: 'Preencha todos os campos!'
        });
    </script>";
    exit;
}

// ===============================
// 🔎 1. BUSCA NA TABELA USUARIO
// ===============================
$sql = "SELECT *, 'user' AS tipo_user FROM usuario WHERE email = :email LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// ===============================
// 🔎 2. SE NÃO ENCONTROU, BUSCA NA TABELA INSTITUIÇÃO
// ===============================
if (!$usuario) {
    $sql = "SELECT *, 'inst' AS tipo_user FROM instituicao WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ===============================
// ❌ 3. NÃO ACHOU NEM USUÁRIO NEM INSTITUIÇÃO
// ===============================
if (!$usuario) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Usuário não encontrado',
            text: 'E-mail incorreto'
        });
    </script>";
    exit;
}

// ===============================
// 🔐 4. VERIFICA A SENHA
// ===============================
if (!isset($usuario['senha_hash']) || !password_verify($senha, $usuario['senha_hash'])) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Senha incorreta',
            text: 'Tente novamente'
        });
    </script>";
    exit;
}

// ===================================
// 🟢 5. LOGIN ACEITO — CRIA SESSÃO
// ===================================
$_SESSION['id_user'] = $usuario['id_user'] ?? $usuario['id_instituicao'];
$_SESSION['nome'] = $usuario['nome_completo'] ?? $usuario['nome'];
$_SESSION['email'] = $usuario['email'];
$_SESSION['tipo_user'] = $usuario['tipo_user'];

// ===================================
// 🚀 6. REDIRECIONAMENTO
// ===================================
if ($usuario['tipo_user'] === 'adm') {
    header("Location: ../frontend/adm.php");
    exit;
}

if ($usuario['tipo_user'] === 'inst') {
    header("Location: ../frontend/dashboard_inst.php");
    exit;
}

// Usuário comum
header("Location: ../frontend/dashboard_user.php");
exit;
?>
