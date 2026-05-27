<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación - DinoPark</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .confirm-container { background: white; padding: 40px 50px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; max-width: 500px; }
        h1 { color: #2ecc71; margin-top: 0; margin-bottom: 20px; font-size: 2.2em; }
        .order-number { font-size: 2.2em; font-weight: bold; color: #2c3e50; margin: 25px 0; padding: 20px; background: #f8f9fa; border-radius: 10px; border: 2px dashed #bdc3c7; }
        p { color: #555; line-height: 1.6; font-size: 1.1em; }
        .btn { display: inline-block; background-color: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; transition: background 0.3s, transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn:hover { background-color: #2980b9; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="confirm-container">
    <h1>¡Compra Confirmada! 🎉</h1>
    <p>Gracias por tu compra. Tus entradas han sido procesadas correctamente y te esperan en la taquilla de DinoPark.</p>
    
    <p>Tu número de pedido es:</p>
    <div id="order-number">#<?php echo htmlspecialchars($orderId); ?></div>
    
    <a href="index.php" class="btn">Volver al Inicio</a>
</div>

</body>
</html>
