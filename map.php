<?php
// --- mapa.php ---
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mapa de Pessoas Cadastradas</title>

  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    body { font-family: Arial, sans-serif; background-color: #f6f4f7; padding: 20px; }
    #map { height: 500px; width: 100%; border-radius: 10px; margin-top: 20px; }
    .filtros { display: flex; gap: 10px; flex-wrap: wrap; }
    select, button { padding: 8px; border-radius: 5px; border: 1px solid #ccc; }
    button { background-color: #5e17eb; color: white; border: none; cursor: pointer; }
    button:hover { background-color: #813cf0; }
    #info { margin-top: 10px; font-weight: bold; }
  </style>
</head>
<body>

  <h2>🔍 Buscar Pessoas Cadastradas no Mapa</h2>

  <div class="filtros">
    <select id="uf" required onchange="carregarCidades()">
      <option value="">Selecione a UF</option>
    </select>

    <select id="cidade" required disabled>
      <option value="">Selecione a UF primeiro</option>
    </select>

    <button onclick="buscar()">Buscar</button>
  </div>

  <div id="info"></div>
  <div id="map"></div>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    // 🗺️ Inicializa o mapa
    let map = L.map('map').setView([-14.2350, -51.9253], 4);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    let markersLayer = L.layerGroup().addTo(map);

    // ✅ Carrega UFs da API do IBGE
    async function carregarUFs() {
      const response = await fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');
      const estados = await response.json();
      const ufSelect = document.getElementById('uf');
      estados.forEach(uf => {
        const opt = document.createElement('option');
        opt.value = uf.sigla;
        opt.textContent = uf.nome;
        ufSelect.appendChild(opt);
      });
    }

    // ✅ Carrega cidades da UF selecionada
    async function carregarCidades() {
      const uf = document.getElementById('uf').value;
      const cidadeSelect = document.getElementById('cidade');
      cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
      cidadeSelect.disabled = true;

      if (!uf) {
        cidadeSelect.innerHTML = '<option value="">Selecione a UF primeiro</option>';
        return;
      }

      const response = await fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`);
      const cidades = await response.json();

      cidadeSelect.innerHTML = '<option value="">Selecione a Cidade</option>';
      cidades.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.nome;
        opt.textContent = c.nome;
        cidadeSelect.appendChild(opt);
      });

      cidadeSelect.disabled = false;
    }

    // ✅ Busca pessoas no banco (via PHP)
    async function buscar() {
      const uf = document.getElementById('uf').value;
      const cidade = document.getElementById('cidade').value;

      if (!uf || !cidade) {
        alert("Selecione UF e Cidade!");
        return;
      }

      const response = await fetch(`buscar.php?uf=${uf}&cidade=${cidade}`);
      const data = await response.json();

      markersLayer.clearLayers();

      if (data.length === 0) {
        document.getElementById('info').innerText = `Nenhuma pessoa encontrada em ${cidade}/${uf}`;
        return;
      }

      document.getElementById('info').innerText = `${data.length} pessoas encontradas em ${cidade}/${uf}`;

      data.forEach(p => {
        const marker = L.marker([p.latitude, p.longitude])
          .bindPopup(`<b>${p.nome}</b><br>${p.cidade}/${p.uf}`)
          .addTo(markersLayer);
      });

      map.setView([data[0].latitude, data[0].longitude], 12);
    }

    // 🚀 Executa ao carregar a página
    carregarUFs();
  </script>
</body>
</html>
