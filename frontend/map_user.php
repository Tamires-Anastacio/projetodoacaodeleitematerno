<?php
session_start();
require "../backend/includes/conexao.php";

// Se não estiver logado, redireciona
if (!isset($_SESSION['id_user'])) {
    header('Location: ../frontend/form_login.html');
    exit;
}

// Você pode acessar os dados do usuário aqui
$user_id = $_SESSION['id_user'];
$user_nome = $_SESSION['nome'];
$user_email = $_SESSION['email'];
$user_tipo = $_SESSION['tipo_user'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Mapa de Instituições</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
  body { font-family: Arial, sans-serif; margin: 16px; background:#f7f7fb; }
  #map { height: 560px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
</style>
</head>
<body>

<h2>Bem-vindo, <?php echo htmlspecialchars($user_nome); ?>!</h2>
<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
let map = L.map('map').setView([-14.2350, -51.9253], 4);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// Exemplo de marcador
L.marker([-14.2350, -51.9253]).addTo(map)
  .bindPopup("Instituição Exemplo")
  .openPopup();
</script>

</body>
</html>
