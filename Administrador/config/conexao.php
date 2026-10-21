<?php
// conexao.php (Usando PDO)
$host = "localhost";
$banco = "vitta_nexus";
$usuario = "root";
$senha = "";

try {
    // Cria a conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Se der erro na conexão, retorna um JSON informando
    echo json_encode(["sucesso" => false, "mensagem" => "Erro na conexão: " . $e->getMessage()]);
    exit;
}
?>