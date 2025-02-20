<?php
if (!isset($pagina)) {
    $pagina = 'Início';
}
?>

<header>
    <div class="container">
        <h1>App Restaurante</h1>
        <nav>
            <ul>
                <li><a href="index.php" class="<?= ($pagina == 'Início') ? 'active' : '' ?>">Início</a></li>
                <li><a href="produtos.php" class="<?= ($pagina == 'Produtos') ? 'active' : '' ?>">Produtos</a></li>
                <li><a href="adicionar.php" class="<?= ($pagina == 'Adicionar') ? 'active' : '' ?>">Adicionar Produto</a></li>
            </ul>
        </nav>
    </div>
</header>
