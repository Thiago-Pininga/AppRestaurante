<?php
include("conexao.php");

$usuario = "admin";
$senha = "1234";

// Cria hash seguro
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Insere no banco
$stmt = $mysqli->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
$stmt->bind_param("ss", $usuario, $senha_hash);
$stmt->execute();

echo "Usuário cadastrado com sucesso!";
?>
