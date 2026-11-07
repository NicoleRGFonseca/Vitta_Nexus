<?php
// control/salvar_horario.php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/conexao.php';

$id_medico  = $_POST['id_medico'] ?? null;
$data       = $_POST['data'] ?? '';
$hora_inicio= $_POST['hora_inicio'] ?? '';
$duracao    = (int)($_POST['duracao'] ?? 30);

if (empty($id_medico) || empty($data) || empty($hora_inicio)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Preencha todos os campos."]);
    exit;
}

try {
    // Monta a string de início em formato DATETIME
    $startString = "$data $hora_inicio:00";
    $dtInicio = new DateTime($startString);
    
    // Calcula o final somando a duração
    $dtFim = clone $dtInicio;
    $dtFim->modify("+$duracao minutes");

    $sql = "INSERT INTO horarios_disponiveis (id_medico, data_hora_inicio, data_hora_fim, status) 
            VALUES (?, ?, ?, 'disponivel')";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $id_medico,
        $dtInicio->format('Y-m-d H:i:s'),
        $dtFim->format('Y-m-d H:i:s')
    ]);

    echo json_encode(["sucesso" => true, "mensagem" => "Horário adicionado com sucesso!"]);

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(["sucesso" => false, "mensagem" => "Este médico já possui esse horário cadastrado."]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar: " . $e->getMessage()]);
    }
}
?>