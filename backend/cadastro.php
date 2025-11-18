<?php

require 'includes/conexao.php';




$nome_completo = $POST['nome_completo'];
$cpf = $POST['cpf'] ;
$telefone = $POST['telefone'] ; 
$data_nascimento = $POST['data_nascimento'] ;
$email = $POST['email'] ;
$senha_hash = md5['senha'] ;
$uf = $POST['uf'] ;
$cidade = $POST['cidade'] ;



if ($nome && $cpf && $data_nascimento && $email && $senha_hash && $uf && $cidade) {

    $sql = "INSERT INTO usuario (nome_completo, cpf, email, telefone, data_nascimento, senha, uf, cidade) 
            VALUES (:nome_completo, :cpf, :email, :telefone, :data_nascimento, :senha_hash, :uf, :cidade)";
    
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_completo', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':Pos$POSTnascimento', $POSTnascimento);
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
