
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>PAINEL ADMINISTRATIVO</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <style>
      body {
        background: #f0ecff;
        font-family: "Poppins", sans-serif;
      }
      .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      }
      .nav-link.active {
        background: #ec23ba !important;
        color: white !important;
        border-radius: 8px;
      }
      table {
        font-size: 0.9rem;
      }
      .table th, .table td {
        padding: 1rem;
      }
      .container {
        max-width: 1200px;
      }
      
    </style>
  </head>

  <body>
    <div class="container mt-4">
      <div class="card p-4">
        <h2 class="text-center mb-3 text-primary">PAINEL ADMINISTRATIVO</h2>

        <!-- ABAS -->
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              type="button"
              class="nav-link active"
              id="logins-tab"
              data-bs-toggle="tab"
              data-bs-target="#logins"
            >
              Histórico de Logins
            </button>
          </li>

          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="solicitacoes-tab"
              data-bs-toggle="tab"
              data-bs-target="#solicitacoes"
            >
              Solicitações
            </button>
          </li>

          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="usuarios-tab"
              data-bs-toggle="tab"
              data-bs-target="#usuarios"
            >
              Usuários
            </button>
          </li>

          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="inst-tab"
              data-bs-toggle="tab"
              data-bs-target="#instituicoes"
            >
              Instituições
            </button>
            
          </li>
        </ul>
        <!-- CONTEÚDO DAS ABAS -->
        <div class="tab-content">
          <!-- LOGIN -->
          <div class="tab-pane fade show active" id="logins">
            <h5> Histórico de Entradas</h5>
            <table class="table table-striped mt-3">
              <thead>
                <tr>
                  <th>Usuário</th>
                  <th>Data/Hora</th>
                  <th>IP</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($logins as $l): ?>
                <tr>
                  <td><?= htmlspecialchars($l['nome']) ?></td>
                  <td><?= $l['data'] ?></td>
                  <td><?= $l['ip'] ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- SOLICITAÇÕES -->
          <div class="tab-pane fade" id="solicitacoes">
            <h5> Solicitações</h5>
            <table class="table table-bordered mt-3">
              <thead>
                <tr>
                  <th>Usuário</th>
                  <th>Instituição</th>
                  <th>Tipo</th>
                  <th>Observação</th>
                  <th>Data</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($solicitacoes as $s): ?>
                <tr>
                  <td><?= htmlspecialchars($s['nome_user']) ?></td>
                  <td><?= htmlspecialchars($s['nome_inst']) ?></td>
                  <td><?= strtoupper($s['tipo_solicitacao']) ?></td>
                  <td><?= htmlspecialchars($s['observacao']) ?></td>
                  <td><?= $s['data'] ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- USUÁRIOS -->
          <div class="tab-pane fade" id="usuarios">
            <h5> Usuários Cadastrados</h5>
            <table class="table table-hover mt-3">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>Cidade</th>
                  <th>UF</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                  <td><?= htmlspecialchars($u['nome']) ?></td>
                  <td><?= htmlspecialchars($u['email']) ?></td>
                  <td><?= $u['cidade'] ?></td>
                  <td><?= $u['uf'] ?></td>
                  <td><?= $u['tipo'] ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- INSTITUIÇÕES -->
          <div class="tab-pane fade" id="instituicoes">
            <h5> Instituições</h5>
            <table class="table table-striped mt-3">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Cidade</th>
                  <th>UF</th>
                  <th>Contato</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($instituicoes as $i): ?>
                <tr>
                  <td><?= htmlspecialchars($i['nome']) ?></td>
                  <td><?= $i['cidade'] ?></td>
                  <td><?= $i['uf'] ?></td>
                  <td><?= $i['telefone'] ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
