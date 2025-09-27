<?php

session_start();
if (!isset($_SESSION['usuario']) && !isset($_COOKIE['usuario'])) {
    header("Location: login.php");
    exit;
}

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
        let contadores = {}; // Objeto para armazenar os contadores temporários
        let produtosAlterados = {}; // Objeto para armazenar os produtos alterados
        
        function alterarQuantidade(id, valor) {
            let produto = document.querySelector(`.produto[data-id="${id}"]`);
            let contador = produto.querySelector('.contador');
            let quantidadeVendida = parseInt(contador.textContent);
            quantidadeVendida += valor;
            if (quantidadeVendida < 0) quantidadeVendida = 0;
            contador.textContent = quantidadeVendida;

            // Atualiza a lista de produtos alterados
            produtosAlterados[id] = quantidadeVendida;

            // Envia a atualização via AJAX
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

            // Atualizar contador de mudanças temporárias
            if (!contadores[id]) {
                contadores[id] = { quantidade: 0, timer: null, elemento: null };
            }
            contadores[id].quantidade += valor;
            
            if (!contadores[id].elemento) {
                let contadorElemento = document.createElement('div');
                contadorElemento.className = 'contador-temp';
                produto.appendChild(contadorElemento);
                contadores[id].elemento = contadorElemento;
            }
            
            contadores[id].elemento.textContent = `+${contadores[id].quantidade}x`;
            
            if (contadores[id].timer) clearTimeout(contadores[id].timer);
            contadores[id].timer = setTimeout(() => {
                contadores[id].elemento.remove();
                delete contadores[id];
            }, 5000);
        }

        function registrarVendaAutomatica() {
            // Envia os produtos alterados para o servidor
            for (let id in produtosAlterados) {
                let vendidos = produtosAlterados[id];

                fetch('registrar_venda.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `produto_id=${id}&quantidade=${vendidos}`
                })
                .then(response => response.text())
                .then(data => console.log('Venda registrada:', data))
                .catch(error => console.error('Erro ao registrar venda:', error));
            }

            // Limpa os produtos alterados após o registro
            produtosAlterados = {};
        }

        setInterval(registrarVendaAutomatica, 3000); // 10 minutos
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
