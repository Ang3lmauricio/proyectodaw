<?php
include_once '../Api/config/db.php';
include_once '../Api/controllers/UsuarioController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuarioController = new UsuarioController();
    
    $resultado = $usuarioController->registrarUsuario(
        $_POST['nombre'], 
        $_POST['email'],
        $_POST['contrasena'],
        'cliente'
    );
    
    if ($resultado) {
        header('Location: login.php');
        exit;
    } else {
        $error = "Error al registrar el usuario. El email podría estar en uso.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="register-container">
        <h1>Registrar Usuario</h1>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="registrar.php">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" required minlength="6">
                <small>Mínimo 6 caracteres</small>
            </div>
            <button type="submit">Registrar</button>
            <p class="login-link">¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
        </form>
    </div>
</body>
</html>