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
