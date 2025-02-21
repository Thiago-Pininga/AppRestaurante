<?php
include 'conexao.php';

// Verifica se o ID foi passado para exclusão
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Inicia a transação para garantir integridade
    $mysqli->begin_transaction();

    try {
        // Remove as vendas associadas ao produto
        $sql_vendas = "DELETE FROM registro_vendas WHERE produto_id = ?";
        $stmt_vendas = $mysqli->prepare($sql_vendas);
        $stmt_vendas->bind_param("i", $id);
        $stmt_vendas->execute();

        // Agora, exclui o produto
        $sql_produto = "DELETE FROM produtos WHERE id = ?";
        $stmt_produto = $mysqli->prepare($sql_produto);
        $stmt_produto->bind_param("i", $id);
        $stmt_produto->execute();

        // Se a exclusão for bem-sucedida, confirma a transação
        $mysqli->commit();

        // Redireciona de volta para a lista de produtos
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        // Se ocorrer algum erro, desfaz a transação
        $mysqli->rollback();
        echo "Erro ao excluir o produto: " . $e->getMessage();
    }
}
?>
