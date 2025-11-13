<?php
// Ver todos los pedidos del sistema
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('administrador');

// Obtener todos los pedidos
$pdo = getConnection();
$stmt = $pdo->query("
    SELECT p.*, u.nombre as cliente_nombre, u.email as cliente_email
    FROM pedidos p
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    ORDER BY p.fecha_pedido DESC
    LIMIT 100
");
$pedidos = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="margin-top: 40px;">
    <h1 style="margin-bottom: 40px;">Todos los Pedidos</h1>
    
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><strong>#<?php echo $pedido['id_pedido']; ?></strong></td>
                        <td>
                            <?php echo htmlspecialchars($pedido['cliente_nombre']); ?><br>
                            <small style="color: var(--gray);"><?php echo htmlspecialchars($pedido['cliente_email']); ?></small>
                        </td>
                        <td style="font-weight: bold; color: var(--primary);">
                            $<?php echo number_format($pedido['total'], 2); ?>
                        </td>
                        <td><?php echo getOrderStatusBadge($pedido['estado']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></td>
                        <td>
                            <!-- Fixed path to point to customer/order-details.php instead of employee/order-details.php -->
                            <a href="../customer/order-details.php?id=<?php echo $pedido['id_pedido']; ?>" 
                               class="btn btn-outline" style="padding: 8px 16px;">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
