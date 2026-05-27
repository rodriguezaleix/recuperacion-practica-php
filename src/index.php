<?php
session_start();
require_once 'db.php';

try {
    $db = Database::getInstance();
    $stmt = $db->query("SELECT * FROM attractions");
    $attractions = $stmt->fetchAll();
} catch (Exception $e) {
    die("Error de base de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parque de Dinosaurios</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; }
        .hero { text-align: center; margin-bottom: 20px; }
        #theme-image { max-width: 100%; height: auto; border-radius: 8px; }
        .filter-container { margin-bottom: 20px; text-align: center; background: #ecf0f1; padding: 15px; border-radius: 8px; }
        .attraction-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .attraction-card h3 { margin-top: 0; color: #2980b9; }
        .maintenance-true { background-color: #ffeaa7; border-color: #fdcb6e; }
        .maintenance-false { background-color: #e8f8f5; border-color: #a3e4d7; }
        .status-badge { font-weight: bold; padding: 5px 10px; border-radius: 3px; display: inline-block; margin-top: 10px; }
        .status-maintenance { background: #e74c3c; color: white; }
        .status-available { background: #2ecc71; color: white; }
        .login-btn { display: inline-block; background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .login-btn:hover { background: #2980b9; }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Bienvenidos a DinoPark</h1>
        <div style="text-align: right; margin-bottom: 20px;">
            <?php if(isset($_SESSION['username'])): ?>
                <span>Hola, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php">Salir</a>
                | <a href="buy.php" class="login-btn">Comprar Entradas</a>
            <?php else: ?>
                <a href="login.php" class="login-btn">Iniciar compra</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="hero">
        <img id="theme-image" src="https://images.unsplash.com/photo-1518355601272-3cf7bbf8bf0a?auto=format&fit=crop&w=1000&q=80" alt="Parque de Dinosaurios">
    </div>

    <div class="filter-container">
        <label for="filter-maintenance">Filtrar atracciones:</label>
        <select id="filter-maintenance">
            <option value="all">Todas</option>
            <option value="maintenance">En mantenimiento</option>
            <option value="available">Disponibles</option>
        </select>
        <p>Atracciones visibles: <span id="attraction-count"><?php echo count($attractions); ?></span></p>
    </div>

    <div id="attraction-list">
        <?php foreach ($attractions as $attraction): ?>
            <?php 
                $isMaintenance = $attraction['maintenance'] ? 'true' : 'false';
                $cardClass = $attraction['maintenance'] ? 'maintenance-true' : 'maintenance-false';
            ?>
            <div class="attraction-card <?php echo $cardClass; ?>" data-maintenance="<?php echo $isMaintenance; ?>">
                <h3><?php echo htmlspecialchars($attraction['name']); ?></h3>
                <p><?php echo htmlspecialchars($attraction['description']); ?></p>
                <p><strong>Categoría:</strong> <?php echo htmlspecialchars($attraction['category']); ?> | <strong>Duración:</strong> <?php echo $attraction['duration_minutes']; ?> min</p>
                
                <?php if ($attraction['maintenance']): ?>
                    <span class="status-badge status-maintenance">En Mantenimiento</span>
                <?php else: ?>
                    <span class="status-badge status-available">Disponible</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelect = document.getElementById('filter-maintenance');
        const attractionList = document.getElementById('attraction-list');
        const countSpan = document.getElementById('attraction-count');

        filterSelect.addEventListener('change', function() {
            const filterValue = this.value;
            const cards = attractionList.querySelectorAll('.attraction-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const isMaintenance = card.getAttribute('data-maintenance') === 'true';
                let show = false;

                if (filterValue === 'all') {
                    show = true;
                } else if (filterValue === 'maintenance' && isMaintenance) {
                    show = true;
                } else if (filterValue === 'available' && !isMaintenance) {
                    show = true;
                }

                if (show) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            countSpan.textContent = visibleCount;
        });
    });
</script>
