<?php
include 'conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $e_comida = ($_POST['tipo'] == 'comida') ? 1 : 0; // 1 para comida, 0 para bebida
    $quantidade_inicial = $_POST['quantidade_inicial'];
    $valor = $_POST['valor'];
    $estoque_minimo = $_POST['estoque_minimo']; // Novo campo
    $imagem = '';

    // Verifica se o usuário optou por carregar uma imagem
    if (!empty($_FILES['imagem']['name'])) {
        $imagem_nome = strtolower(str_replace(" ", "_", $_FILES['imagem']['name']));
        $diretorio = 'imagens/';
        $caminho_imagem = $diretorio . $nome . '.' . pathinfo($imagem_nome, PATHINFO_EXTENSION);

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem)) {
            $imagem = $caminho_imagem;
        } else {
            $mensagem = "Erro ao fazer upload da imagem.";
        }
    } elseif (!empty($_POST['link_imagem'])) {
        $imagem = $_POST['link_imagem'];
    }

    // Prepara a consulta para inserir o produto no banco
    $sql = "INSERT INTO produtos (nome, e_comida, quantidade_inicial, valor, estoque_minimo, imagem) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssiiss", $nome, $e_comida, $quantidade_inicial, $valor, $estoque_minimo, $imagem);

        if ($stmt->execute()) {
            $mensagem = "Produto adicionado com sucesso!";
        } else {
            $mensagem = "Erro ao adicionar o produto.";
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
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php
    $pagina = 'Adicionar'; // Página ativa
    include 'header.php';
    ?>

    <div class="container">
        <h1>Adicionar Novo Produto</h1>

        <?php if (isset($mensagem)): ?>
            <p class="mensagem"><?= $mensagem ?></p>
        <?php endif; ?>

        <form action="adicionar.php" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="tipo">Tipo:</label>
            <select name="tipo" id="tipo" required>
                <option value="comida">Comida</option>
                <option value="bebida">Bebida</option>
            </select>

            <label for="quantidade_inicial">Quantidade Inicial:</label>
            <input type="number" name="quantidade_inicial" id="quantidade_inicial" required>

            <label for="estoque_minimo">Estoque Mínimo:</label>
            <input type="number" name="estoque_minimo" id="estoque_minimo" required>

            <label for="valor">Valor (R$):</label>
            <input type="text" name="valor" id="valor" required>

            <label for="imagem">Imagem do Produto:</label>
            <input type="file" name="imagem" id="imagem" accept="image/*">

            <p>Ou adicione um link para a imagem:</p>
            <input type="url" name="link_imagem" id="link_imagem" placeholder="http://exemplo.com/imagem.jpg">

            <button type="submit">Adicionar Produto</button>
        </form>
    </div>

</body>
</html>
