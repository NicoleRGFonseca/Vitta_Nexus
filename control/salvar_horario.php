<?php
// control/salvar_horario.php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/conexao.php';

$id_medico = $_POST['id_medico'] ?? null;
$meses     = (int)($_POST['meses'] ?? 3);

if (empty($id_medico)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Selecione um médico."]);
    exit;
}

try {
    $dataInicio = new DateTime(); // Começa hoje
    $dataFim = clone $dataInicio;
    $dataFim->modify("+$meses months"); // Adiciona os meses escolhidos

    $intervalo = new DateInterval('P1D');
    $periodo = new DatePeriod($dataInicio, $intervalo, $dataFim);

    $inseridos = 0;

    // Prepara a query de inserção
    $sql = "INSERT IGNORE INTO horarios_disponiveis (id_medico, data_hora_inicio, data_hora_fim, status) 
            VALUES (?, ?, ?, 'disponivel')";
    $stmt = $pdo->prepare($sql);

    foreach ($periodo as $data) {
        $diaSemana = $data->format('N'); // 1 (Segunda) até 7 (Domingo)

        // Apenas dias úteis (Segunda a Sexta: 1 a 5)
        if ($diaSemana <= 5) {
            $dataStr = $data->format('Y-m-d');
            
            // Horário de atendimento: das 07:00 às 17:00
            $horaAtual = clone $data;
            $horaAtual->setTime(7, 0, 0);

            $horaLimite = clone $data;
            $horaLimite->setTime(17, 0, 0);

            while ($horaAtual < $horaLimite) {
                $inicioSlot = clone $horaAtual;
                $horaAtual->modify("+30 minutes");
                $fimSlot = clone $horaAtual;

                // Executa a inserção (INSERT IGNORE evita erro caso o horário já exista)
                $stmt->execute([
                    $id_medico,
                    $inicioSlot->format('Y-m-d H:i:s'),
                    $fimSlot->format('Y-m-d H:i:s')
                ]);

                if ($stmt->rowCount() > 0) {
                    $inseridos++;
                }
            }
        }
    }

    echo json_encode([
        "sucesso" => true, 
        "mensagem" => "Grade gerada com sucesso! Foram criados novos horários de segunda a sexta (07h às 17h)."
    ]);

} catch (PDOException $e) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao gerar horários: " . $e->getMessage()]);
}
?>                 