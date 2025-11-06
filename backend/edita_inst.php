<?php
require 'includes/conexao.php';
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
  header(header: "Location: login.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = intval(value: $_POST['id_instituicao']);
  $nome = trim(string: $_POST['nome']);
  $cidade = trim(string: $_POST['cidade']);
  $uf = strtoupper(trim($_POST['uf']));
  $rua = trim(string: $_POST['rua']);
  $num = intval($_POST['num']);
  $cert = trim($_POST['certificacao']);

  $stmt = $conn->prepare("UPDATE instituicao SET nome=?, cidade=?, uf=?, rua=?, num=?, certificacao=? WHERE id_instituicao=?");
  $stmt->bind_param("ssssisi", $nome, $cidade, $uf, $rua, $num, $cert, $id);

  if ($stmt->execute()) {
    echo "<script>
      alert('Instituição atualizada com sucesso!');
      window.location.href='admin.php';
    </script>";
  } else {
    echo "<script>
      alert('Erro ao atualizar instituição.');
      window.location.href='admin.php';
    </script>";
  }

  $stmt->close();
}
$conn->close();
?>
