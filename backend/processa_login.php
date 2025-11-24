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

// Busca usuário na tabela usuario
$sql_usuario = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
$stmt_usuario = $pdo->prepare($sql_usuario);
$stmt_usuario->bindValue(':email', $email);
$stmt_usuario->execute();
$usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

// Se não encontrar na tabela usuario, procura na tabela instituicao
if (!$usuario) {
    $sql_instituicao = "SELECT * FROM instituicao WHERE email = :email LIMIT 1";
    $stmt_instituicao = $pdo->prepare($sql_instituicao);
    $stmt_instituicao->bindValue(':email', $email);
    $stmt_instituicao->execute();
    $instituicao = $stmt_instituicao->fetch(PDO::FETCH_ASSOC);

    // Se encontrar na tabela instituicao, define como tipo de usuário 'inst'
    if ($instituicao) {
        $usuario = $instituicao;
        $usuario['tipo_user'] = 'inst'; // Define que é uma instituição
    }
}

// Se não encontrar em nenhum dos casos
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

// Verifica a senha
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
$_SESSION['id_user'] = $usuario['id_user'] ?? $usuario['id_instituicao']; // ID pode ser diferente nas tabelas
$_SESSION['nome'] = $usuario['nome_completo'] ?? $usuario['nome']; // Nome pode ter campos diferentes nas tabelas
$_SESSION['email'] = $usuario['email'];
$_SESSION['tipo_user'] = $usuario['tipo_user'];

// Redirecionamento por tipo de usuário
switch ($usuario['tipo_user']) {
    case 'adm':
        header("Location: ../frontend/adm.php");
        break;
    case 'inst':
        header("Location: ../frontend/dashboard_inst.php
        ");
        break;
    default:
        header("Location: ../frontend/dashboard_user.php");
        break;
}
exit;
?>