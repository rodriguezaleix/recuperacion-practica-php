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
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h1 { color: #2c3e50; margin: 0; }
        header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ecf0f1; padding-bottom: 20px; margin-bottom: 20px; }
        .hero { background: linear-gradient(135deg, #2c3e50, #3498db); padding: 40px 20px; border-radius: 10px; color: white; text-align: center; margin-bottom: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .hero h2 { margin: 0; font-size: 2.2em; text-shadow: 1px 1px 3px rgba(0,0,0,0.3); }
        .hero p { font-size: 1.1em; opacity: 0.9; margin-top: 10px; }
        .filter-container { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; background: #f8f9fa; padding: 15px 20px; border-radius: 8px; border: 1px solid #e9ecef; }
        .filter-container select { padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; outline: none; }
        #attraction-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .attraction-card { border: 1px solid #eee; padding: 20px; border-radius: 10px; transition: transform 0.2s, box-shadow 0.2s; background: white; }
        .attraction-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .attraction-card h3 { margin-top: 0; color: #2980b9; font-size: 1.3em; margin-bottom: 10px; }
        .maintenance-true { border-top: 4px solid #e74c3c; background: #fffaf9; }
        .maintenance-false { border-top: 4px solid #2ecc71; }
        .status-badge { font-weight: bold; padding: 6px 12px; border-radius: 20px; display: inline-block; margin-top: 15px; font-size: 0.9em; }
        .status-maintenance { background: #fee2e2; color: #c0392b; }
        .status-available { background: #dcfce7; color: #27ae60; }
        .login-btn { display: inline-block; background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; transition: background 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .login-btn:hover { background: #2980b9; }
        .nav-links span { color: #7f8c8d; margin-right: 15px; }
        .nav-links a.logout { text-decoration: none; color: #e74c3c; font-weight: bold; margin-right: 15px; transition: color 0.3s; }
        .nav-links a.logout:hover { color: #c0392b; }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>DinoPark</h1>
        <div class="nav-links">
            <?php if(isset($_SESSION['username'])): ?>
                <span>Hola, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="logout">Salir</a>
                <a href="buy.php" class="login-btn">Comprar Entradas</a>
            <?php else: ?>
                <a href="login.php" class="login-btn">Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="hero">
        <h2>Aventura Jurásica Te Espera</h2>
        <p>Descubre el parque temático de dinosaurios más espectacular del mundo.</p>
    </div>

    <div class="filter-container">
        <div>
            <label for="filter-maintenance" style="font-weight: bold; color: #2c3e50;">Filtrar atracciones:</label>
            <select id="filter-maintenance" style="margin-left: 10px;">
                <option value="all">Ver Todas</option>
                <option value="maintenance">En mantenimiento</option>
                <option value="available">Disponibles</option>
            </select>
        </div>
        <p style="margin: 0; color: #7f8c8d;">Visibles: <strong id="attraction-count" style="color: #2c3e50; font-size: 1.2em;"><?php echo count($attractions); ?></strong></p>
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
