<?php
// RF07 - Ver detalles de un pedido
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

requireRole('cliente', 'admin');

$pedido_id = (int)($_GET['id'] ?? 0);
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'] ?? 'cliente';

if ($pedido_id <= 0) {
    setFlashMessage('danger', 'Pedido no encontrado');
    $redirect = ($user_role === 'admin') ? '../admin/orders.php' : 'orders.php';
    header("Location: $redirect");
    exit();
}

// Obtener información del pedido
$pdo = getConnection();

if ($user_role === 'admin') {
    $stmt = $pdo->prepare("SELECT p.*, u.nombre as nombre_cliente, u.email as email_cliente 
                          FROM pedidos p 
                          JOIN usuarios u ON p.id_usuario = u.id_usuario 
                          WHERE p.id_pedido = ?");
    $stmt->execute([$pedido_id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_pedido = ? AND id_usuario = ?");
    $stmt->execute([$pedido_id, $user_id]);
}

$pedido = $stmt->fetch();

if (!$pedido) {
    setFlashMessage('danger', 'Pedido no encontrado');
    $redirect = ($user_role === 'admin') ? '../admin/orders.php' : 'orders.php';
    header("Location: $redirect");
    exit();
}

// Obtener detalles del pedido
$stmt = $pdo->prepare("
    SELECT dp.*, p.nombre, p.imagen 
    FROM detalle_pedidos dp
    JOIN productos p ON dp.id_producto = p.id_producto
    WHERE dp.id_pedido = ?
");
$stmt->execute([$pedido_id]);
$detalles = $stmt->fetchAll();
?>

<div class="container" style="margin-top: 40px; margin-bottom: 80px;">
    <!-- Dynamic back link based on user role -->
    <?php 
    $back_link = ($user_role === 'admin') ? '../admin/orders.php' : 'orders.php';
    $back_text = ($user_role === 'admin') ? 'Volver a Pedidos' : 'Volver a Mis Pedidos';
    ?>
    <a href="<?php echo $back_link; ?>" class="btn btn-outline" style="margin-bottom: 30px;">
        <i class="fas fa-arrow-left"></i> <?php echo $back_text; ?>
    </a>
    
    <h1 style="text-align: center; margin-bottom: 40px;">Detalles del Pedido #<?php echo $pedido['id_pedido']; ?></h1>
    
    <div style="display: grid; gap: 30px;">
        <!-- Información del pedido -->
        <div class="card">
            <div class="card-body">
                <h3 style="margin-bottom: 20px;">Información del Pedido</h3>
                
                <!-- Show customer info for admins -->
                <?php if ($user_role === 'admin'): ?>
                <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
                    <h4 style="margin-bottom: 10px;">Cliente</h4>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['nombre_cliente']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($pedido['email_cliente']); ?></p>
                </div>
                <?php endif; ?>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <p style="color: var(--muted-foreground); font-size: 0.9rem; margin-bottom: 5px;">Estado</p>
                        <?php
                        $estado_class = match($pedido['estado']) {
                            'Pendiente' => 'background: #fff3cd; color: #664d03; padding: 8px 16px; border-radius: var(--radius); font-weight: 600; display: inline-block;',
                            'En preparación' => 'background: #cfe2ff; color: #084298; padding: 8px 16px; border-radius: var(--radius); font-weight: 600; display: inline-block;',
                            'Listo' => 'background: #d1f2eb; color: #0f5132; padding: 8px 16px; border-radius: var(--radius); font-weight: 600; display: inline-block;',
                            'Entregado' => 'background: #d1e7dd; color: #0a3622; padding: 8px 16px; border-radius: var(--radius); font-weight: 600; display: inline-block;',
                            'Cancelado' => 'background: #f8d7da; color: #842029; padding: 8px 16px; border-radius: var(--radius); font-weight: 600; display: inline-block;',
                            default => ''
                        };
                        ?>
                        <span style="<?php echo $estado_class; ?>">
                            <?php echo htmlspecialchars($pedido['estado']); ?>
                        </span>
                    </div>
                    
                    <div>
                        <p style="color: var(--muted-foreground); font-size: 0.9rem; margin-bottom: 5px;">Fecha</p>
                        <p style="font-weight: 600;"><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></p>
                    </div>
                    
                    <div>
                        <p style="color: var(--muted-foreground); font-size: 0.9rem; margin-bottom: 5px;">Total</p>
                        <p style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                            $<?php echo number_format($pedido['total'], 2); ?>
                        </p>
                    </div>
                </div>
                
                <?php if ($pedido['notas']): ?>
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                    <p style="color: var(--muted-foreground); font-size: 0.9rem; margin-bottom: 5px;">Notas del pedido</p>
                    <p><?php echo nl2br(htmlspecialchars($pedido['notas'])); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Productos del pedido -->
        <div class="card">
            <div class="card-body">
                <h3 style="margin-bottom: 20px;">Productos</h3>
                
                <?php foreach ($detalles as $detalle): ?>
                <div style="display: flex; gap: 20px; padding: 20px 0; border-bottom: 1px solid var(--border);">
                    <img src="../assets/images/<?php echo htmlspecialchars($detalle['imagen']); ?>" 
                         alt="<?php echo htmlspecialchars($detalle['nombre']); ?>"
                         style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius);"
                         onerror="this.src='../assets/images/placeholder.jpg'">
                    
                    <div style="flex: 1;">
                        <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($detalle['nombre']); ?></h4>
                        <p style="color: var(--muted-foreground); font-size: 0.9rem;">
                            Cantidad: <?php echo $detalle['cantidad']; ?> × $<?php echo number_format($detalle['precio_unitario'], 2); ?>
                        </p>
                    </div>
                    
                    <div style="text-align: right;">
                        <p style="font-size: 1.2rem; font-weight: 700; color: var(--primary);">
                            $<?php echo number_format($detalle['subtotal'], 2); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div style="text-align: right; padding-top: 20px;">
                    <p style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                        Total: $<?php echo number_format($pedido['total'], 2); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
