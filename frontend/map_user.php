<?php
session_start();
require "../backend/includes/conexao.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['nome']) || !isset($_SESSION['email']) || !isset($_SESSION['tipo_user'])) {
    session_unset();
    session_destroy();
    header('Location: ../frontend/index.html');
    exit;
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - Instituições Próximas</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
  body { font-family: Arial, sans-serif; margin: 16px; background:#f7f7fb; }
  #map { height: 560px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
  .controls { display:flex; gap:8px; margin-bottom:10px; flex-wrap:wrap; }
  select, button { padding:8px 10px; border-radius:6px; border:1px solid #ccc; background:white; }
  button { background:#5e17eb; color:white; border:none; cursor:pointer; }
  .summary { margin-bottom:8px; font-weight:600; }

  #bottom-panel {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      background: #ffffff;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.15);
      padding: 12px;
      max-height: 260px;
      overflow-y: auto;
      border-radius: 12px 12px 0 0;
  }
  .card-inst {
      background: #f5f2ff;
      border-left: 4px solid #5e17eb;
      padding: 10px 12px;
      border-radius: 8px;
      margin-bottom: 8px;
      font-size: 14px;
  }
  .card-inst b { color: #5e17eb; }
  .btn-rota {
      background: #5e17eb;
      color: #fff;
      border: none;
      padding: 6px 10px;
      border-radius: 6px;
      margin-top: 6px;
      cursor: pointer;
  }
</style>
</head>
<body>

<h2>Instituições próximas</h2>
<div class="summary" id="summary">Carregando...</div>

<div class="controls">
  <select id="uf" onchange="carregarCidades()"><option value="">UF</option></select>
  <select id="cidade" disabled><option>Cidade</option></select>
  <button onclick="buscarManual()">Buscar</button>
  <button onclick="carregarProximas()">Usar minha localização</button>
  <button onclick="buscarExternas()">Buscar instituições públicas</button>
</div>

<div id="map"></div>

<div id="bottom-panel">
    <div id="lista"></div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>

let map = L.map('map').setView([-14.2350, -51.9253], 4);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
let markers = L.layerGroup().addTo(map);

const summary = document.getElementById('summary');
const lista = document.getElementById('lista');

// FUNÇÃO TRAÇAR ROTA
function abrirRota(lat, lng) {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(url, "_blank");
}

// carregar UFs
async function carregarUFs() {
  const r = await fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');
  const estados = await r.json();
  estados.forEach(e => uf.insertAdjacentHTML("beforeend", `<option value="${e.sigla}">${e.nome}</option>`));
}

async function carregarCidades() {
  const uf = document.getElementById('uf').value;
  const cidade = document.getElementById('cidade');
  cidade.disabled = true;
  cidade.innerHTML = "<option>Carregando...</option>";

  const r = await fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`);
  const cidades = await r.json();

  cidade.innerHTML = '<option value="">Selecione</option>';
  cidades.forEach(c => cidade.insertAdjacentHTML("beforeend", `<option value="${c.nome}">${c.nome}</option>`));
  cidade.disabled = false;
}

// pega localização do usuário (via banco)
async function getUserLocation() {
  const r = await fetch("../backend/get_user_location.php");
  return await r.json();
}

// carregar instituições cadastradas próximas
async function carregarProximas() {
  markers.clearLayers();
  lista.innerHTML = "";
  summary.innerText = "Buscando sua localização...";

  const user = await getUserLocation();

  if (!user || user.error) {
    summary.innerText = "Não foi possível obter suas coordenadas.";
    return;
  }

  const lat = user.latitude;
  const lng = user.longitude;

  summary.innerText = "Buscando instituições próximas...";

  const r = await fetch(`../backend/buscar_instituicoes.php?lat=${lat}&lng=${lng}&raio=10`);
  const insts = await r.json();

  insts.forEach(i => {
    L.marker([i.latitude, i.longitude]).addTo(markers)
      .bindPopup(`<b>${i.nome}</b><br>${i.rua || ""} ${i.num || ""}<br>${i.cidade}/${i.uf}`);

    lista.insertAdjacentHTML("beforeend", `
      <div class="card-inst">
        <b>${i.nome}</b><br>
        <small>ID: ${i.id_instituicao}</small><br>
        ${i.rua ?? ''} ${i.num ?? ''}<br>
        ${i.cidade} - ${i.uf}<br>
        <button class="btn-rota" onclick="abrirRota(${i.latitude}, ${i.longitude})">Traçar rota</button>
      </div>
    `);
  });

  map.setView([lat, lng], 13);
  summary.innerText = `${insts.length} instituições próximas encontradas.`;
}

// BUSCA MANUAL
async function buscarManual() {
  const uf = document.getElementById('uf').value;
  const cidade = document.getElementById('cidade').value;

  if (!uf || !cidade) return alert("Selecione UF e cidade.");

  markers.clearLayers();
  lista.innerHTML = "";

  summary.innerText = `Buscando instituições em ${cidade}/${uf}...`;

  const r = await fetch(`../backend/buscar_instituicoes.php?uf=${uf}&cidade=${cidade}`);
  const insts = await r.json();

  insts.forEach(i => {
    L.marker([i.latitude, i.longitude]).addTo(markers)
      .bindPopup(`<b>${i.nome}</b><br>${i.rua || ""} ${i.num || ""}<br>${i.cidade}/${i.uf}`);

    lista.insertAdjacentHTML("beforeend", `
      <div class="card-inst">
        <b>${i.nome}</b><br>
        <small>ID: ${i.id_instituicao}</small><br>
        ${i.rua ?? ''} ${i.num ?? ''}<br>
        ${i.cidade} - ${i.uf}<br>
        <button class="btn-rota" onclick="abrirRota(${i.latitude}, ${i.longitude})">Traçar rota</button>
      </div>
    `);
  });

  if (insts[0]) map.setView([insts[0].latitude, insts[0].longitude], 12);

  summary.innerText = `${insts.length} instituições encontradas.`;
}

// BUSCA EXTERNA (SUS / HOSPITAIS)
async function buscarExternas() {
  const uf = document.getElementById('uf').value;
  const cidade = document.getElementById('cidade').value;

  if (!cidade) return alert("Selecione uma cidade.");

  markers.clearLayers();
  lista.innerHTML = "";

  summary.innerText = "Buscando instituições públicas...";

  const r = await fetch(`../backend/buscar_externas.php?cidade=${cidade}&uf=${uf}`);
  const itens = await r.json();

  itens.forEach(it => {
    const lat = parseFloat(it.lat);
    const lon = parseFloat(it.lon);
    if (!lat || !lon) return;

    L.marker([lat, lon]).addTo(markers)
      .bindPopup(`<b>${it.nome}</b><br>Tipo: ${it.type}`);

    lista.insertAdjacentHTML("beforeend", `
      <div class="card-inst">
        <b>${it.nome}</b><br>
        <small>EXTERNA (SUS)</small><br>
        ${it.type}<br>
        <button class="btn-rota" onclick="abrirRota(${lat}, ${lon})">Traçar rota</button>
      </div>
    `);
  });

  if (itens[0]) map.setView([parseFloat(itens[0].lat), parseFloat(itens[0].lon)], 12);

  summary.innerText = `${itens.length} instituições públicas encontradas.`;
}

// Inicialização
carregarUFs();
carregarProximas();

</script>
</body>
</html>
