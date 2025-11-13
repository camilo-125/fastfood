<?php
// RF06 - Ver historial de pedidos
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

requireRole('cliente');

$user_id = $_SESSION['user_id'];

// Obtener pedidos del usuario
$pdo = getConnection();
$stmt = $pdo->prepare("
    SELECT p.*, 
           COUNT(dp.id_detalle) as total_items,
           GROUP_CONCAT(prod.nombre SEPARATOR ', ') as productos
    FROM pedidos p
    LEFT JOIN detalle_pedidos dp ON p.id_pedido = dp.id_pedido
    LEFT JOIN productos prod ON dp.id_producto = prod.id_producto
    WHERE p.id_usuario = ?
    GROUP BY p.id_pedido
    ORDER BY p.fecha_pedido DESC
");
$stmt->execute([$user_id]);
$pedidos = $stmt->fetchAll();
?>

<div class="container" style="margin-top: 40px; margin-bottom: 80px;">
    <h1 style="text-align: center; margin-bottom: 40px;">Mis Pedidos</h1>
    
    <?php if (empty($pedidos)): ?>
        <div style="text-align: center; padding: 60px 20px;">
            <i class="fas fa-receipt" style="font-size: 4rem; color: var(--muted-foreground); margin-bottom: 20px;"></i>
            <h3>No tienes pedidos aún</h3>
            <p style="color: var(--muted-foreground); margin: 20px 0;">Haz tu primer pedido desde nuestro menú</p>
            <!-- Changed from absolute path to relative path -->
            <a href="../menu.php" class="btn btn-primary" style="margin-top: 20px;">Ver Menú</a>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 20px;">
            <?php foreach ($pedidos as $pedido): ?>
            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
                        <div>
                            <h3 style="margin-bottom: 10px;">Pedido #<?php echo $pedido['id_pedido']; ?></h3>
                            <p style="color: var(--muted-foreground); font-size: 0.9rem;">
                                <i class="fas fa-calendar"></i> 
                                <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?>
                            </p>
                            <p style="color: var(--muted-foreground); font-size: 0.9rem;">
                                <i class="fas fa-shopping-bag"></i> 
                                <?php echo $pedido['total_items']; ?> producto(s)
                            </p>
                            <?php if ($pedido['notas']): ?>
                            <p style="color: var(--muted-foreground); font-size: 0.9rem; margin-top: 10px;">
                                <i class="fas fa-comment"></i> 
                                <strong>Notas:</strong> <?php echo htmlspecialchars($pedido['notas']); ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        
                        <div style="text-align: right;">
                            <?php
                            $estado_class = match($pedido['estado']) {
                                'Pendiente' => 'background: #fff3cd; color: #664d03; padding: 8px 16px; border-radius: var(--radius); font-weight: 600;',
                                'En preparación' => 'background: #cfe2ff; color: #084298; padding: 8px 16px; border-radius: var(--radius); font-weight: 600;',
                                'Listo' => 'background: #d1f2eb; color: #0f5132; padding: 8px 16px; border-radius: var(--radius); font-weight: 600;',
                                'Entregado' => 'background: #d1e7dd; color: #0a3622; padding: 8px 16px; border-radius: var(--radius); font-weight: 600;',
                                'Cancelado' => 'background: #f8d7da; color: #842029; padding: 8px 16px; border-radius: var(--radius); font-weight: 600;',
                                default => ''
                            };
                            ?>
                            <span style="<?php echo $estado_class; ?>">
                                <?php echo htmlspecialchars($pedido['estado']); ?>
                            </span>
                            <p style="font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-top: 15px;">
                                $<?php echo number_format($pedido['total'], 2); ?>
                            </p>
                            <!-- Changed from absolute path to relative path -->
                            <a href="order-details.php?id=<?php echo $pedido['id_pedido']; ?>" class="btn btn-outline" style="margin-top: 15px;">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
