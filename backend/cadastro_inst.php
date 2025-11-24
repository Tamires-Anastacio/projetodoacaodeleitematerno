<?php
require 'includes/conexao.php';

// Pegar os dados do formulário
$nome  = trim($_POST['nome'] ?? '');
$cnpj            = trim($_POST['cnpj'] ?? '');
$telefone        = trim($_POST['telefone'] ?? '');
$data_cadastro = trim($_POST['data_cadastro'] ?? '');
$email           = trim($_POST['email'] ?? '');
$senha           = trim($_POST['senha'] ?? '');
$uf              = trim($_POST['uf'] ?? '');
$cidade          = trim($_POST['cidade'] ?? '');

// Verificar campos obrigatórios
if (!$nome || !$cnpj || !$telefone || !$data_cadastro || !$email || !$senha || !$uf || !$cidade) {
    echo json_encode([
        "success" => false,
        "message" => "Por favor, preencha todos os campos obrigatórios!"
    ]);
    exit;
}

// Verificar CNPJ duplicado
try {
    $stmt = $pdo->prepare("SELECT id FROM usuario WHERE cnpj = :cnpj");
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "success" => false,
            "message" => "CPF já cadastrado!"
        ]);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao verificar CNPJ: " . $e->getMessage()
    ]);
    exit;
}

// Gerar hash da senha
$senha_hash = password_hash($senha, PASSWORD_BCRYPT);

// Inserir no banco
$sql = "INSERT INTO usuario (nome_completo, cpf, telefone, data_nascimento, email, senha_hash, uf, cidade) 
        VALUES (:nome_completo, :cpf, :telefone, :data_nascimento, :email, :senha_hash, :uf, :cidade)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':data_cadastro', $data_cadastro);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha_hash', $senha_hash);
    $stmt->bindParam(':uf', $uf);
    $stmt->bindParam(':cidade', $cidade);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Cadastro realizado com sucesso!"
        ]);
    } else {
        $error = $stmt->errorInfo();
        echo json_encode([
            "success" => false,
            "message" => "Erro ao cadastrar usuário.",
            "error" => $error
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro no servidor: " . $e->getMessage()
    ]);
}
