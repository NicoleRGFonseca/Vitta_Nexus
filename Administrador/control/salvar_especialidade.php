<?php
require_once 'config/conexao.php';

$nome = htmlspecialchars($_POST['nome']);

$sql = "INSERT INTO especialidades (nome) VALUES (:nome)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
     ':nome' => $nome
]);

header("location: listar_cards.php");
exit();

?>