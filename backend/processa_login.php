<?php
session_start();
require 'includes/conexao.php'; // Certifique-se que o caminho está correto

// Recebe dados do formulário
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifica campos obrigatórios
if (!$email || !$senha) {
    echo "<script>
    Swal.fire({
        icon: 'error',
        title: 'Ops!',
        text: 'Preencha todos os campos!'
    });
    </script>";
    exit;
}

// Busca usuário pelo email
$sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

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

// Verifica senha
if (!password_verify($senha, $usuario['senha_hash'])) {
    echo "<script>
    Swal.fire({
        icon: 'error',
        title: 'Senha incorreta',
        text: 'Tente novamente'
    });
    </script>";
    exit;
}

// Login bem-sucedido, salva na sessão
$_SESSION['id_user'] = $usuario['id_user'];
$_SESSION['nome'] = $usuario['nome_completo'];
$_SESSION['email'] = $usuario['email'];
$_SESSION['tipo_user'] = $usuario['tipo_user'];

// Redirecionamento por tipo de usuário
switch ($usuario['tipo_user']) {
    case 'adm':
        header("Location: ../frontend/adm.php");
        break;
    case 'inst':
        header("Location: ../frontend/dashboard_inst.html");
        break;
    default:
        header("Location: ../frontend/dashboard_user.html");
        break;
}
exit;
?>
