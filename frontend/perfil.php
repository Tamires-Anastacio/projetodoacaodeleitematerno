<?php
session_start();
include('conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$sql = "SELECT * FROM usuario WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Usuário não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Perfil do Usuário</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f6e6f8;
            font-family: "Comic Relief", sans-serif;
        }
        .container {
            max-width: 700px;
            margin-top: 5%;
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-family: "Protest Guerrilla", sans-serif;
            text-align: center;
            background: linear-gradient(45deg, #cd7ccf, #3a0e66);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .info p {
            font-size: 18px;
            margin: 6px 0;
        }
        .btn-editar {
            background-color: #cd7ccf;
            color: white;
            border: none;
            width: 100%;
            font-weight: bold;
        }
        .btn-editar:hover {
            background-color: #3a0e66;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Perfil do Usuário</h1>

        <div class="info">

            <p><strong>Nome:</strong> <?= htmlspecialchars($user['nome_completo']); ?></p>
            <p><strong>CPF:</strong> <?= htmlspecialchars($user['cpf']); ?></p>
            <p><strong>Cidade:</strong> <?= htmlspecialchars($user['cidade']); ?> - <?= htmlspecialchars($user['uf']); ?></p>
            <p><strong>Data de Nascimento:</strong> <?= date('d/m/Y', strtotime($user['data_nascimento'])); ?></p>
            <p><strong>Telefone:</strong> <?= htmlspecialchars($user['telefone']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Tipo de Usuário:</strong> <?= htmlspecialchars($user['tipo_user']); ?></p>
            <p><strong>Data de Cadastro:</strong> <?= date('d/m/Y H:i', strtotime($user['data_cadastro'])); ?></p>

        </div>

        <a href="editar_perfil.php" class="btn btn-editar mt-3">Editar Perfil</a>
    </div>
</body>
</html>
