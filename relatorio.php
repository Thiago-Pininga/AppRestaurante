<?php
include 'conexao.php';

$pagina = 'Relatório'; // Define qual página está ativa
include 'header.php';

// Consulta SQL para pegar os produtos ordenados pela quantidade vendida de forma decrescente
$sql = "SELECT nome, quantidade_inicial, vendidos, valor FROM produtos ORDER BY vendidos DESC";
$result = $mysqli->query($sql);

// Inicializa a variável para armazenar o valor total das vendas
$total = 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relatório de Vendas</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container">
    <h1>Relatório de Vendas</h1>
    
    <?php if(isset($_GET['finalizado']) && $_GET['finalizado'] == 1): ?>
      <p class="mensagem">Dia finalizado com sucesso!</p>
    <?php endif; ?>

    <table>
      <thead>
        <tr>
          <th>Nome do Produto</th>
          <th>Quantidade Vendida</th>
          <th>Estoque Atual</th>
          <th>Valor Unitário (R$)</th>
          <th>Total Vendido (R$)</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($produto = $result->fetch_assoc()) : ?>
          <?php 
            // Calcula o total vendido do produto (quantidade vendida * valor unitário)
            $totalVendido = $produto['vendidos'] * $produto['valor']; 
            $total += $totalVendido;
            // Calcula o estoque atual (quantidade_inicial - vendidos)
            $estoqueAtual = $produto['quantidade_inicial'] - $produto['vendidos'];
          ?>
          <tr>
            <td><?= htmlspecialchars($produto['nome']) ?></td>
            <td><?= $produto['vendidos'] ?></td>
            <td><?= $estoqueAtual ?></td>
            <td>R$ <?= number_format($produto['valor'], 2, ',', '.') ?></td>
            <td>R$ <?= number_format($totalVendido, 2, ',', '.') ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="total">
      <h3>Total de Vendas: R$ <?= number_format($total, 2, ',', '.') ?></h3>
    </div>

    <!-- Botão Finalizar Dia com confirmação -->
    <form action="finalizar_dia.php" method="post" onsubmit="return confirm('Tem certeza que deseja finalizar o dia? Essa ação subtrairá o vendido do estoque inicial e zerará o campo vendido.');">
      <button type="submit">Finalizar Dia</button>
    </form>

  </div>

</body>
</html>
