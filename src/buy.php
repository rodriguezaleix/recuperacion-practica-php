<?php
session_start();
require_once 'flash.php';
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    set_flash('error', 'Debes iniciar sesión para comprar entradas.');
    header("Location: login.php");
    exit;
}

$db = Database::getInstance();

try {
    $stmt = $db->query("SELECT id, name as label, price FROM ticket_types ORDER BY id ASC");
    $ticketTypes = $stmt->fetchAll();
} catch (Exception $e) {
    die("Error al cargar entradas: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantities = $_POST['quantity'] ?? [];
    
    $totalTickets = 0;
    $validData = true;
    $orderItemsData = [];
    $totalPrice = 0;

    foreach ($ticketTypes as $ticket) {
        $id = $ticket['id'];
        $qty = isset($quantities[$id]) ? (int)$quantities[$id] : 0;

        if ($qty < 0 || $qty > 100) {
            $validData = false;
            break;
        }

        if ($qty > 0) {
            $totalTickets += $qty;
            $subtotal = $qty * $ticket['price'];
            $totalPrice += $subtotal;
            
            $orderItemsData[] = [
                'ticket_type_id' => $id,
                'quantity' => $qty,
                'unit_price' => $ticket['price']
            ];
        }
    }

    if (!$validData) {
        set_flash('error', 'Las cantidades deben ser números entre 0 y 100.');
    } elseif ($totalTickets === 0) {
        set_flash('error', 'Debes seleccionar al menos una entrada para continuar.');
    } else {
        try {
            $db->beginTransaction();

            $stmtOrder = $db->prepare("INSERT INTO orders (buyer_email, total, status) VALUES (?, ?, 'PENDING')");
            $stmtOrder->execute([$_SESSION['username'], $totalPrice]);
            
            $orderId = $db->lastInsertId();

            $stmtItem = $db->prepare("INSERT INTO order_items (order_id, ticket_type_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
            
            foreach ($orderItemsData as $item) {
                $stmtItem->execute([
                    $orderId, 
                    $item['ticket_type_id'], 
                    $item['quantity'], 
                    $item['unit_price']
                ]);
            }

            $db->commit();
            header("Location: preview.php");
            exit;

        } catch (Exception $e) {
            $db->rollBack();
            set_flash('error', 'Error al procesar el pedido. Intenta de nuevo.');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprar Entradas - DinoPark</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; padding: 40px 20px; color: #333; margin: 0; }
        .buy-container { max-width: 650px; margin: auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h1 { color: #2c3e50; text-align: center; margin-top: 0; margin-bottom: 30px; font-size: 2em; }
        .ticket-row { border-bottom: 1px solid #ecf0f1; padding: 20px 0; display: flex; justify-content: space-between; align-items: center; }
        .ticket-row:last-child { border-bottom: none; }
        .ticket-info { flex-grow: 1; }
        .ticket-name { font-weight: bold; font-size: 1.2em; color: #2980b9; margin-bottom: 5px; }
        .ticket-price { color: #e74c3c; font-weight: bold; font-size: 1.1em; }
        label { font-weight: bold; color: #7f8c8d; margin-right: 10px; }
        input[type="number"] { width: 70px; padding: 10px; border: 1px solid #ccc; border-radius: 6px; text-align: center; font-size: 16px; outline: none; transition: border-color 0.3s; }
        input[type="number"]:focus { border-color: #3498db; }
        button { background-color: #2ecc71; color: white; padding: 15px 20px; border: none; border-radius: 6px; cursor: pointer; width: 100%; font-size: 18px; margin-top: 30px; font-weight: bold; transition: background 0.3s, transform 0.2s; box-shadow: 0 4px 6px rgba(46, 204, 113, 0.2); }
        button:hover { background-color: #27ae60; transform: translateY(-2px); }
        .nav-links { text-align: right; margin-bottom: 30px; border-bottom: 2px solid #ecf0f1; padding-bottom: 15px; }
        .nav-links a { margin-left: 20px; color: #7f8c8d; text-decoration: none; font-weight: bold; transition: color 0.3s; }
        .nav-links a:hover { color: #3498db; }
    </style>
</head>
<body>

<div class="buy-container">
    <div class="nav-links">
        <a href="index.php">Inicio</a>
        <a href="logout.php">Salir (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
    </div>

    <h1>Comprar Entradas</h1>
    
    <?php mostrar_flash(); ?>

    <form id="buy-form" action="buy.php" method="POST">
        <?php foreach ($ticketTypes as $ticket): ?>
            <?php $id = htmlspecialchars($ticket['id']); ?>
            <div class="ticket-row" id="ticket-type-<?php echo $id; ?>">
                <div class="ticket-info">
                    <div class="ticket-name"><?php echo htmlspecialchars($ticket['label']); ?></div>
                    <div class="ticket-price"><?php echo number_format($ticket['price'], 2); ?> €</div>
                </div>
                <div>
                    <label for="quantity-<?php echo $id; ?>">Cantidad:</label>
                    <input type="number" id="quantity-<?php echo $id; ?>" name="quantity[<?php echo $id; ?>]" min="0" max="100" value="0">
                </div>
            </div>
        <?php endforeach; ?>
        
        <button type="submit">Continuar a Vista Previa</button>
    </form>
</div>

</body>
</html>
