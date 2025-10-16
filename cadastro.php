<?php
    require 'conexao.php'; 

    // Recebe os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $telefone = $_POST['fone'] ?? '';
    $datanascimento = $_POST['datanascimento'] ?? '';
    $email  = $_POST['email'] ?? '';
    $senha    = $_POST['senha'] ?? '';
    $uf = $_POST['uf'] ?? '';

    if ($nome && $cpf && $datanascimento && $email && $senha && $uf) {
        // Corrigido: Adicionada a vírgula entre 'email' e 'datanascimento'
        $sql = "INSERT INTO usuario(nome, cpf, email,telefone, datanascimento, senha, uf) 
                VALUES (:nome, :cpf, :email,:fone, :datanascimento, :senha, :uf)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':fone', $telefone);
        $stmt->bindParam(':datanascimento', $datanascimento);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha); 
        $stmt->bindParam(':uf', $uf);

        if ($stmt->execute()) {
            echo "<h1>Cadastro feito com sucesso!</h1>";
            header('location:login.php');
        } else {
            echo "<h1 style='color: var(--cor-vermelha);'>Erro ao cadastrar.</h1>";
        }
    } else {
        echo "<h1 style='color: var(--cor-vermelha);'>Preencha todos os campos.</h1>";
    }
?>
