<?php
// Normal para XAMPP    
$hostname = 'localhost';
$usuario = 'root';
$senha = '';
$database = 'restaurante';

// Normal para Docker
// $hostname = 'db';
// $usuario = 'user';
// $senha = 'password';
// $database = 'restaurante';

$mysqli = new mysqli($hostname, $usuario, $senha, $database);

//verifica se houver erro
if ($mysqli -> connect_errno) {
    echo 'Falha ao conectar: ('.$mysqli->connect_errno . ') ' . $mysqli -> connect_errno;
}

$mysqli->query("SET time_zone = '-03:00';");

?>