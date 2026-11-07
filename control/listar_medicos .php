<?php

require_once __DIR__ . '/../config/conexao.php';

$sql = "SELECT
            m.id_medico,
            m.id_esp,
            m.nome,
            m.crm,
            m.celular,
            m.email,
            e.nome AS nome_especialidade
        FROM medicos m
        INNER JOIN especialidades e
            ON m.id_esp = e.id_esp";

$stmt = $pdo->query($sql);

$medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);