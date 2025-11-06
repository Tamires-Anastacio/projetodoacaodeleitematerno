<?php
require_once "includes/conexao.php"; // arquivo que conecta ao banco (PDO)

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim(string: $_POST["nome"]);
    $cnpj = trim(string: $_POST["cnpj"]);
    $certificacao = trim(string: $_POST["certificacao"]);
    $cidade = trim(string: $_POST["cidade"]);
    $num = trim(string: $_POST["num"]);
    $uf = trim(string: $_POST["uf"]);
    $rua = trim(string: $_POST["rua"]);
    $telefone = trim(string: $_POST["telefone"]);
    $email = trim(string: $_POST["email"]);

    // validações simples
    if (empty($nome) || empty($cnpj) || empty($cidade) || empty($uf) || empty($num) || empty($rua)) {
        die(" Por favor, preencha todos os campos obrigatórios!");
    }

    try {
        $sql = "INSERT INTO instituicao (nome, cnpj, certificacao, cidade, num, uf, rua, telefone, email)
                VALUES (:nome, :cnpj, :certificacao, :cidade, :num, :uf, :rua, :telefone, :email)";
        $stmt = $pdo->prepare(query: $sql);

        $stmt->execute(params: [
            ":nome" => $nome,
            ":cnpj" => $cnpj,
            ":certificacao" => $certificacao,
            ":cidade" => $cidade,
            ":num" => $num,
            ":uf" => $uf,
            ":rua" => $rua,
            ":telefone" => $telefone,
            ":email" => $email
        ]);

        echo "<div style='text-align:center; margin-top:50px; font-size:20px; color:green;'>
                ✅ Instituição cadastrada com sucesso!
              </div>";
    } catch (PDOException $e) {
        if (str_contains(haystack: $e->getMessage(), needle: 'Duplicate entry')) {
            echo " CNPJ ou email já cadastrado!";
        } else {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
