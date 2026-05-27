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
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .confirm-container { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; max-width: 500px; }
        h1 { color: #2ecc71; margin-bottom: 20px; }
        .order-number { font-size: 2em; font-weight: bold; color: #2c3e50; margin: 20px 0; padding: 15px; background: #ecf0f1; border-radius: 8px; border: 2px dashed #bdc3c7; }
        p { color: #555; line-height: 1.6; }
        .btn { display: inline-block; background-color: #3498db; color: white; padding: 12px 25px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
        .btn:hover { background-color: #2980b9; }
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
