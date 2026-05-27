# Práctica PHP — Taquilla Online Parque Temático

Esta es la práctica de recuperación de PHP. A continuación se responden los puntos obligatorios:

### 1. Cómo se conecta la base de datos (Singleton)
Se ha implementado el patrón **Singleton** en el archivo `src/db.php`.
Consiste en una clase `Database` con un constructor privado y un método estático `getInstance()`. Esto asegura que sólo se cree una única conexión a la base de datos durante toda la ejecución de la aplicación, ahorrando recursos y centralizando el manejo de la conexión.
```php
// Ejemplo de uso:
require_once 'db.php';
$db = Database::getInstance();
```

### 2. Cómo se recupera el pedido pendiente
El pedido pendiente se recupera en el archivo `src/preview.php`. Se busca en la tabla `orders` el pedido asociado al correo del usuario que haya iniciado sesión (`$_SESSION['username']`), que tenga el estado `PENDING`, ordenado por fecha de creación descendente (para obtener el último):
```php
$stmtOrder = $db->prepare("SELECT id, total FROM orders WHERE buyer_email = ? AND status = 'PENDING' ORDER BY created_at DESC LIMIT 1");
$stmtOrder->execute([$email]);
$order = $stmtOrder->fetch();
```
Una vez se tiene el ID del pedido (`$order['id']`), se hace un `JOIN` entre `order_items` y `ticket_types` para recuperar el detalle del carrito.

### 3. Ejemplo de una consulta con prepared statement
Para garantizar la seguridad y evitar Inyecciones SQL, todas las consultas que reciben parámetros del usuario utilizan sentencias preparadas. 
Por ejemplo, en `src/buy.php`, para insertar el pedido:
```php
$stmtOrder = $db->prepare("INSERT INTO orders (buyer_email, total, status) VALUES (?, ?, 'PENDING')");
$stmtOrder->execute([$_SESSION['username'], $totalPrice]);
```

### 4. Enlace al video demostración
*Aquí se debe poner el enlace al vídeo de demostración mostrando el funcionamiento.*
*(Enlace de YouTube o similar)*
