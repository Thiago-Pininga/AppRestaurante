<?php

include 'conexao.php';

$query = "ALTER TABLE produtos AUTO_INCREMENT = 1";
$mysqli->query($query);


$query = "ALTER TABLE registro_vendas AUTO_INCREMENT = 1";
$mysqli->query($query);

header("Location: index.php");
exit();
?>