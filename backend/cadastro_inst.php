<?php
require_once "includes/conexao.php"; // arquivo que conecta ao banco (PDO)

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $cnpj = trim($_POST["cnpj"]);
    $certificacao = trim($_POST["certificacao"]);
    $cidade = trim($_POST["cidade"]);
    $num = trim($_POST["num"]);
    $uf = trim($_POST["uf"]);
    $rua = trim($_POST["rua"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);
    $especialidade = trim($_POST["especialidade"]);

    // Validações simples
    if (empty($nome) || empty($cnpj) || empty($cidade) || empty($especialidade) || empty($telefone) || empty($email) || empty($uf) || empty($num) || empty($senha) || empty($rua)) {
        die("Por favor, preencha todos os campos obrigatórios!");
    }

    // Hash da senha (recomendado para segurança)
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT); // Hashing da senha para segurança

    try {
        $sql = "INSERT INTO instituicao (nome, cnpj, certificacao, cidade, num, uf, rua, senha, telefone, email, especialidade )
                VALUES (:nome, :cnpj, :certificacao, :cidade, :num, :uf, :rua, :senha_hash, :telefone, :email, :especialidade)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nome" => $nome,
            ":cnpj" => $cnpj,
            ":certificacao" => $certificacao,
            ":cidade" => $cidade,
            ":num" => $num,
            ":uf" => $uf,
            ":rua" => $rua,
            ":senha_hash" => $senha_hash,
            ":telefone" => $telefone,
            ":email" => $email,
            ":especialidade" => $especialidade
        ]);

        echo "<div style='text-align:center; margin-top:50px; font-size:20px; color:green;'>✅ Instituição cadastrada com sucesso!</div>";

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "CNPJ ou e-mail já cadastrado!";
        } else {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
