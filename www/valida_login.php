<?php
session_start();
include("conexao.php"); // seu arquivo de conexão

$usuario = $_POST['usuario'];
$senha   = $_POST['senha'];

// Buscar no banco
$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $mysqli->prepare($sql); // <-- aqui
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Verifica senha (se usou password_hash no cadastro)
    if (password_verify($senha, $row['senha'])) {
        $_SESSION['usuario'] = $usuario;

        // Se marcar "lembrar", cria cookie por 7 dias
        if (isset($_POST['lembrar'])) {
            setcookie("usuario", $usuario, time() + (7 * 24 * 60 * 60), "/");
        }

        header("Location: index.php");
        exit;
    }
}

echo "Usuário ou senha inválidos. <a href='login.php'>Tente novamente</a>";
?>
