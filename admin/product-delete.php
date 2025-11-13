<?php
// RF07 - Eliminar producto (baja lógica)
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('administrador');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('danger', 'Token de seguridad inválido');
    } else {
        $producto_id = (int)($_POST['producto_id'] ?? 0);
        
        $pdo = getConnection();
        // Baja lógica: marcar como inactivo
        $stmt = $pdo->prepare("UPDATE productos SET activo = 0 WHERE id_producto = ?");
        
        if ($stmt->execute([$producto_id])) {
            setFlashMessage('success', 'Producto eliminado exitosamente');
        } else {
            setFlashMessage('danger', 'Error al eliminar el producto');
        }
    }
}

header('Location: products.php');
exit();
?>
