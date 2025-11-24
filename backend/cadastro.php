<?php
// Define que a resposta será sempre em JSON
header('Content-Type: application/json; charset=utf-8');

// Inclui a conexão
require_once 'includes/conexao.php';

// 1. Receber e verificar se os dados existem (evita erro de "undefined index")
$nome_completo   = $_POST['nome_completo'] ?? '';
$cpf             = $_POST['cpf'] ?? '';
$telefone        = $_POST['telefone'] ?? '';
$data_nascimento = $_POST['data_nascimento'] ?? '';
$uf              = $_POST['uf'] ?? '';
$cidade          = $_POST['cidade'] ?? '';
$email           = $_POST['email'] ?? '';
$senha           = $_POST['senha'] ?? '';
$confsenha       = $_POST['confsenha'] ?? '';

// 2. Validações Básicas
if (empty($nome_completo) || empty($cpf) || empty($email) || empty($senha) || empty($data_nascimento)) {
    echo json_encode(["success" => false, "message" => "Preencha todos os campos obrigatórios."]);
    exit;
}

// Verifica se as senhas conferem
if ($senha !== $confsenha) {
    echo json_encode(["success" => false, "message" => "As senhas não coincidem."]);
    exit;
}

// 3. Limpeza de dados (Remove pontos e traços do CPF e Telefone)
$cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);
$telefone_limpo = preg_replace('/[^0-9]/', '', $telefone);

try {
    // 4. Verificar se CPF ou Email já existem no banco
    $sql_check = "SELECT id FROM usuario WHERE cpf = :cpf OR email = :email LIMIT 1";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindValue(':cpf', $cpf_limpo);
    $stmt_check->bindValue(':email', $email);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "CPF ou E-mail já cadastrados."]);
        exit;
    }

    // 5. Criptografar a senha
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // 6. Inserir no banco (Com todos os campos do formulário)
    $sql = "INSERT INTO usuario (nome_completo, cpf, telefone, data_nascimento, uf, cidade, email, senha_hash) 
            VALUES (:nome, :cpf, :tel, :dt_nasc, :uf, :cidade, :email, :senha)";
    
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':nome', $nome_completo);
    $stmt->bindValue(':cpf', $cpf_limpo);
    $stmt->bindValue(':tel', $telefone_limpo);
    $stmt->bindValue(':dt_nasc', $data_nascimento);
    $stmt->bindValue(':uf', $uf);
    $stmt->bindValue(':cidade', $cidade);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senha_hash);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Cadastro realizado com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao salvar no banco de dados."]);
    }

} catch (PDOException $e) {
    // Em produção, não mostre $e->getMessage() para o usuário, grave num log.
    echo json_encode(["success" => false, "message" => "Erro interno do servidor. Tente novamente mais tarde."]);
    // error_log($e->getMessage()); // Recomendado para debugar
}
?>