<?php
// salvar_medico.php
header('Content-Type: application/json; charset=utf-8');
include 'config/conexao.php';

// Pega os dados enviados pelo formulário
$nome = $_POST['nome'] ?? '';
$crm = $_POST['crm'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$id_esp = $_POST['id_esp'] ?? null;
$telefone = $_POST['telefone'] ?? '';

// Validação simples
if (empty($nome) || empty($crm) || empty($email) || empty($senha) || empty($id_esp) || empty($telefone)  ) {
    echo json_encode(["sucesso" => false, "mensagem" => "Preencha todos os campos obrigatórios."]);
    exit;
}

try {
    // Criptografa a senha para gerar o senha_hash exigido no seu banco
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere na tabela 'medicos' do seu banco vita_nexus
    $sql = "INSERT INTO medicos (nome, crm, email, senha_hash, id_esp) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $crm, $email, $senhaHash, $id_esp, $telefone]);

    echo json_encode(["sucesso" => true, "mensagem" => "Médico cadastrado com sucesso no banco!"]);

} catch (PDOException $e) {
    // Se der erro (ex: CRM ou E-mail duplicado)
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar: " . $e->getMessage()]);
}
?>