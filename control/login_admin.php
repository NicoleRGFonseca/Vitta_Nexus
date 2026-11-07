<?php
// control/login_admin.php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/conexao.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Defina aqui o único e-mail de administrador permitido
$emailUnicoAdmin = "admin@vittanexus.com";

if ($email !== $emailUnicoAdmin) {
    echo json_encode(["sucesso" => false, "mensagem" => "Acesso negado. E-mail não autorizado."]);
    exit;
}

try {
    // Busca o administrador único no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM administrador WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o admin existe e se a senha confere com o hash do banco
    if ($admin && password_verify($senha, $admin['senha_hash'])) {
        echo json_encode(["sucesso" => true, "mensagem" => "Login bem-sucedido!"]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Senha incorreta."]);
    }

} catch (PDOException $e) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro no servidor: " . $e->getMessage()]);
}
?>