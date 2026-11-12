<?php
// control/salvar_especialidade.php
require_once '../config/conexao.php';

header('Content-Type: application/json');

try {
    if (isset($_POST['nome']) && !empty(trim($_POST['nome']))) {
        $nome = trim($_POST['nome']);

        $sql = "INSERT INTO especialidades (nome) VALUES (:nome)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nome' => $nome]);

        $novoId = $pdo->lastInsertId();

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Especialidade cadastrada com sucesso!',
            'id' => $novoId,
            'nome' => $nome
        ]);
    } else {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'O nome da especialidade é obrigatório.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao cadastrar (talvez esta especialidade já exista).'
    ]);
}
exit();
?>