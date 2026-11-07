<?php
                    // Query para buscar os horários cadastrados no banco
                    $sqlSlots = "SELECT h.id_horario, h.data_hora_inicio, h.data_hora_fim, h.status, m.nome AS nome_medico 
                        FROM horarios_disponiveis h
                        JOIN medicos m ON h.id_medico = m.id_medico
                        ORDER BY h.data_hora_inicio DESC";
                    $stmtSlots = $pdo->query($sqlSlots);
                    $horarios = $stmtSlots->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($horarios)):
                        foreach ($horarios as $slot):
                            $inicio = new DateTime($slot['data_hora_inicio']);
                            $fim = new DateTime($slot['data_hora_fim']);
                            
                            // Define classe CSS do status
                            $statusClass = 'waiting';
                            if ($slot['status'] === 'disponivel') $statusClass = 'confirmed';
                            if ($slot['status'] === 'bloqueado') $statusClass = 'cancelled';
                    ?>