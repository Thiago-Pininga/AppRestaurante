<?php
include 'conexao.php';

// Verifica se o ID foi passado para exclusão
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para excluir o produto
    $sql = "DELETE FROM produtos WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: index.php"); // Redireciona de volta para a lista de produtos
        } else {
            echo "Erro ao excluir o produto.";
        }
    }
}
?>
