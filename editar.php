<?php
include 'conexao.php';

// Verifica se o ID do produto foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para buscar os dados do produto
    $sql = "SELECT * FROM produtos WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $produto = $result->fetch_assoc();
        $stmt->close();
    }
}

// Verifica se o formulário de edição foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $e_comida = $_POST['e_comida'];
    $quantidade_inicial = $_POST['quantidade_inicial'];
    $valor = $_POST['valor'];
    $imagem = $produto['imagem']; // Mantém a imagem atual caso não altere

    // Verifica se o usuário optou por carregar uma nova imagem
    if (!empty($_FILES['imagem']['name'])) {
        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_nome = strtolower(str_replace(" ", "_", $imagem_nome));
        $diretorio = 'imagens/';
        $caminho_imagem = $diretorio . $nome . '.' . pathinfo($imagem_nome, PATHINFO_EXTENSION);
        
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem)) {
            $imagem = $caminho_imagem; // Atualiza a imagem no banco
        } else {
            $mensagem = "Erro ao fazer upload da imagem.";
        }
    } elseif (!empty($_POST['link_imagem'])) {
        $imagem = $_POST['link_imagem']; // Atualiza o link da imagem
    }

    // Prepara a consulta para atualizar os dados no banco
    $sql = "UPDATE produtos SET nome = ?, e_comida = ?, quantidade_inicial = ?, valor = ?, imagem = ? WHERE id = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssiisi", $nome, $e_comida, $quantidade_inicial, $valor, $imagem, $id);
        
        if ($stmt->execute()) {
            $mensagem = "Produto atualizado com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar o produto.";
        }
        $stmt->close();
    } else {
        $mensagem = "Erro na preparação da consulta.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php
    $pagina = 'Editar'; // Página ativa
    include 'header.php'; // Inclui o header com o menu
    ?>

    <div class="container">
        <h1>Editar Produto</h1>

        <?php if (isset($mensagem)): ?>
            <p class="mensagem"><?= $mensagem ?></p>
        <?php endif; ?>

        <form action="editar.php?id=<?= $produto['id'] ?>" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>

            <label for="e_comida">Tipo:</label>
            <select name="e_comida" id="e_comida" required>
                <option value="1" <?= $produto['e_comida'] == 1 ? 'selected' : '' ?>>Comida</option>
                <option value="0" <?= $produto['e_comida'] == 0 ? 'selected' : '' ?>>Bebida</option>
            </select>

            <label for="quantidade_inicial">Quantidade Inicial:</label>
            <input type="number" name="quantidade_inicial" id="quantidade_inicial" value="<?= $produto['quantidade_inicial'] ?>" required>

            <label for="valor">Valor (R$):</label>
            <input type="text" name="valor" id="valor" value="<?= $produto['valor'] ?>" required>

            <label for="imagem">Imagem do Produto:</label>
            <input type="file" name="imagem" id="imagem" accept="image/*">
            
            <p>Ou adicione um link para a imagem:</p>
            <input type="url" name="link_imagem" id="link_imagem" value="<?= $produto['imagem'] ?>" placeholder="http://exemplo.com/imagem.jpg">

            <button type="submit">Atualizar Produto</button>
        </form>
    </div>

</body>
</html>
