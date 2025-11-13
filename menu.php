<?php
// RF03 - Ver menú
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Obtener categorías
$pdo = getConnection();
$stmt = $pdo->query("SELECT DISTINCT categoria FROM productos WHERE activo = 1 ORDER BY categoria");
$categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Filtro por categoría
$categoria_filtro = $_GET['categoria'] ?? 'todas';

// Obtener productos
if ($categoria_filtro === 'todas') {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE activo = 1 ORDER BY categoria, nombre");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE activo = 1 AND categoria = ? ORDER BY nombre");
    $stmt->execute([$categoria_filtro]);
}
$productos = $stmt->fetchAll();
?>

<div class="container" style="margin-top: 40px;">
    <h1 style="text-align: center; margin-bottom: 40px;">Nuestro Menú</h1>
    
    <!-- Filtros de categoría con rutas relativas -->
    <div style="display: flex; justify-content: center; gap: 15px; margin-bottom: 40px; flex-wrap: wrap;">
        <a href="menu.php?categoria=todas" 
           class="btn <?php echo $categoria_filtro === 'todas' ? 'btn-primary' : 'btn-outline'; ?>">
            Todas
        </a>
        <?php foreach ($categorias as $cat): ?>
        <a href="menu.php?categoria=<?php echo urlencode($cat); ?>" 
           class="btn <?php echo $categoria_filtro === $cat ? 'btn-primary' : 'btn-outline'; ?>">
            <?php echo ucfirst(htmlspecialchars($cat)); ?>
        </a>
        <?php endforeach; ?>
    </div>
    
    <!-- Grid de productos -->
    <div class="grid grid-3">
        <?php foreach ($productos as $producto): ?>
        <div class="card">
            <!-- Rutas de imágenes con path relativo -->
            <img src="assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                 class="card-img"
                 onerror="this.src='/placeholder.svg?height=200&width=300'nombre']); ?>'">
            <div class="card-body">
                <h3 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                <p class="card-text" style="font-size: 0.9rem; color: var(--gray);">
                    <strong>Ingredientes:</strong> <?php echo htmlspecialchars($producto['ingredientes']); ?>
                </p>
                <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                <div class="card-price">$<?php echo number_format($producto['precio'], 2); ?></div>
                
                <?php if (isLoggedIn() && hasRole('cliente')): ?>
                    <!-- Form action con ruta relativa -->
                    <form method="POST" action="customer/add-to-cart.php" style="display: flex; gap: 10px; align-items: center;">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id_producto']; ?>">
                        <input type="number" name="cantidad" value="1" min="1" max="99" 
                               class="form-control" style="width: 70px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-cart-plus"></i> Agregar
                        </button>
                    </form>
                <?php else: ?>
                    <!-- Link con ruta relativa -->
                    <a href="login.php" class="btn btn-primary" style="width: 100%;">
                        Inicia sesión para ordenar
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($productos)): ?>
        <div style="text-align: center; padding: 60px 20px;">
            <i class="fas fa-utensils" style="font-size: 4rem; color: var(--gray); margin-bottom: 20px;"></i>
            <h3>No hay productos en esta categoría</h3>
            <!-- Link con ruta relativa -->
            <a href="menu.php?categoria=todas" class="btn btn-primary" style="margin-top: 20px;">Ver todos los productos</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
