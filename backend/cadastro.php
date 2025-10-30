<?php
require 'includes/conexao.php';

// Recebe os dados do formulário
$nome_completo           = $_POST['nome'] ?? '';
$cpf            = $_POST['cpf'] ?? '';
$data_nascimento = $_POST['data_nascimento'] ?? '';
$email          = $_POST['email'] ?? '';
$cidade         = $_POST['cidade'] ?? '';
$telefone       = $_POST['telefone'] ?? '';
$senha          = $_POST['senha'] ?? '';
$confsenha      = $_POST['confsenha'] ?? '';
$uf             = $_POST['uf'] ?? '';

// Verifica se todos os campos obrigatórios foram preenchidos
if ($nome_completo && $cpf && $data_nascimento && $email && $senha && $confsenha && $uf && $cidade) {

    // Confirma senhas
    if ($senha !== $confsenha) {
        echo "<h1 style='color:red;text-align:center;'>As senhas não coincidem!</h1>";
        exit;
    }

    // Sanitização
    $nome_completo = htmlspecialchars(trim($nome_completo), ENT_QUOTES, 'UTF-8');
    $cpf = preg_replace('/\D/', '', $cpf);
    $telefone = preg_replace('/\D/', '', $telefone);
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<h1 style='color:red;text-align:center;'>E-mail inválido.</h1>";
        exit;
    }

    // Verifica se o e-mail já existe
    // $check = $pdo->prepare("SELECT id FROM usuario WHERE email = :email");
    // $check->bindParam(':email', $email);
    // $check->execute();
    // if ($check->rowCount() > 0) {
    //     echo "<h1 style='color:red;text-align:center;'>E-mail já cadastrado!</h1>";
    //     exit;
    // }

    // --- Obtém latitude e longitude via OpenStreetMap ---
    function getLatLon($cidade, $uf) {
        $url = "https://nominatim.openstreetmap.org/search?city=" . urlencode($cidade) .
               "&state=" . urlencode($uf) .
               "&country=Brazil&format=json&limit=1";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'CadastroMapa/1.0 (seuemail@dominio.com)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        if (!$response) return [null, null];
        $data = json_decode($response, true);
    
        if (!empty($data[0])) {
            return [$data[0]['lat'], $data[0]['lon']];
        }
    
        return [null, null];
    }
    

    list($lat, $lon) = getLatLon($cidade, $uf);

    // --- Hash da senha ---
    $hash = password_hash($senha, PASSWORD_DEFAULT);

    // --- Inserção no banco ---
    $sql = "INSERT INTO usuario (nome_completo, cpf, data_nascimento, email, cidade, telefone, senha, uf, latitude, longitude)
            VALUES (:nome_completo, :cpf, :data_nascimento, :email, :cidade, :telefone, :senha, :uf, :lat, :lon)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_completo', $nome_completo);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':data_nascimento', $data_nascimento);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cidade', $cidade);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':senha', $hash);
    $stmt->bindParam(':uf', $uf);
    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lon', $lon);

    if ($stmt->execute()) {
        echo "<h1 style='color:green;text-align:center;'>Cadastro realizado com sucesso!</h1>";
        echo "<p style='text-align:center;'><a href='mapa.php'>Ver no mapa</a></p>";
    } else {
        echo "<h1 style='color:red;text-align:center;'>Erro ao cadastrar. Tente novamente.</h1>";
    }

} else {
    echo "<h1 style='color:red;text-align:center;'>Preencha todos os campos obrigatórios.</h1>";
}
?>
