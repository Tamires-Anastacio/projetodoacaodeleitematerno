<?php
session_start();
include_once '../backend/includes/conexao.php';

$cpf = $_POST['cpf'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Consulta segura: compara senha via hash (ideal)
$sql = "SELECT * FROM usuario 
        WHERE cpf = :cpf 
        AND email = :email";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':cpf', $cpf);
$stmt->bindParam(':email', $email);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$usuario){
    // Nenhum usuário encontrado
    header("Location: index.php?erro=usuario_incorreto");
    exit();
}

// Verificação da senha (se estiver usando hash)
if(!password_verify($senha, $usuario['senha_hash'])){
    header("Location: index.php?erro=senha_incorreta");
    exit();
}

// Login — salvar na sessão
$_SESSION['id_user'] = $usuario['id_user'];
$_SESSION['nome'] = $usuario['nome_completo'];
$_SESSION['email'] = $usuario['email'];
$_SESSION['tipo_user'] = $usuario['tipo_user'];


// REDIRECIONAMENTO POR TIPO DE USUÁRIO
// -------------------------------------

//Administrador

if ($usuario['tipo_user'] === 'adm') {
    header("Location: adm.php");
    exit();
}

if ($usuario['tipo_user'] === 'inst') {
    header("Location:../frontend/dashboard_inst.php");  // INSTITUIÇÃO
    exit();
}

// Usuário comum
header("Location: ../frontend/dashboard_user.php");
exit();

?>
