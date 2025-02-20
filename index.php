<?php
include 'conexao.php';

$pagina = 'Início'; // Define qual página está ativa
include 'header.php';

// Consulta para buscar os produtos
$sql = "SELECT * FROM produtos";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <h1>Lista de Produtos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Quantidade Inicial</th>
                    <th>Vendidos</th>
                    <th>Valor (R$)</th>
                    <th>Ações</th> <!-- Coluna de ações -->
                </tr>
            </thead>
            <tbody>
                <?php while ($produto = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $produto['id'] ?></td>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <!-- Exibe 'Comida' ou 'Bebida' com base no valor de e_comida -->
                        <td><?= $produto['e_comida'] == 1 ? 'Comida' : 'Bebida' ?></td>
                        <td><?= $produto['quantidade_inicial'] ?></td>
                        <td><?= $produto['vendidos'] ?></td>
                        <td>R$ <?= number_format($produto['valor'], 2, ',', '.') ?></td>
                        <td>
                            <!-- Botões de editar e excluir -->
                            <a href="editar.php?id=<?= $produto['id'] ?>" class="btn-editar">Editar</a>
                            <a href="excluir.php?id=<?= $produto['id'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
