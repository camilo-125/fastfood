<?php
// RF04 - Agregar al carrito
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('cliente');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../menu.php');
    exit();
}

// Validar CSRF
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    setFlashMessage('danger', 'Token de seguridad inválido');
    header('Location: ../menu.php');
    exit();
}

$producto_id = (int)($_POST['producto_id'] ?? 0);
$cantidad = (int)($_POST['cantidad'] ?? 1);

if ($producto_id <= 0 || $cantidad <= 0) {
    setFlashMessage('danger', 'Datos inválidos');
    header('Location: ../menu.php');
    exit();
}

try {
    // Verificar que el producto existe y está activo
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT id_producto, nombre, precio, imagen FROM productos WHERE id_producto = ? AND activo = 1");
    $stmt->execute([$producto_id]);
    $producto = $stmt->fetch();
    
    if (!$producto) {
        setFlashMessage('danger', 'Producto no encontrado');
        header('Location: ../menu.php');
        exit();
    }
    
    // Inicializar carrito si no existe
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Buscar si el producto ya está en el carrito
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $producto_id) {
            $item['cantidad'] += $cantidad;
            $found = true;
            break;
        }
    }
    
    // Si no está en el carrito, agregarlo
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $producto['id_producto'],
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'imagen' => $producto['imagen'],
            'cantidad' => $cantidad
        ];
    }
    
    setFlashMessage('success', 'Producto agregado al carrito');
    header('Location: ../menu.php');
    exit();
    
} catch (Exception $e) {
    setFlashMessage('danger', 'Error al agregar producto al carrito');
    header('Location: ../menu.php');
    exit();
}
?>
