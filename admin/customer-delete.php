<?php
// RF09 - Eliminar/desactivar cliente
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('administrador');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('danger', 'Token de seguridad inválido');
    } else {
        $usuario_id = (int)($_POST['usuario_id'] ?? 0);
        
        // No permitir que el admin se desactive a sí mismo
        if ($usuario_id == $_SESSION['user_id']) {
            setFlashMessage('danger', 'No puedes desactivar tu propia cuenta');
        } else {
            $pdo = getConnection();
            // Baja lógica: marcar como inactivo
            $stmt = $pdo->prepare("UPDATE usuarios SET activo = 0 WHERE id_usuario = ?");
            
            if ($stmt->execute([$usuario_id])) {
                setFlashMessage('success', 'Usuario desactivado exitosamente');
            } else {
                setFlashMessage('danger', 'Error al desactivar el usuario');
            }
        }
    }
}

header('Location: customers.php');
exit();
?>
