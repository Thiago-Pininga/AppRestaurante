<?php

$hostname = 'sql309.infinityfree.com';
$usuario = 'if0_38361506';
$senha = 'gsrSTDRILEHQaE';
$database = 'restaurante';

$mysqli = new mysqli($hostname, $usuario, $senha, $database);

//verifica se houver erro
if ($mysqli -> connect_errno) {
    echo 'Falha ao conectar: ('.$mysqli->connect_errno . ') ' . $mysqli -> connect_errno;
}
?>