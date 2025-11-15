<?php
    require 'includes/conexao.php';
    $id = 1;
    $sql = "DELETE FROM usuario WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
    echo "Usuario excluído com sucesso!";
    } else {
    echo "Erro ao excluir usuario.";
    }
?>