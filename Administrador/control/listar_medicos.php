<?php
require_once 'config/conexao.php';

$sql = "SELECT id_esp, nome FROM medicos";

$stmt = $pdo->query($sql);
$medicos = $stmt->fetchALL(PDO::FETCH_ASSOC);