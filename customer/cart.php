<?php
// RF04 - Ver carrito
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

requireRole('cliente');

// Inicializar carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$total = calculateCartTotal($cart);
?>

<div class="container" style="margin-top: 40px; margin-bottom: 80px;">
    <h1 style="text-align: center; margin-bottom: 40px;">Mi Carrito</h1>
    
    <?php if (empty($cart)): ?>
        <div style="text-align: center; padding: 60px 20px;">
            <i class="fas fa-shopping-cart" style="font-size: 4rem; color: var(--muted-foreground); margin-bottom: 20px;"></i>
            <h3>Tu carrito está vacío</h3>
            <p style="color: var(--muted-foreground); margin: 20px 0;">Agrega productos desde nuestro menú</p>
            <!-- Changed from absolute path to relative path -->
            <a href="../menu.php" class="btn btn-primary" style="margin-top: 20px;">Ver Menú</a>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 30px;">
            <!-- Productos en el carrito -->
            <div class="card" style="overflow: visible;">
                <div class="card-body">
                    <?php foreach ($cart as $index => $item): ?>
                    <div style="display: flex; gap: 20px; padding: 20px 0; border-bottom: 1px solid var(--border);">
                        <img src="../assets/images/<?php echo htmlspecialchars($item['imagen']); ?>" 
                             alt="<?php echo htmlspecialchars($item['nombre']); ?>"
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: var(--radius);"
                             onerror="this.src='../assets/images/placeholder.jpg'">
                        
                        <div style="flex: 1;">
                            <h3 style="margin-bottom: 10px;"><?php echo htmlspecialchars($item['nombre']); ?></h3>
                            <p style="color: var(--muted-foreground); font-size: 0.9rem;">
                                Precio unitario: $<?php echo number_format($item['precio'], 2); ?>
                            </p>
                            <p style="color: var(--primary); font-weight: 600; font-size: 1.1rem; margin-top: 10px;">
                                Subtotal: $<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?>
                            </p>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 10px; align-items: flex-end;">
                            <!-- Changed form action from absolute to relative path -->
                            <form method="POST" action="update-cart.php" style="display: flex; align-items: center; gap: 10px;">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                
                                <button type="submit" name="action" value="decrease" class="btn-icon" style="width: 35px; height: 35px; font-size: 1.2rem;">
                                    -
                                </button>
                                
                                <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" 
                                       min="1" max="99" readonly
                                       style="width: 60px; text-align: center; border: 1px solid var(--border); border-radius: var(--radius); padding: 8px;">
                                
                                <button type="submit" name="action" value="increase" class="btn-icon" style="width: 35px; height: 35px; font-size: 1.2rem;">
                                    +
                                </button>
                            </form>
                            
                            <!-- Changed form action from absolute to relative path -->
                            <form method="POST" action="update-cart.php" onsubmit="return confirm('¿Eliminar este producto del carrito?');">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <button type="submit" name="action" value="remove" class="btn btn-outline" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Resumen del pedido -->
            <div class="card">
                <div class="card-body">
                    <h3 style="margin-bottom: 20px;">Resumen del Pedido</h3>
                    
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span>Subtotal:</span>
                        <span style="font-weight: 600;">$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span>Envío:</span>
                        <span style="color: var(--secondary); font-weight: 600;">Gratis</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding: 20px 0; font-size: 1.3rem; font-weight: 700;">
                        <span>Total:</span>
                        <span style="color: var(--primary);">$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <!-- Changed form action from absolute to relative path -->
                    <form method="POST" action="checkout.php">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="form-group">
                            <label for="notas" class="form-label">Notas para el pedido (opcional)</label>
                            <textarea id="notas" name="notas" rows="3" class="form-control" 
                                      placeholder="Ej: Sin cebolla, extra picante..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1rem;">
                            <i class="fas fa-check-circle"></i> Realizar Pedido
                        </button>
                    </form>
                    
                    <!-- Changed from absolute path to relative path -->
                    <a href="../menu.php" class="btn btn-outline" style="width: 100%; margin-top: 10px; padding: 15px; font-size: 1rem;">
                        <i class="fas fa-arrow-left"></i> Seguir Comprando
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
