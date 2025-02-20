<?php
include 'conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $e_comida = ($_POST['tipo'] == 'comida') ? 1 : 0; // 1 para comida, 0 para bebida
    $quantidade_inicial = $_POST['quantidade_inicial'];
    $valor = $_POST['valor'];
    $imagem = '';

    // Verifica se o usuário optou por carregar uma imagem
    if (!empty($_FILES['imagem']['name'])) {
        // Obtém o nome da imagem
        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_nome = strtolower(str_replace(" ", "_", $imagem_nome));
        
        // Define o diretório e o caminho
        $diretorio = 'imagens/';
        $caminho_imagem = $diretorio . $nome . '.' . pathinfo($imagem_nome, PATHINFO_EXTENSION);

        // Move a imagem para o diretório
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem)) {
            $imagem = $caminho_imagem; // Armazena o caminho no banco
        } else {
            $mensagem = "Erro ao fazer upload da imagem.";
        }
    } elseif (!empty($_POST['link_imagem'])) {
        // Se for link de imagem, armazena o link
        $imagem = $_POST['link_imagem'];
    }

    // Prepara a consulta para inserir o produto no banco
    $sql = "INSERT INTO produtos (nome, e_comida, quantidade_inicial, valor, imagem) 
            VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssiis", $nome, $e_comida, $quantidade_inicial, $valor, $imagem);

        // Executa a consulta
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
    include 'header.php'; // Inclui o header com o menu
    ?>

    <div class="container">
        <h1>Adicionar Novo Produto</h1>

        <!-- Exibe a mensagem se houver -->
        <?php if (isset($mensagem)): ?>
            <p class="mensagem"><?= $mensagem ?></p>
        <?php endif; ?>

        <!-- Formulário de Adicionar Produto -->
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

            <label for="valor">Valor (R$):</label>
            <input type="text" name="valor" id="valor" required>

            <!-- Opção de adicionar imagem -->
            <label for="imagem">Imagem do Produto:</label>
            <input type="file" name="imagem" id="imagem" accept="image/*">

            <p>Ou adicione um link para a imagem:</p>
            <input type="url" name="link_imagem" id="link_imagem" placeholder="http://exemplo.com/imagem.jpg">

            <button type="submit">Adicionar Produto</button>
        </form>
    </div>

</body>
</html>
