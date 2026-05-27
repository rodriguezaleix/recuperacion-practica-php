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

if (isset($_POST['action']) && $_POST['action'] === 'finalize') {
    $orderId = $_POST['order_id'];
    $stmt = $db->prepare("UPDATE orders SET status = 'COMPLETED' WHERE id = ? AND buyer_email = ? AND status = 'PENDING'");
    if ($stmt->execute([$orderId, $email])) {
        header("Location: confirm.php?id=" . $orderId);
        exit;
    } else {
        set_flash('error', 'No se pudo finalizar el pedido.');
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'cancel') {
    $orderId = $_POST['order_id'];
    $stmt = $db->prepare("UPDATE orders SET status = 'CANCELLED' WHERE id = ? AND buyer_email = ? AND status = 'PENDING'");
    if ($stmt->execute([$orderId, $email])) {
        set_flash('info', 'Pedido cancelado correctamente.');
        header("Location: index.php");
        exit;
    } else {
        set_flash('error', 'No se pudo cancelar el pedido.');
    }
}

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
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; padding: 40px 20px; color: #333; margin: 0; }
        .preview-container { max-width: 650px; margin: auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h1 { color: #2c3e50; text-align: center; margin-top: 0; margin-bottom: 30px; font-size: 2em; }
        #cart-preview { margin-top: 20px; }
        .cart-item { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #ecf0f1; font-size: 1.1em; }
        .cart-total { font-size: 1.4em; font-weight: bold; text-align: right; padding: 20px 0; border-top: 3px solid #ecf0f1; margin-top: 10px; color: #e74c3c; }
        .actions { display: flex; justify-content: space-between; margin-top: 30px; }
        button { padding: 15px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; color: white; width: 100%; transition: background 0.3s, transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        #finalize-button { background-color: #2ecc71; }
        #finalize-button:hover { background-color: #27ae60; transform: translateY(-2px); }
        #cancel-button { background-color: #e74c3c; }
        #cancel-button:hover { background-color: #c0392b; transform: translateY(-2px); }
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

    <div class="actions">
        <form action="preview.php" method="POST" style="width: 48%;">
            <input type="hidden" name="action" value="cancel">
            <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
            <button type="submit" id="cancel-button">Cancelar Pedido</button>
        </form>

        <form action="preview.php" method="POST" style="width: 48%;">
            <input type="hidden" name="action" value="finalize">
            <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
            <button type="submit" id="finalize-button">Confirmar Compra</button>
        </form>
    </div>
</div>

</body>
</html>
