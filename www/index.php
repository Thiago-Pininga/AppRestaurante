<?php
session_start();

// Se não estiver logado, redireciona para login
if (!isset($_SESSION['usuario']) && !isset($_COOKIE['usuario'])) {
    header("Location: login.php");
    exit;
}

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
    <style>
        /* Classe para destacar produtos com estoque baixo */
        .alerta-estoque {
            background-color: #ffcccc !important; /* Fundo vermelho claro */
        }
    </style>
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
                    <th>Estoque Atual</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($produto = $result->fetch_assoc()) : 
                    $estoque_atual = $produto['quantidade_inicial'] - $produto['vendidos'];
                    // Aplica a classe se o estoque atual for menor ou igual ao estoque mínimo
                    $classe = ($estoque_atual <= $produto['estoque_minimo']) ? 'alerta-estoque' : '';
                ?>
                    <tr class="<?= $classe ?>">
                        <td><?= $produto['id'] ?></td>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td><?= $produto['e_comida'] == 1 ? 'Comida' : 'Bebida' ?></td>
                        <td><?= $produto['quantidade_inicial'] ?></td>
                        <td><?= $produto['vendidos'] ?></td>
                        <td><?= $estoque_atual ?></td>
                        <td>
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
