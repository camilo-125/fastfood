<?php
// RF10 - Actualizar estado de pedidos
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('empleado');

$pedido_id = (int)($_GET['id'] ?? 0);

// Obtener información del pedido
$pdo = getConnection();
$stmt = $pdo->prepare("
    SELECT p.*, u.nombre as cliente_nombre
    FROM pedidos p
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    WHERE p.id_pedido = ?
");
$stmt->execute([$pedido_id]);
$pedido = $stmt->fetch();

if (!$pedido) {
    setFlashMessage('danger', 'Pedido no encontrado');
    header('Location: /code/employee/dashboard.php');
    exit();
}

$errors = [];

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $errors[] = 'Token de seguridad inválido';
    } else {
        $nuevo_estado = $_POST['estado'] ?? '';
        
        $estados_validos = ['Pendiente', 'En preparación', 'Listo', 'Entregado'];
        if (!in_array($nuevo_estado, $estados_validos)) {
            $errors[] = 'Estado inválido';
        }
        
        if (empty($errors)) {
            try {
                $pdo->beginTransaction();
                
                // Actualizar estado del pedido
                $stmt = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id_pedido = ?");
                $stmt->execute([$nuevo_estado, $pedido_id]);
                
                // Si se marca como entregado, registrar en historial (RF11)
                if ($nuevo_estado === 'Entregado') {
                    $stmt = $pdo->prepare("INSERT INTO historial_entregas (id_pedido, id_empleado) VALUES (?, ?)");
                    $stmt->execute([$pedido_id, $_SESSION['user_id']]);
                }
                
                $pdo->commit();
                
                setFlashMessage('success', 'Estado del pedido actualizado exitosamente');
                header('Location: /code/employee/dashboard.php');
                exit();
                
            } catch (Exception $e) {
                $pdo->rollBack();
                $errors[] = 'Error al actualizar el estado';
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="margin-top: 40px; max-width: 700px;">
    <a href="/code/employee/dashboard.php" class="btn btn-outline" style="margin-bottom: 20px;">
        <i class="fas fa-arrow-left"></i> Volver al Panel
    </a>
    
    <div class="card">
        <div class="card-body" style="padding: 40px;">
            <h2 style="margin-bottom: 30px;">Actualizar Estado del Pedido</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div style="background-color: var(--gray-light); padding: 20px; border-radius: 12px; margin-bottom: 30px;">
                <h3 style="margin-bottom: 15px;">Pedido #<?php echo $pedido['id_pedido']; ?></h3>
                <p style="margin-bottom: 5px;">
                    <strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['cliente_nombre']); ?>
                </p>
                <p style="margin-bottom: 5px;">
                    <strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?>
                </p>
                <p style="margin-bottom: 5px;">
                    <strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?>
                </p>
                <p>
                    <strong>Estado Actual:</strong> <?php echo getOrderStatusBadge($pedido['estado']); ?>
                </p>
            </div>
            
            <form method="POST" action="/code/employee/update-status.php?id=<?php echo $pedido_id; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label class="form-label" style="font-size: 1.1rem; margin-bottom: 15px;">
                        Seleccionar Nuevo Estado
                    </label>
                    
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <label style="display: flex; align-items: center; padding: 20px; border: 2px solid var(--gray-light); 
                                      border-radius: 12px; cursor: pointer; transition: all 0.3s;"
                               onmouseover="this.style.borderColor='var(--warning)'; this.style.backgroundColor='#fff3cd'"
                               onmouseout="this.style.borderColor='var(--gray-light)'; this.style.backgroundColor='white'">
                            <input type="radio" name="estado" value="Pendiente" 
                                   <?php echo $pedido['estado'] === 'Pendiente' ? 'checked' : ''; ?>
                                   style="width: 20px; height: 20px; margin-right: 15px;">
                            <div>
                                <strong style="font-size: 1.1rem;">Pendiente</strong>
                                <p style="color: var(--gray); margin: 5px 0 0 0; font-size: 0.9rem;">
                                    El pedido está en espera de ser procesado
                                </p>
                            </div>
                        </label>
                        
                        <label style="display: flex; align-items: center; padding: 20px; border: 2px solid var(--gray-light); 
                                      border-radius: 12px; cursor: pointer; transition: all 0.3s;"
                               onmouseover="this.style.borderColor='var(--info)'; this.style.backgroundColor='#cff4fc'"
                               onmouseout="this.style.borderColor='var(--gray-light)'; this.style.backgroundColor='white'">
                            <input type="radio" name="estado" value="En preparación" 
                                   <?php echo $pedido['estado'] === 'En preparación' ? 'checked' : ''; ?>
                                   style="width: 20px; height: 20px; margin-right: 15px;">
                            <div>
                                <strong style="font-size: 1.1rem;">En Preparación</strong>
                                <p style="color: var(--gray); margin: 5px 0 0 0; font-size: 0.9rem;">
                                    El pedido se está preparando en cocina
                                </p>
                            </div>
                        </label>
                        
                        <label style="display: flex; align-items: center; padding: 20px; border: 2px solid var(--gray-light); 
                                      border-radius: 12px; cursor: pointer; transition: all 0.3s;"
                               onmouseover="this.style.borderColor='var(--success)'; this.style.backgroundColor='#d1f2eb'"
                               onmouseout="this.style.borderColor='var(--gray-light)'; this.style.backgroundColor='white'">
                            <input type="radio" name="estado" value="Listo" 
                                   <?php echo $pedido['estado'] === 'Listo' ? 'checked' : ''; ?>
                                   style="width: 20px; height: 20px; margin-right: 15px;">
                            <div>
                                <strong style="font-size: 1.1rem;">Listo</strong>
                                <p style="color: var(--gray); margin: 5px 0 0 0; font-size: 0.9rem;">
                                    El pedido está listo para ser entregado
                                </p>
                            </div>
                        </label>
                        
                        <label style="display: flex; align-items: center; padding: 20px; border: 2px solid var(--gray-light); 
                                      border-radius: 12px; cursor: pointer; transition: all 0.3s;"
                               onmouseover="this.style.borderColor='var(--gray)'; this.style.backgroundColor='var(--gray-light)'"
                               onmouseout="this.style.borderColor='var(--gray-light)'; this.style.backgroundColor='white'">
                            <input type="radio" name="estado" value="Entregado" 
                                   <?php echo $pedido['estado'] === 'Entregado' ? 'checked' : ''; ?>
                                   style="width: 20px; height: 20px; margin-right: 15px;">
                            <div>
                                <strong style="font-size: 1.1rem;">Entregado</strong>
                                <p style="color: var(--gray); margin: 5px 0 0 0; font-size: 0.9rem;">
                                    El pedido ha sido entregado al cliente
                                </p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; font-size: 1.1rem;">
                        <i class="fas fa-save"></i> Actualizar Estado
                    </button>
                    <a href="/code/employee/dashboard.php" class="btn btn-outline" style="flex: 1; font-size: 1.1rem;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
