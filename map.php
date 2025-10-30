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
    #listaPessoas {
      margin-top: 20px;
      background-color: white;
      border-radius: 8px;
      padding: 15px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .pessoa-item {
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid #eee;
      padding: 10px 0;
    }
    .pessoa-item img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
    }
    .pessoa-item span {
      font-weight: bold;
      color: #333;
    }
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
  <div id="listaPessoas"></div>

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

    // ✅ Busca pessoas no banco e mostra no mapa + lista
    async function buscar() {
      const uf = document.getElementById('uf').value;
      const cidade = document.getElementById('cidade').value;
      const listaDiv = document.getElementById('listaPessoas');

      if (!uf || !cidade) {
        alert("Selecione UF e Cidade!");
        return;
      }

      const response = await fetch(`buscar.php?uf=${uf}&cidade=${cidade}`);
      const data = await response.json();

      markersLayer.clearLayers();
      listaDiv.innerHTML = ""; // limpa lista anterior

      if (data.length === 0) {
        document.getElementById('info').innerText = `Nenhuma pessoa encontrada em ${cidade}/${uf}`;
        listaDiv.innerHTML = "<p>Nenhuma pessoa encontrada.</p>";
        return;
      }

      document.getElementById('info').innerText = `${data.length} pessoas encontradas em ${cidade}/${uf}`;

      data.forEach(p => {
        // Cria marcador no mapa
        const foto = p.foto ? `" alt="${p.nome_completo}" style="width: 80px; height: 80px; border-radius: 50%;">` : '';
        L.marker([p.latitude, p.longitude])
          .bindPopup(`<b>${p.nome_completo}</b><br>${p.cidade}/${p.uf}`)
          .addTo(markersLayer);

        // Adiciona item na lista abaixo do mapa
        const item = document.createElement("div");
        item.classList.add("pessoa-item");
        item.innerHTML = `
          ${p.foto ? ` alt="${p.nome_completo}">` : ''}
          <span>${p.nome_completo}</span> — ${p.cidade}/${p.uf}
        `;
        listaDiv.appendChild(item);
      });

      map.setView([data[0].latitude, data[0].longitude], 12);
    }

    carregarUFs();
  </script>
</body>
</html>
