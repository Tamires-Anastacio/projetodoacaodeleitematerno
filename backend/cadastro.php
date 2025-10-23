<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require 'includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// Debug temporário (opcional)
// file_put_contents("log.txt", print_r($data, true));

$nome_completo = $data['nome'] ?? '';
$cpf = $data['cpf'] ?? '';
$telefone = $data['telefone'] ?? ''; // atenção no React: você envia 'fone' ou 'telefone'? (no React: 'fone')
$datanascimento = $data['datanascimento'] ?? '';
$email = $data['email'] ?? '';
$senha_hash = md5['senha'] ?? '';
$uf = $data['uf'] ?? '';
$cidade = $data['cidade'] ?? '';

if ($nome && $cpf && $datanascimento && $email && $senha_hash && $uf && $cidade) {

    $sql = "INSERT INTO usuario (nome_completo, cpf, email, telefone, datanascimento, senha, uf, cidade) 
            VALUES (:nome_completo, :cpf, :email, :telefone, :datanascimento, :senha_hash, :uf, :cidade)";
    
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_completo', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':datanascimento', $datanascimento);
    $stmt->bindParam(':senha', $senha_hash);
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
