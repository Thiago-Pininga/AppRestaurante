<?php
include 'conexao.php';

$pagina = 'Relatório'; // Define qual página está ativa
include 'header.php';

// =====================
// TABELA DE VENDAS
// =====================

// Consulta para obter os produtos e calcular as vendas totais
$sqlTable = "
    SELECT 
        nome, 
        e_comida, 
        vendidos AS quantidade_vendida, 
        valor,
        (vendidos * valor) AS total_item
    FROM produtos p
    ORDER BY e_comida ASC, nome ASC
";
$resultTable = $mysqli->query($sqlTable);

$produtos_vendidos = [];
$total_vendas = 0;
while ($row = $resultTable->fetch_assoc()) {
    $produtos_vendidos[] = $row;
    $total_vendas += $row['total_item'];
}

// =====================
// DADOS PARA O GRÁFICO
// =====================

// Consulta para obter os dados de vendas dos últimos 24h agrupados por intervalos de 30 minutos
$sqlGraph = "
    SELECT 
        CONCAT(
            DATE_FORMAT(DATE_SUB(rv.horario, INTERVAL MINUTE(rv.horario) % 30 MINUTE), '%Y-%m-%d %H:'),
            LPAD(FLOOR(MINUTE(rv.horario) / 30) * 30, 2, '0')
        ) AS intervalo,
        p.e_comida,
        rv.quantidade AS quantidade_vendida
    FROM registro_vendas rv
    JOIN produtos p ON rv.produto_id = p.id
    WHERE DATE(rv.horario) = CURDATE()
    ORDER BY rv.horario ASC;
";
$resultGraph = $mysqli->query($sqlGraph);

$dados_comida = [];
$dados_bebida = [];
while ($row = $resultGraph->fetch_assoc()) {
    $intervalo = $row['intervalo'];
    if ($row['e_comida'] == 1) {
        $dados_comida[$intervalo] = (int)$row['quantidade_vendida'];
    } else {
        $dados_bebida[$intervalo] = (int)$row['quantidade_vendida'];
    }
}

// Cria uma linha do tempo unificada
$all_intervals = array_unique(array_merge(array_keys($dados_comida), array_keys($dados_bebida)));
sort($all_intervals);

// Preenche os intervalos que não possuem registro
$incrementos_comida = [];
$incrementos_bebida = [];
$prev_comida = 0;
$prev_bebida = 0;
foreach ($all_intervals as $intv) {
    $current_comida = $dados_comida[$intv] ?? $prev_comida;
    $incrementos_comida[] = $current_comida - $prev_comida;
    $prev_comida = $current_comida;

    $current_bebida = $dados_bebida[$intv] ?? $prev_bebida;
    $incrementos_bebida[] = $current_bebida - $prev_bebida;
    $prev_bebida = $current_bebida;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relatório de Vendas</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container my-4">
    <h1>Relatório de Vendas</h1>

    <h2>Tabela de Vendas</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Tipo</th>
          <th>Quantidade Vendida</th>
          <th>Valor Unitário (R$)</th>
          <th>Total do Item (R$)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produtos_vendidos as $prod): ?>
        <tr>
          <td><?= $prod['nome'] ?></td>
          <td><?= $prod['e_comida'] == 1 ? 'Comida' : 'Bebida' ?></td>
          <td><?= $prod['quantidade_vendida'] ?></td>
          <td>R$ <?= number_format($prod['valor'], 2, ',', '.') ?></td>
          <td>R$ <?= number_format($prod['total_item'], 2, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="mb-3">
      <h3>Total de Vendas: R$ <?= number_format($total_vendas, 2, ',', '.') ?></h3>
    </div>

    <div style="margin: 20px 0; text-align: right;">
        <form action="finalizar_dia.php" method="post" onsubmit="return confirm('Deseja realmente finalizar o dia?');">
            <button type="submit" class="finalizar-dia-btn">
                Finalizar Dia
            </button>
        </form>
    </div>


    <h2>Gráfico de Vendas Incrementais (30 minutos)</h2>
    <canvas id="graficoVendasIntervalos"></canvas>
    <script>
      var ctx = document.getElementById('graficoVendasIntervalos').getContext('2d');
      var grafico = new Chart(ctx, {
          type: 'line',
          data: {
              labels: <?= json_encode($all_intervals); ?>,
              datasets: [
                  {
                      label: 'Vendas de Comida (30 min)',
                      data: <?= json_encode($incrementos_comida); ?>,
                      borderColor: 'rgba(255, 99, 132, 1)',
                      backgroundColor: 'rgba(255, 99, 132, 0.2)',
                      borderWidth: 2,
                      tension: 0.1
                  },
                  {
                      label: 'Vendas de Bebida (30 min)',
                      data: <?= json_encode($incrementos_bebida); ?>,
                      borderColor: 'rgba(54, 162, 235, 1)',
                      backgroundColor: 'rgba(54, 162, 235, 0.2)',
                      borderWidth: 2,
                      tension: 0.1
                  }
              ]
          },
          options: {
              responsive: true,
              scales: {
                  x: { title: { display: true, text: 'Intervalo (30 minutos)' } },
                  y: { beginAtZero: true, title: { display: true, text: 'Vendas Incrementais' } }
              }
          }
      });
    </script>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
