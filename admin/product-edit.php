<?php
// RF07 y RF08 - Editar producto y actualizar precios
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('administrador');

$producto_id = (int)($_GET['id'] ?? 0);

$pdo = getConnection();
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id_producto = ?");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch();

if (!$producto) {
    setFlashMessage('danger', 'Producto no encontrado');
    header('Location: products.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $errors[] = 'Token de seguridad inválido';
    } else {
        $nombre = sanitize($_POST['nombre'] ?? '');
        $descripcion = sanitize($_POST['descripcion'] ?? '');
        $ingredientes = sanitize($_POST['ingredientes'] ?? '');
        $precio = floatval($_POST['precio'] ?? 0);
        $categoria = sanitize($_POST['categoria'] ?? '');
        $imagen = sanitize($_POST['imagen'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (empty($nombre) || strlen($nombre) > 50) {
            $errors[] = 'El nombre es requerido (máximo 50 caracteres)';
        }
        
        if (empty($descripcion) || strlen($descripcion) > 120) {
            $errors[] = 'La descripción es requerida (máximo 120 caracteres)';
        }
        
        if (empty($ingredientes) || strlen($ingredientes) > 120) {
            $errors[] = 'Los ingredientes son requeridos (máximo 120 caracteres)';
        }
        
        if ($precio <= 0) {
            $errors[] = 'El precio debe ser mayor a 0';
        }
        
        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, ingredientes = ?, precio = ?, categoria = ?, imagen = ?, activo = ? WHERE id_producto = ?");
            
            if ($stmt->execute([$nombre, $descripcion, $ingredientes, $precio, $categoria, $imagen, $activo, $producto_id])) {
                setFlashMessage('success', 'Producto actualizado exitosamente');
                header('Location: products.php');
                exit();
            } else {
                $errors[] = 'Error al actualizar el producto';
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="margin-top: 40px; max-width: 800px;">
    <!-- Converted absolute URL to relative URL -->
    <a href="products.php" class="btn btn-outline" style="margin-bottom: 20px;">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
    
    <div class="card">
        <div class="card-body" style="padding: 40px;">
            <h2 style="margin-bottom: 30px;">Editar Producto #<?php echo $producto_id; ?></h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Converted absolute URL to relative URL -->
            <form method="POST" action="product-edit.php?id=<?php echo $producto_id; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label class="form-label">Nombre del Producto *</label>
                    <input type="text" name="nombre" class="form-control" required maxlength="50"
                           value="<?php echo htmlspecialchars($_POST['nombre'] ?? $producto['nombre']); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Descripción *</label>
                    <input type="text" name="descripcion" class="form-control" required maxlength="120"
                           value="<?php echo htmlspecialchars($_POST['descripcion'] ?? $producto['descripcion']); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ingredientes *</label>
                    <input type="text" name="ingredientes" class="form-control" required maxlength="120"
                           value="<?php echo htmlspecialchars($_POST['ingredientes'] ?? $producto['ingredientes']); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Precio * (RF08)</label>
                    <input type="number" name="precio" class="form-control" required step="0.01" min="0.01"
                           value="<?php echo htmlspecialchars($_POST['precio'] ?? $producto['precio']); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Categoría *</label>
                    <select name="categoria" class="form-select" required>
                        <?php
                        $categorias = ['hamburguesas', 'pollo', 'acompañamientos', 'bebidas', 'ensaladas', 'otros'];
                        $cat_actual = $_POST['categoria'] ?? $producto['categoria'];
                        foreach ($categorias as $cat):
                        ?>
                        <option value="<?php echo $cat; ?>" <?php echo $cat_actual === $cat ? 'selected' : ''; ?>>
                            <?php echo ucfirst($cat); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nombre de Imagen</label>
                    <input type="text" name="imagen" class="form-control" maxlength="255"
                           value="<?php echo htmlspecialchars($_POST['imagen'] ?? $producto['imagen']); ?>">
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="activo" value="1" 
                               <?php echo (isset($_POST['activo']) ? $_POST['activo'] : $producto['activo']) ? 'checked' : ''; ?>
                               style="width: 20px; height: 20px; margin-right: 10px;">
                        <span style="font-weight: 600;">Producto Activo</span>
                    </label>
                    <small style="color: var(--gray);">Los productos inactivos no se muestran en el menú</small>
                </div>
                
                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <!-- Converted absolute URL to relative URL -->
                    <a href="products.php" class="btn btn-outline" style="flex: 1;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
