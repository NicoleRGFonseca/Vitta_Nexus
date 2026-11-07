<?php
require_once 'config/conexao.php';

$sql = "SELECT m.*, e.nome AS nome_especialidade 
        FROM medicos m 
        JOIN especialidades e ON m.id_esp = e.id_esp";

$stmt = $pdo->query($sql);
$especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);