<?php

session_start();
if (!isset($_SESSION['usuario']) && !isset($_COOKIE['usuario'])) {
    header("Location: login.php");
    exit;
}

include 'conexao.php';

// Atualiza todos os produtos: subtrai o valor de vendidos do estoque inicial e zera os vendidos
$sql = "UPDATE produtos SET quantidade_inicial = quantidade_inicial - vendidos, vendidos = 0";

if ($mysqli->query($sql)) {
    // Se a atualização ocorrer com sucesso, redireciona para o relatório com um parâmetro de sucesso
    header("Location: relatorio.php?finalizado=1");
    exit();
} else {
    // Em caso de erro, exibe a mensagem de erro
    echo "Erro ao finalizar o dia: " . $mysqli->error;
}
?>
