<?php
// RF05 - Realizar pedido
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
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

// Verificar que hay productos en el carrito
if (empty($_SESSION['cart'])) {
    setFlashMessage('danger', 'El carrito está vacío');
    header('Location: ../menu.php');
    exit();
}

$cart = $_SESSION['cart'];
$total = calculateCartTotal($cart);
$notas = sanitize($_POST['notas'] ?? '');
$user_id = $_SESSION['user_id'];

try {
    $pdo = getConnection();
    $pdo->beginTransaction();
    
    // Insertar pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, estado, total, notas) VALUES (?, 'Pendiente', ?, ?)");
    $stmt->execute([$user_id, $total, $notas ?: null]);
    $pedido_id = $pdo->lastInsertId();
    
    // Insertar detalles del pedido
    $stmt = $pdo->prepare("INSERT INTO detalle_pedidos (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($cart as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $stmt->execute([
            $pedido_id,
            $item['id'],
            $item['cantidad'],
            $item['precio'],
            $subtotal
        ]);
    }
    
    $pdo->commit();
    
    // Limpiar carrito
    $_SESSION['cart'] = [];
    
    setFlashMessage('success', 'Pedido realizado exitosamente. ID: #' . $pedido_id);
    header('Location: orders.php');
    exit();
    
} catch (Exception $e) {
    $pdo->rollBack();
    setFlashMessage('danger', 'Error al procesar el pedido. Intenta nuevamente.');
    header('Location: cart.php');
    exit();
}
?>
