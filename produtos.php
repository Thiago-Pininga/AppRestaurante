<?php
include 'conexao.php';

// Consulta para buscar todos os produtos
$sql = "SELECT * FROM produtos";
$result = $mysqli->query($sql);

// Separar produtos por tipo (comida e bebida)
$bebidas = [];
$comidas = [];

while ($produto = $result->fetch_assoc()) {
    // Verifica se o produto é comida ou bebida, baseando-se no campo 'e_comida'
    if ($produto['e_comida'] == 1) {
        $comidas[] = $produto; // Produto é comida
    } else {
        $bebidas[] = $produto; // Produto é bebida
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <script>
        // Função para atualizar a quantidade vendida de todos os produtos
        function atualizarQuantidades() {
            let produtos = document.querySelectorAll('.produto');
            let dados = [];

            produtos.forEach(produto => {
                let id = produto.getAttribute('data-id');
                let vendidos = produto.querySelector('.contador').textContent;

                dados.push({ id: id, vendidos: vendidos });
            });

            fetch('atualizar_quantidades.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dados)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Quantidades atualizadas com sucesso!');
                } else {
                    console.log('Erro ao atualizar quantidades.');
                }
            });
        }

        // Função para registrar vendas automaticamente a cada 10 minutos
        function registrarVendaAutomatica() {
            let produtos = document.querySelectorAll('.produto');
            produtos.forEach(produto => {
                let id = produto.getAttribute('data-id');
                let vendidos = produto.querySelector('.contador').textContent;

                fetch('registrar_venda.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `produto_id=${id}&quantidade=${vendidos}`
                })
                .then(response => response.text())
                .then(data => console.log(data))
                .catch(error => console.error('Erro:', error));
            });
        }

        setInterval(registrarVendaAutomatica, 600); // 10 minutos

        function alterarQuantidade(id, valor) {
            let contador = document.querySelector(`.produto[data-id="${id}"] .contador`);
            let quantidadeVendida = parseInt(contador.textContent);
            quantidadeVendida += valor;
            if (quantidadeVendida < 0) quantidadeVendida = 0;
            contador.textContent = quantidadeVendida;

            fetch('alterar_quantidade.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id, vendidos: quantidadeVendida })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.log('Erro ao atualizar a quantidade.');
                }
            });
        }
    </script>
</head>
<body>
    <?php
    $pagina = 'Produtos';
    include 'header.php';
    ?>

    <div class="container">
        <h1>Produtos</h1>

        <h2>Bebidas</h2>
        <div class="cards-produtos">
            <?php foreach ($bebidas as $produto) : ?>
                <div class="produto" data-id="<?= $produto['id'] ?>">
                    <div class="produto-imagem">
                        <?php if (!empty($produto['imagem'])): ?>
                            <img src="<?= $produto['imagem'] ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <?php else: ?>
                            <img src="imagens/default.png" alt="Imagem padrão">
                        <?php endif; ?>
                    </div>
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <p>Quantidade Inicial: <?= $produto['quantidade_inicial'] ?></p>
                    <p>Valor: R$ <?= number_format($produto['valor'], 2, ',', '.') ?></p>
                    <div class="contador-vendido">
                        <button class="btn-decrementar" onclick="alterarQuantidade(<?= $produto['id'] ?>, -1)">-</button>
                        <span class="contador"><?= $produto['vendidos'] ?></span>
                        <button class="btn-incrementar" onclick="alterarQuantidade(<?= $produto['id'] ?>, 1)">+</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Comidas</h2>
        <div class="cards-produtos">
            <?php foreach ($comidas as $produto) : ?>
                <div class="produto" data-id="<?= $produto['id'] ?>">
                    <div class="produto-imagem">
                        <?php if (!empty($produto['imagem'])): ?>
                            <img src="<?= $produto['imagem'] ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <?php else: ?>
                            <img src="imagens/default.png" alt="Imagem padrão">
                        <?php endif; ?>
                    </div>
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <p>Quantidade Inicial: <?= $produto['quantidade_inicial'] ?></p>
                    <p>Valor: R$ <?= number_format($produto['valor'], 2, ',', '.') ?></p>
                    <div class="contador-vendido">
                        <button class="btn-decrementar" onclick="alterarQuantidade(<?= $produto['id'] ?>, -1)">-</button>
                        <span class="contador"><?= $produto['vendidos'] ?></span>
                        <button class="btn-incrementar" onclick="alterarQuantidade(<?= $produto['id'] ?>, 1)">+</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
