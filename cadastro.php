 <?php
            require 'conexao.php'; 

            // Recebe os dados do formulário
            $nome = $_POST['nome'] ?? '';
            $cpf = $_POST['cpf'] ?? '';
            $datanascimento = $_POST['datanascimento'] ?? '';
            $email  = $_POST['email'] ?? '';
            $senha    = $_POST['senha'] ?? '';
            $uf = $_POST['uf'] ?? '';
           

            if ($nome && $cpf && $datanascimento && $email && $senha && $uf) {
                $sql = "INSERT INTO usuarios (nome,cpf, datanascimento,email,senha,uf) 
                        VALUES (:nome ,:cpf,:datanascimento,:email,:senha,:uf)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':datanascimento', $datanascimento);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':senha', $senha); 
                $stmt->bindParam(':uf', $uf);
                

                if ($stmt->execute()) {
                    echo "<h1>Cadastro feito com sucesso!</h1>";
                } else {
                    echo "<h1 style='color: var(--cor-vermelha);'>Erro ao cadastrar.</h1>";
                }
            } else {
                echo "<h1 style='color: var(--cor-vermelha);'>Preencha todos os campos.</h1>";
            }
    ?>