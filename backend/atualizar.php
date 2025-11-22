<?php
require 'conexao.php';

// ID do usuário que será atualizado
$id = 1;

// Novos valores
$novoNome = "João da Silva";
$novaCidade = "São Paulo";
$novoTelefone = "11999999999";

$sql = "UPDATE usuario 
        SET nome_completo = :nome,
            cidade = :cidade,
            telefone = :telefone
        WHERE id_user = :id";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':nome', $novoNome, PDO::PARAM_STR);
$stmt->bindParam(':cidade', $novaCidade, PDO::PARAM_STR);
$stmt->bindParam(':telefone', $novoTelefone, PDO::PARAM_STR);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

try {
    $stmt->execute();
    echo "Usuário atualizado com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao atualizar: " . $e->getMessage();
}
?>
