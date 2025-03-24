<?php
session_start();
include_once '../Api/config/db.php';
include_once '../Api/controllers/UsuarioController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuarioController = new UsuarioController();
    $usuario = $usuarioController->obtenerUsuarioPorEmail($_POST['email']);
    
    if ($usuario) {
        // En un caso real, aquí verificarías la contraseña
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];
        
        header('Location: ../public/js/index.html'); 
        exit;
    } else {
        $error = "Email no encontrado";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Usuario</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>Login Usuario</h1>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>