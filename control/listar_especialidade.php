<?php
// control/listar_especialidade.php
require_once 'config/conexao.php';

$sql = "SELECT id_esp, nome FROM especialidades ORDER BY nome ASC";
$stmt = $pdo->query($sql);
$especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);