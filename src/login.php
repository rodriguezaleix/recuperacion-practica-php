<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - DinoPark</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        input[type="email"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #3498db; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        button:hover { background-color: #2980b9; }
        .back-link { display: block; margin-top: 15px; color: #7f8c8d; text-decoration: none; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    
    <form id="login-form" action="login.php" method="POST">
        <label for="email-input">Correo Electrónico:</label>
        <input type="email" id="email-input" name="email" required placeholder="tu@email.com">
        
        <button type="submit">Entrar</button>
    </form>
    
    <a href="index.php" class="back-link">Volver al inicio</a>
</div>

</body>
</html>
