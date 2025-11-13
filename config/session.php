<?php
// Configuración de sesiones (RNF04 - Optimización de concurrencia)
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400, // 24 horas
        'cookie_httponly' => true,
        'cookie_secure' => false, // Cambiar a true en producción con HTTPS
        'use_strict_mode' => true,
    ]);
}

function getRelativePathToRoot() {
    $script_path = $_SERVER['SCRIPT_NAME'];
    $script_dir = dirname($script_path);
    
    // Count how many directories deep we are
    $depth = substr_count($script_dir, '/') - 1;
    
    // If we're in root directory
    if ($depth <= 0) {
        return '.';
    }
    
    // Go up the required number of directories
    return str_repeat('../', $depth);
}

// Función para verificar si el usuario está autenticado
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

// Función para verificar el rol del usuario
function hasRole($role) {
    return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === $role;
}

// Función para requerir autenticación
function requireLogin() {
    if (!isLoggedIn()) {
        $base_path = getRelativePathToRoot();
        header("Location: {$base_path}/login.php");
        exit();
    }
}

// Función para requerir un rol específico
function requireRole($role) {
    if (!isLoggedIn()) {
        $base_path = getRelativePathToRoot();
        setFlashMessage('Por favor inicia sesión para continuar', 'warning');
        header("Location: {$base_path}/login.php");
        exit();
    }
    
    if (!hasRole($role)) {
        $base_path = getRelativePathToRoot();
        setFlashMessage('No tienes permisos para acceder a esta página', 'danger');
        header("Location: {$base_path}/index.php");
        exit();
    }
}

// Función para destruir la sesión (logout)
function destroySession() {
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

// Función para regenerar ID de sesión (seguridad)
function regenerateSession() {
    session_regenerate_id(true);
}

// Función para establecer un mensaje flash
function setFlashMessage($message, $type = 'success') {
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
}

// Función para obtener y limpiar mensaje flash
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}
?>
