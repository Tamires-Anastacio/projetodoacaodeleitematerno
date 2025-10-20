<?php
$host = 'localhost';
$dbname = 'sistema';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Habilita erros do PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Retorna um erro JSON compatível com o React
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Erro na conexão: " . $e->getMessage()]);
    exit;
}
?>
