<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

require __DIR__ . '/includes/conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success"=>false, "message"=>"Nenhum dado recebido"]);
    exit;
}

$cpf = $data['cpf'] ?? '';
$email = $data['email'] ?? '';
$senha = $data['senha'] ?? '';

try {
    $sql = "SELECT id_usuario, nome_completo, email, cpf, senha FROM usuario WHERE cpf=:cpf And email=:email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':cpf'=>$cpf, ':email'=>$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        session_regenerate_id(true);
        $_SESSION['id'] = $user['id_usuario'];
        $_SESSION['nome'] = $user['nome_completo'];
        $_SESSION['email'] = $user['email'];

        echo json_encode(["success"=>true]);
    } else {
        echo json_encode(["success"=>false,"message"=>"CPF/email ou senha incorretos"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success"=>false,"message"=>"Erro no banco: ".$e->getMessage()]);
}
