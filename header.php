<?php
if (!isset($pagina)) {
    $pagina = 'Início';
}
?>

<header>
    <div class="container">
        <h1>Rango De Mãe</h1>
        <nav>
            <ul>
                <li><a href="index.php" class="<?= ($pagina == 'Início') ? 'active' : '' ?>">Início</a></li>
                <li><a href="produtos.php" class="<?= ($pagina == 'Produtos') ? 'active' : '' ?>">Produtos</a></li>
                <li><a href="adicionar.php" class="<?= ($pagina == 'Adicionar') ? 'active' : '' ?>">Adicionar Produto</a></li>
                <li><a href="relatorio.php" class="<?= ($pagina == 'Relatório') ? 'active' : '' ?>">Relatório</a></li> <!-- Nova página Relatório -->
            </ul>
        </nav>
    </div>
</header>
