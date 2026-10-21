<?php
require_once 'config/conexao.php';

$sql = "SELECT id_esp, nome FROM especialidades";

$stmt = $pdo->query($sql);
$especialidades = $stmt->fetchALL(PDO::FETCH_ASSOC);