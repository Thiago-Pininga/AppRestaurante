<?php
include 'conexao.php';

// Obtém os dados enviados via AJAX
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['vendidos'])) {
    $id = $data['id'];
    $vendidos = $data['vendidos'];

    // Atualiza a quantidade vendida no banco de dados
    $sql = "UPDATE produtos SET vendidos = ? WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ii", $vendidos, $id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
