<?php
session_start();
require "../backend/includes/conexao.php";

// Verifica se a sessão está definida corretamente (usuário logado)
if( (!isset($_SESSION['id'])== true) and (!isset($_SESSION['nome'])==true) and (!isset($_SESSION['email'])==true)) {
    // Caso algum campo de sessão não esteja definido, destrói a sessão e redireciona para a página inicial
    unset($_SESSION['id']);
    unset($_SESSION['nome']);
    unset($_SESSION['email']);

    header('Location:login_user.php');

}

// Caso a sessão esteja válida, exibe o conteúdo restrito (exemplo)
// Adicione aqui o código da página restrita, que só deve ser acessada se o usuário estiver logado.

$sql = "SELECT * FROM notificacao WHERE id_instituicao = ? AND lida = 0 ORDER BY data_envio DESC";
// $stmt = prepare($sql);
// $stmt->bind_param("i", $id_instituicao);
// $stmt->execute();
// $result = $stmt->get_result();

// while($n = $result->fetch_assoc()) {
//     echo "<div class='alert alert-info'>
//             🔔 {$n['mensagem']} 
//             <a href='solicitacoes.php' class='btn btn-sm btn-outline-primary'>Ver</a>
//           </div>";
// }
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <nav style="background: #5e17eb; color: white; padding: 10px;">
  <span style="font-size: 20px;">🏥 Painel da Instituição</span>
  <div style="float: right; position: relative;">
    <button id="btnNotificacao" 
            style="background: none; border: none; color: white; font-size: 25px; cursor: pointer;">
      🔔
      <span id="contador" 
            style="background: red; color: white; border-radius: 50%; padding: 3px 8px; 
                   font-size: 12px; position: absolute; top: -5px; right: -10px; display: none;">
      </span>
    </button>
  </div>
</nav>

<div id="notificacoes" style="display:none; background:white; 
                              position:absolute; top:60px; right:10px; 
                              width:300px; border-radius:8px; 
                              box-shadow:0 2px 10px rgba(0,0,0,0.1); padding:10px;">
</div>
<script>
async function carregarNotificacoes() {
  const res = await fetch('notificacoes_ajax.php');
  const notificacoes = await res.json();

  const contador = document.getElementById('contador');
  const divNotif = document.getElementById('notificacoes');

  if (notificacoes.length > 0) {
    contador.style.display = 'inline';
    contador.innerText = notificacoes.length;

    divNotif.innerHTML = notificacoes.map(n => `
      <div style="border-bottom: 1px solid #ddd; padding: 8px;">
        <b>Nova solicitação!</b><br>
        <span>${n.mensagem}</span><br>
        <a href="solicitacoes.php" class="btn btn-sm btn-primary">Ver</a>
      </div>
    `).join('');
  } else {
    contador.style.display = 'none';
    divNotif.innerHTML = '<p style="text-align:center; color:#777;">Nenhuma nova notificação.</p>';
  }
}

// Atualiza a cada 5 segundos
setInterval(carregarNotificacoes, 5000);

// Exibir / ocultar lista ao clicar no sino
document.getElementById('btnNotificacao').addEventListener('click', () => {
  const box = document.getElementById('notificacoes');
  box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
});

// Chama a primeira vez
carregarNotificacoes();
</script>

  
</body>
</html>