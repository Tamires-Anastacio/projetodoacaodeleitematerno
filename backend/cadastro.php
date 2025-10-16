<?php
require 'conexao.php'; 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Se for requisição OPTIONS (pré-verificação do CORS), termina aqui
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Pega os dados enviados pelo React (JSON)
$data = json_decode(file_get_contents("php://input"), true);

$nome = $data['nome'] ?? '';
$cpf = $data['cpf'] ?? '';
$telefone = $data['fone'] ?? '';
$datanascimento = $data['datanascimento'] ?? '';
$email  = $data['email'] ?? '';
$senha  = $data['senha'] ?? '';
$uf     = $data['uf'] ?? '';

if ($nome && $cpf && $datanascimento && $email && $senha && $uf) {

    $sql = "INSERT INTO usuario (nome, cpf, email, telefone, datanascimento, senha, uf) 
            VALUES (:nome, :cpf, :email, :fone, :datanascimento, :senha, :uf)";
    
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':fone', $telefone);
    $stmt->bindParam(':datanascimento', $datanascimento);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':uf', $uf);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Cadastro feito com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao cadastrar."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Preencha todos os campos."]);
}
