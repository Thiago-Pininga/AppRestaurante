<?php
session_start();

// Se já estiver logado, redireciona
if (isset($_SESSION['usuario']) || isset($_COOKIE['usuario'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Restaurante</title>
    <meta charset="UTF-8">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">

    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px; border-radius: 12px;">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Login</h2>
            <form method="POST" action="valida_login.php">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuário</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="lembrar" name="lembrar" value="1">
                    <label class="form-check-label" for="lembrar">Manter conectado</label>
                </div>
                <button type="submit" class="btn btn-success w-100">Entrar</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (opcional, caso queira componentes interativos) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
