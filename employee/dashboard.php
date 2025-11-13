<?php
// Panel del empleado - Ver pedidos
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('empleado');

// Definir la ruta base del proyecto
define('BASE_PATH', '/code');

// Obtener todos los pedidos
$pdo = getConnection();
$stmt = $pdo->query("
    SELECT p.*, u.nombre as cliente_nombre
    FROM pedidos p
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    ORDER BY p.fecha_pedido DESC
");
$pedidos = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="margin-top: 40px; max-width: 1000px;">
    <h1 style="margin-bottom: 30px;">Panel de Pedidos</h1>

    <?php if (empty($pedidos)): ?>
        <div class="alert alert-info">
            No hay pedidos registrados actualmente.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body" style="padding: 30px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?php echo $pedido['id_pedido']; ?></td>
                                <td><?php echo htmlspecialchars($pedido['cliente_nombre']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></td>
                                <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                                <td><?php echo getOrderStatusBadge($pedido['estado']); ?></td>
                                <td>
                                    <a href="order-details.php?id=<?php echo $pedido['id_pedido']; ?>"
                                       class="btn btn-sm btn-outline">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="update-status.php?id=<?php echo $pedido['id_pedido']; ?>"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Estado
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

ORDER:

<?php
// Ver detalles del pedido (empleado)
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('empleado');

$pedido_id = (int)($_GET['id'] ?? 0);

if ($pedido_id <= 0) {
    setFlashMessage('danger', 'ID de pedido inválido');
    header('Location: dashboard.php');
    exit();
}

// Obtener información del pedido
$pdo = getConnection();
$stmt = $pdo->prepare("
    SELECT p.*, u.nombre as cliente_nombre, u.email as cliente_email
    FROM pedidos p
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    WHERE p.id_pedido = ?
");
$stmt->execute([$pedido_id]);
$pedido = $stmt->fetch();

if (!$pedido) {
    setFlashMessage('danger', 'Pedido no encontrado');
    header('Location: dashboard.php');
    exit();
}

// Obtener detalles del pedido
$stmt = $pdo->prepare("
    SELECT dp.*, prod.nombre, prod.imagen, prod.ingredientes
    FROM detalle_pedidos dp
    JOIN productos prod ON dp.id_producto = prod.id_producto
    WHERE dp.id_pedido = ?
");
$stmt->execute([$pedido_id]);
$detalles = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="margin-top: 40px; max-width: 900px;">
    <a href="dashboard.php" class="btn btn-outline" style="margin-bottom: 20px;">
        <i class="fas fa-arrow-left"></i> Volver al Panel
    </a>
   
    <div class="card">
        <div class="card-body" style="padding: 40px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px;">
                <div>
                    <h1 style="margin-bottom: 10px;">Pedido #<?php echo $pedido['id_pedido']; ?></h1>
                    <p style="color: var(--gray); margin-bottom: 5px;">
                        <i class="fas fa-user"></i>
                        <strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['cliente_nombre']); ?>
                    </p>
                    <p style="color: var(--gray); margin-bottom: 5px;">
                        <i class="fas fa-envelope"></i>
                        <?php echo htmlspecialchars($pedido['cliente_email']); ?>
                    </p>
                    <p style="color: var(--gray);">
                        <i class="fas fa-calendar"></i>
                        <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?>
                    </p>
                </div>
                <?php echo getOrderStatusBadge($pedido['estado']); ?>
            </div>
           
            <?php if ($pedido['notas']): ?>
            <div style="background-color: #fff3cd; padding: 20px; border-radius: 8px; margin-bottom: 30px; border-left: 4px solid var(--warning);">
                <strong style="font-size: 1.1rem;"><i class="fas fa-sticky-note"></i> Notas del Cliente:</strong>
                <p style="margin-top: 10px; font-size: 1.05rem;"><?php echo htmlspecialchars($pedido['notas']); ?></p>
            </div>
            <?php endif; ?>
           
            <h3 style="margin-bottom: 20px;">Productos del Pedido</h3>
           
            <?php foreach ($detalles as $detalle): ?>
            <div style="display: flex; gap: 20px; padding: 20px;
                        border: 2px solid var(--gray-light); border-radius: 12px; margin-bottom: 15px;">
                <img src="../assets/images/<?php echo htmlspecialchars($detalle['imagen']); ?>"
                     alt="<?php echo htmlspecialchars($detalle['nombre']); ?>"
                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;"
                     onerror="this.src='../assets/placeholder.svg'">
               
                <div style="flex: 1;">
                    <h4 style="margin-bottom: 10px; font-size: 1.25rem;"><?php echo htmlspecialchars($detalle['nombre']); ?></h4>
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 10px;">
                        <strong>Ingredientes:</strong> <?php echo htmlspecialchars($detalle['ingredientes']); ?>
                    </p>
                    <p style="color: var(--gray);">
                        <strong>Cantidad:</strong> <?php echo $detalle['cantidad']; ?> x
                        $<?php echo number_format($detalle['precio_unitario'], 2); ?>
                    </p>
                </div>
               
                <div style="text-align: right;">
                    <p style="font-size: 1.5rem; font-weight: bold; color: var(--primary);">
                        $<?php echo number_format($detalle['subtotal'], 2); ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
           
            <div style="text-align: right; margin-top: 30px; padding-top: 20px;
                        border-top: 3px solid var(--gray-light);">
                <p style="font-size: 2rem; font-weight: bold; color: var(--primary);">
                    Total: $<?php echo number_format($pedido['total'], 2); ?>
                </p>
            </div>
           
            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <a href="update-status.php?id=<?php echo $pedido['id_pedido']; ?>"
                   class="btn btn-primary" style="flex: 1; font-size: 1.1rem;">
                    <i class="fas fa-edit"></i> Actualizar Estado
                </a>
                <a href="dashboard.php" class="btn btn-outline" style="flex: 1; font-size: 1.1rem;">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>