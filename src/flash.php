<?php
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function mostrar_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        $type = htmlspecialchars($flash['type']);
        $message = htmlspecialchars($flash['message']);
        
        $color = ($type === 'error') ? '#e74c3c' : (($type === 'success') ? '#2ecc71' : '#3498db');
        
        echo "<div style='background-color: {$color}; color: white; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center;'>";
        echo $message;
        echo "</div>";
        
        unset($_SESSION['flash']);
    }
}
?>
