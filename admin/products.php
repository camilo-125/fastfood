<?php
// RF07 - Gestionar menú
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('administrador');

// Obtener todos los productos
$pdo = getConnection();
$stmt = $pdo->query("SELECT * FROM productos ORDER BY categoria, nombre");
$productos = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="margin-top: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h1>Gestión de Productos</h1>
        <!-- Converted absolute URL to relative URL -->
        <a href="product-add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Producto
        </a>
    </div>
    
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['id_producto']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong><br>
                            <small style="color: var(--gray);"><?php echo htmlspecialchars($producto['descripcion']); ?></small>
                        </td>
                        <td><?php echo ucfirst(htmlspecialchars($producto['categoria'])); ?></td>
                        <td style="font-weight: bold; color: var(--primary);">
                            $<?php echo number_format($producto['precio'], 2); ?>
                        </td>
                        <td>
                            <?php if ($producto['activo']): ?>
                                <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 10px;">
                                <!-- Converted absolute URLs to relative URLs -->
                                <a href="product-edit.php?id=<?php echo $producto['id_producto']; ?>" 
                                   class="btn btn-outline" style="padding: 8px 16px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="product-delete.php" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="producto_id" value="<?php echo $producto['id_producto']; ?>">
                                    <button type="submit" class="btn btn-danger" style="padding: 8px 16px;"
                                            onclick="return confirm('¿Eliminar este producto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
