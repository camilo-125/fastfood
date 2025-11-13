<?php
// Funciones auxiliares del sistema

// Función para encriptar contraseñas (RNF05)
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Función para verificar contraseñas
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Función para sanitizar entrada de usuario
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Función para validar email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para formatear precio
function formatPrice($price) {
    return '$' . number_format($price, 2);
}

// Función para obtener el estado del pedido con color
function getOrderStatusBadge($status) {
    $badges = [
        'Pendiente' => '<span class="badge badge-warning">Pendiente</span>',
        'En preparación' => '<span class="badge badge-info">En preparación</span>',
        'Listo' => '<span class="badge badge-success">Listo</span>',
        'Entregado' => '<span class="badge badge-secondary">Entregado</span>',
    ];
    return $badges[$status] ?? $status;
}

// Función para calcular el total del carrito
function calculateCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
    return $total;
}

// Función para generar token CSRF
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Función para verificar token CSRF
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

?>
