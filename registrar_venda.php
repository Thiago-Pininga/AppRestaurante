<?php
include 'conexao.php';

$produto_id = $_POST['produto_id'];
$quantidade = $_POST['quantidade'];

// Busca o horário da última venda do produto
$sql = "SELECT horario FROM registro_vendas WHERE produto_id = ? ORDER BY horario DESC LIMIT 1";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $produto_id);
$stmt->execute();
$result = $stmt->get_result();
$ultima_venda = $result->fetch_assoc();
$stmt->close();

$registrar = false;

if (!$ultima_venda) {
    // Nunca houve venda desse produto, pode registrar
    $registrar = true;
} else {
    $ultima_hora = strtotime($ultima_venda['horario']);
    $agora = time();

    if (($agora - $ultima_hora) >= 600) { // 600 segundos = 10 minutos
        $registrar = true;
    }
}

if ($registrar) {
    $sql = "INSERT INTO registro_vendas (produto_id, quantidade) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $produto_id, $quantidade);

    if ($stmt->execute()) {
        echo "Venda registrada!";
    } else {
        echo "Erro ao registrar.";
    }

    $stmt->close();
} else {
    echo "Menos de 10 minutos desde a última venda.";
}

$mysqli->close();
?>
