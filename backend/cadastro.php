<?php
session_start();
require 'includes/conexao.php';

$cpf   = $_POST['cpf'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifica campos vazios
if (!$cpf || !$email || !$senha) {

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        icon: "error",
        title: "Atenção!",
        text: "Preencha todos os campos!"
    }).then(() => {
        window.location.href = "../frontend/index.html";
    });
    </script>';

    exit;
}

// Buscar usuário no banco
$sql = "SELECT * FROM usuario WHERE cpf = :cpf AND email = :email LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':cpf', $cpf);
$stmt->bindValue(':email', $email);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Usuário não encontrado
if (!$usuario) {

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        icon: "error",
        title: "Erro!",
        text: "Usuário não encontrado!"
    }).then(() => {
        window.location.href = "../frontend/index.html?erro=usuario_incorreto";
    });
    </script>';

    exit;
}

// Verificação da senha (coluna correta é senha_hash)
if (!password_verify($senha, $usuario['senha_hash'])) {

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        icon: "error",
        title: "Senha incorreta!",
        text: "Tente novamente."
    }).then(() => {
        window.location.href = "../frontend/index.html?erro=senha_incorreta";
    });
    </script>';

    exit;
}

// Login OK → cria sessão
$_SESSION['id_user']   = $usuario['id_user'];
$_SESSION['nome']      = $usuario['nome_completo'];
$_SESSION['email']     = $usuario['email'];
$_SESSION['tipo_user'] = $usuario['tipo_user'];

// Verifica o tipo de usuário e redireciona
if ($usuario['tipo_user'] === 'adm') {

    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        icon: "success",
        title: "Bem-vindo, administrador!"
    }).then(() => {
        window.location.href = "adm.php";
    });
    </script>';

    exit;
}

if ($usuario['tipo_user'] === 'inst') {

    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        icon: "success",
        title: "Bem-vindo!"
    }).then(() => {
        window.location.href = "../frontend/dashboard_inst.php";
    });
    </script>';

    exit;
}

// Se for usuário comum
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: "success",
    title: "Login realizado!"
}).then(() => {
    window.location.href = "../frontend/dashboard_user.php";
});
</script>';
exit;

?>
