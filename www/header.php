<?php

if (!isset($pagina)) {
    $pagina = 'Início';
}
?>

<header>
    <div class="container">
        <h1></h1>
        <nav>
            <ul>
                <li><a href="index.php" class="<?= ($pagina == 'Início') ? 'active' : '' ?>">Início</a></li>
                <li><a href="produtos.php" class="<?= ($pagina == 'Produtos') ? 'active' : '' ?>">Produtos</a></li>
                <li><a href="adicionar.php" class="<?= ($pagina == 'Adicionar') ? 'active' : '' ?>">Adicionar Produto</a></li>
                <li><a href="relatorio.php" class="<?= ($pagina == 'Relatório') ? 'active' : '' ?>">Relatório</a></li>
                <li><a href="logout.php" style="background-color:rgba(189, 26, 26, 1)">Logout</a></li> <!-- Botão de logout -->
            </ul>
        </nav>
    </div>
</header>
