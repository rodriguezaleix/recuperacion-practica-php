<?php
session_start();
require_once 'flash.php';
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$db = Database::getInstance();
$email = $_SESSION['username'];

try {
    $stmtOrder = $db->prepare("SELECT id, total FROM orders WHERE buyer_email = ? AND status = 'PENDING' ORDER BY created_at DESC LIMIT 1");
    $stmtOrder->execute([$email]);
    $order = $stmtOrder->fetch();

    if (!$order) {
        set_flash('info', 'No tienes pedidos pendientes.');
        header("Location: index.php");
        exit;
    }

    $orderId = $order['id'];

    $stmtItems = $db->prepare("SELECT oi.quantity, oi.unit_price, tt.name as label 
                                FROM order_items oi 
                                JOIN ticket_types tt ON oi.ticket_type_id = tt.id 
                                WHERE oi.order_id = ?");
    $stmtItems->execute([$orderId]);
    $items = $stmtItems->fetchAll();

} catch (Exception $e) {
    die("Error al cargar la vista previa: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista Previa del Pedido - DinoPark</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .preview-container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; }
        #cart-preview { margin-top: 20px; }
        .cart-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .cart-total { font-size: 1.2em; font-weight: bold; text-align: right; padding: 20px 0; border-top: 2px solid #ccc; margin-top: 10px; color: #e74c3c; }
    </style>
</head>
<body>

<div class="preview-container">
    <h1>Revisa tu Compra</h1>
    
    <?php mostrar_flash(); ?>

    <div id="cart-preview">
        <?php foreach ($items as $item): ?>
            <div class="cart-item">
                <span><?php echo $item['quantity']; ?>x <?php echo htmlspecialchars($item['label']); ?> (<?php echo number_format($item['unit_price'], 2); ?> €)</span>
                <span><?php echo number_format($item['quantity'] * $item['unit_price'], 2); ?> €</span>
            </div>
        <?php endforeach; ?>
        
        <div class="cart-total">
            Total a pagar: <?php echo number_format($order['total'], 2); ?> €
        </div>
    </div>
</div>

</body>
</html>
