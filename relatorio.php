<?php
include 'conexao.php';

$pagina = 'Relatório'; // Define qual página está ativa
include 'header.php';

// Consulta SQL: agrupa os registros em intervalos de 30 minutos e obtém o maior valor cumulativo (quantidade) de cada tipo
$sql = "
    SELECT 
        CONCAT(
            DATE_FORMAT(DATE_SUB(rv.horario, INTERVAL MINUTE(rv.horario) % 30 MINUTE), '%Y-%m-%d %H:'),
            LPAD(FLOOR(MINUTE(rv.horario) / 30) * 30, 2, '0')
        ) AS intervalo,
        p.e_comida,
        MAX(rv.quantidade) AS quantidade_maxima
    FROM registro_vendas rv
    JOIN produtos p ON rv.produto_id = p.id
    WHERE rv.horario >= NOW() - INTERVAL 24 HOUR
    GROUP BY intervalo, p.e_comida
    ORDER BY intervalo ASC
";

$result = $mysqli->query($sql);

// Armazena os valores cumulativos por intervalo para cada tipo
$dados_comida = [];
$dados_bebida = [];

while ($row = $result->fetch_assoc()) {
    $intervalo = $row['intervalo'];
    if ($row['e_comida'] == 1) {
        $dados_comida[$intervalo] = (int)$row['quantidade_maxima'];
    } else {
        $dados_bebida[$intervalo] = (int)$row['quantidade_maxima'];
    }
}

// Cria uma linha do tempo unificada com todos os intervalos dos últimos 24h
$all_intervals = array_unique(array_merge(array_keys($dados_comida), array_keys($dados_bebida)));
sort($all_intervals);

// Preenche os intervalos que possam não ter registro para cada tipo com o último valor conhecido (ou 0 se não houver nenhum anterior)
$filled_comida = [];
$filled_bebida = [];

$prev_comida = 0;
$prev_bebida = 0;

foreach ($all_intervals as $intv) {
    // Comida
    if (isset($dados_comida[$intv])) {
        $current_comida = $dados_comida[$intv];
    } else {
        $current_comida = $prev_comida;
    }
    $filled_comida[$intv] = $current_comida;
    $prev_comida = $current_comida;
    
    // Bebida
    if (isset($dados_bebida[$intv])) {
        $current_bebida = $dados_bebida[$intv];
    } else {
        $current_bebida = $prev_bebida;
    }
    $filled_bebida[$intv] = $current_bebida;
    $prev_bebida = $current_bebida;
}

// Agora, calcula os incrementos (diferença entre o valor cumulativo atual e o anterior)
$incrementos_comida = [];
$incrementos_bebida = [];

$prev_comida = 0;
$prev_bebida = 0;

foreach ($all_intervals as $intv) {
    $current_comida = $filled_comida[$intv];
    $inc_comida = $current_comida - $prev_comida;
    $incrementos_comida[] = $inc_comida;
    $prev_comida = $current_comida;
    
    $current_bebida = $filled_bebida[$intv];
    $inc_bebida = $current_bebida - $prev_bebida;
    $incrementos_bebida[] = $inc_bebida;
    $prev_bebida = $current_bebida;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relatório de Vendas Incrementais por Tipo (30 min)</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
  <!-- Inclusão do Chart.js via CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container">
    <h1>Relatório de Vendas Incrementais por Tipo (30 minutos)</h1>
    
    <canvas id="graficoVendasIntervalos" width="400" height="200"></canvas>
    
    <script>
      var ctx = document.getElementById('graficoVendasIntervalos').getContext('2d');
      var graficoVendasIntervalos = new Chart(ctx, {
          type: 'line',
          data: {
              labels: <?php echo json_encode($all_intervals); ?>,
              datasets: [{
                  label: 'Vendas de Comida (30 min)',
                  data: <?php echo json_encode($incrementos_comida); ?>,
                  borderColor: 'rgba(255, 99, 132, 1)',
                  backgroundColor: 'rgba(255, 99, 132, 0.2)',
                  borderWidth: 2,
                  tension: 0.1
              },
              {
                  label: 'Vendas de Bebida (30 min)',
                  data: <?php echo json_encode($incrementos_bebida); ?>,
                  borderColor: 'rgba(54, 162, 235, 1)',
                  backgroundColor: 'rgba(54, 162, 235, 0.2)',
                  borderWidth: 2,
                  tension: 0.1
              }]
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
</body>
</html>
