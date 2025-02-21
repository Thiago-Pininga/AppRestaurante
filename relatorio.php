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
    GROUP BY id
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

// Consulta para obter os dados de vendas da tabela registro_vendas dos últimos 24h, agrupados em intervalos de 30 minutos
$sqlGraph = "
    SELECT 
        CONCAT(
            DATE_FORMAT(DATE_SUB(rv.horario, INTERVAL MINUTE(rv.horario) % 30 MINUTE), '%Y-%m-%d %H:'), 
            LPAD(FLOOR(MINUTE(rv.horario) / 30) * 30, 2, '0')
        ) AS intervalo,
        p.e_comida,
        MAX(rv.quantidade) AS quantidade_vendida
    FROM registro_vendas rv
    JOIN produtos p ON rv.produto_id = p.id
    WHERE DATE(rv.horario) = CURDATE()  -- Filtra apenas as vendas de hoje
    GROUP BY intervalo, p.e_comida
    ORDER BY intervalo ASC
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

// Cria uma linha do tempo unificada com todos os intervalos
$all_intervals = array_unique(array_merge(array_keys($dados_comida), array_keys($dados_bebida)));
sort($all_intervals);

// Preenche os intervalos que não possuem registro com o último valor conhecido
$filled_comida = [];
$filled_bebida = [];
$prev_comida = 0;
$prev_bebida = 0;
foreach ($all_intervals as $intv) {
    $current_comida = isset($dados_comida[$intv]) ? $dados_comida[$intv] : $prev_comida;
    $filled_comida[$intv] = $current_comida;
    $prev_comida = $current_comida;
    
    $current_bebida = isset($dados_bebida[$intv]) ? $dados_bebida[$intv] : $prev_bebida;
    $filled_bebida[$intv] = $current_bebida;
    $prev_bebida = $current_bebida;
}

// Calcula os incrementos (diferença entre o valor cumulativo atual e o anterior) para cada intervalo
$incrementos_comida = [];
$incrementos_bebida = [];
$prev_comida = 0;
$prev_bebida = 0;
foreach ($all_intervals as $intv) {
    $current_comida = $filled_comida[$intv];
    $incrementos_comida[] = $current_comida - $prev_comida;
    $prev_comida = $current_comida;
    
    $current_bebida = $filled_bebida[$intv];
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
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container my-4">
    <h1>Relatório de Vendas</h1>
    
    <!-- Tabela de Vendas -->
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
    
    <!-- Botão Finalizar Dia -->
    <form action="finalizar_dia.php" method="POST" class="mb-4">
      <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja encerrar o dia?')">Finalizar o Dia</button>
    </form>
    
    <!-- Gráfico de Vendas Incrementais -->
    <h2>Gráfico de Vendas Incrementais (30 minutos)</h2>
    <canvas id="graficoVendasIntervalos" width="400" height="200"></canvas>
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
                  x: {
                      title: {
                          display: true,
                          text: 'Intervalo (30 minutos)'
                      },
                      ticks: {
                          maxRotation: 90,
                          minRotation: 45
                      }
                  },
                  y: {
                      beginAtZero: true,
                      title: {
                          display: true,
                          text: 'Vendas Incrementais'
                      }
                  }
              }
          }
      });
    </script>
  </div>
  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
