<?php
// RF04 - Actualizar carrito
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('cliente');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit();
}

// Validar CSRF
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    setFlashMessage('danger', 'Token de seguridad inválido');
    header('Location: cart.php');
    exit();
}

$index = (int)($_POST['index'] ?? -1);
$action = $_POST['action'] ?? '';

if (!isset($_SESSION['cart']) || $index < 0 || $index >= count($_SESSION['cart'])) {
    setFlashMessage('danger', 'Producto no encontrado en el carrito');
    header('Location: cart.php');
    exit();
}

switch ($action) {
    case 'increase':
        $_SESSION['cart'][$index]['cantidad']++;
        setFlashMessage('success', 'Cantidad actualizada');
        break;
        
    case 'decrease':
        if ($_SESSION['cart'][$index]['cantidad'] > 1) {
            $_SESSION['cart'][$index]['cantidad']--;
            setFlashMessage('success', 'Cantidad actualizada');
        } else {
            setFlashMessage('warning', 'La cantidad mínima es 1');
        }
        break;
        
    case 'remove':
        $producto_nombre = $_SESSION['cart'][$index]['nombre'];
        array_splice($_SESSION['cart'], $index, 1);
        setFlashMessage('success', "'{$producto_nombre}' eliminado del carrito");
        break;
        
    default:
        setFlashMessage('danger', 'Acción no válida');
}

header('Location: cart.php');
exit();
?>
