<?php
require 'includes/conexao.php';
session_start();

// Verifica se é admin
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
  header("Location: login.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = intval($_POST['id_user']);
  $nome = trim($_POST['nome']);
  $email = trim($_POST['email']);
  $telefone = trim($_POST['telefone']);
  $cidade = trim($_POST['cidade']);
  $uf = strtoupper(trim($_POST['uf']));
  $tipo_user = trim($_POST['tipo_user']);

  $stmt = $conn->prepare("UPDATE usuario SET nome_completo=?, email=?, telefone=?, cidade=?, uf=?, tipo_user=? WHERE id_user=?");
  $stmt->bind_param("ssssssi", $nome, $email, $telefone, $cidade, $uf, $tipo_user, $id);

  if ($stmt->execute()) {
    echo "<script>
      alert('Usuário atualizado com sucesso!');
      window.location.href='admin.php';
    </script>";
  } else {
    echo "<script>
      alert('Erro ao atualizar usuário.');
      window.location.href='a
