<?php
session_start();
require_once 'flash.php';

if (isset($_SESSION['username'])) {
    header("Location: buy.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        set_flash('error', 'El email es obligatorio.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('error', 'Formato de email inválido.');
    } else {
        $_SESSION['username'] = $email;
        header("Location: buy.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - DinoPark</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 40px 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 400px; text-align: center; }
        h2 { color: #2c3e50; margin-top: 0; margin-bottom: 25px; font-size: 1.8em; }
        label { display: block; text-align: left; margin-bottom: 5px; color: #7f8c8d; font-weight: bold; font-size: 0.9em; }
        input[type="email"] { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-size: 16px; outline: none; transition: border-color 0.3s; }
        input[type="email"]:focus { border-color: #3498db; }
        button { background-color: #3498db; color: white; padding: 12px 15px; border: none; border-radius: 6px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; transition: background 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        button:hover { background-color: #2980b9; transform: translateY(-1px); }
        .back-link { display: block; margin-top: 20px; color: #7f8c8d; text-decoration: none; font-size: 0.95em; transition: color 0.3s; }
        .back-link:hover { color: #3498db; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    
    <?php mostrar_flash(); ?>
    
    <form id="login-form" action="login.php" method="POST">
        <label for="email-input">Correo Electrónico:</label>
        <input type="email" id="email-input" name="email" required placeholder="tu@email.com">
        
        <button type="submit">Entrar</button>
    </form>
    
    <a href="index.php" class="back-link">Volver al inicio</a>
</div>

</body>
</html>
