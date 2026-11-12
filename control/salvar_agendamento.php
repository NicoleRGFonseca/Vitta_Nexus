<?php

header('Content-Type: application/json; charset=utf-8');
include '../config/conexao.php'; 

// Dados enviados pelo formulário do paciente
$id_horario = $_POST['id_horario'] ?? null;
// Numa aplicação real com login, o id_paciente viria da sessão ($_SESSION['id_paciente'])
$id_paciente = $_POST['id_paciente'] ?? 1; 

if (empty($id_horario)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Selecione um horário válido."]);
    exit;
}

try {
    // Inicia uma transação para garantir consistência e evitar conflitos simultâneos
    $pdo->beginTransaction();

    // Passo 1: Verificar se o horário ainda está disponível na tabela 'horarios_disponiveis'
    $verificacao = $pdo->prepare("SELECT status FROM horarios_disponiveis WHERE id_horario = ? FOR UPDATE");
    $verificacao->execute([$id_horario]);
    $horario = $verificacao->fetch(PDO::FETCH_ASSOC);

    if (!$horario || $horario['status'] !== 'disponivel') {
        $pdo->rollBack();
        echo json_encode(["sucesso" => false, "mensagem" => "Erro: Este horário já foi reservado por outra pessoa."]);
        exit;
    }

    // Passo 2: Inserir a marcação na tabela 'consultas'
    // A tabela consultas possui 'id_horario INT NOT NULL UNIQUE' (conforme seu banco_2.sql)
    $insercao = $pdo->prepare("INSERT INTO consultas (id_horario, id_paciente, status) VALUES (?, ?, 'agendada')");
    $insercao->execute([$id_horario, $id_paciente]);

    // Passo 3: Atualizar o status do horário para 'reservado'
    $atualizacao = $pdo->prepare("UPDATE horarios_disponiveis SET status = 'reservado' WHERE id_horario = ?");
    $atualizacao->execute([$id_horario]);

    // Se tudo correr bem, efetiva as alterações no banco
    $pdo->commit();

    echo json_encode(["sucesso" => true, "mensagem" => "Agendamento realizado com sucesso!"]);

} catch (PDOException $e) {
    // Se ocorrer qualquer erro, desfaz as alterações pendentes
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // O código 23000 indica violação de restrição UNIQUE (se outra requisição inseriu o mesmo id_horario milissegundos antes)
    if ($e->getCode() == 23000) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro: Alguém acabou de reservar este horário. Tente novamente."]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro no sistema: " . $e->getMessage()]);
    }
}
?>