<?php

require 'includes/conexao.php';

$nome_completo = trim($_POST['nome_completo']);
$cpf = trim($_POST['cpf']);
$telefone = trim($_POST['telefone']); 
$data_nascimento = trim($_POST['data_nascimento']);
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);
$uf = trim($_POST['uf']);
$cidade = trim($_POST['cidade']);

$senha_hash = password_hash($senha, PASSWORD_BCRYPT); // Hash seguro da senha

// Verificação correta dos campos — não precisa verificar o hash
if ($nome_completo && $cpf && $data_nascimento && $email && $senha && $uf && $cidade) {

    $sql = "INSERT INTO usuario 
            (nome_completo, cpf, email, telefone, data_nascimento, senha_hash, uf, cidade) 
            VALUES 
            (:nome_completo, :cpf, :email, :telefone, :data_nascimento, :senha_hash, :uf, :cidade)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_completo', $nome_completo);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':data_nascimento', $data_nascimento);
    $stmt->bindParam(':senha_hash', $senha_hash);
    $stmt->bindParam(':uf', $uf);
    $stmt->bindParam(':cidade', $cidade);

    try {
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Cadastro feito com sucesso!"]);
        } else {
            $error = $stmt->errorInfo();
            echo json_encode(["success" => false, "message" => "Erro ao cadastrar.", "error" => $error]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Erro PDO: " . $e->getMessage()]);
    }

} else {
    echo json_encode(["success" => false, "message" => "Preencha todos os campos."]);
}

