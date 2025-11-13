<?php
// RF07 - Agregar producto
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('administrador');

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
        $imagen = sanitize($_POST['imagen'] ?? 'default.jpg');
        
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
        
        if (empty($categoria)) {
            $errors[] = 'La categoría es requerida';
        }
        
        if (empty($errors)) {
            $pdo = getConnection();
            $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, ingredientes, precio, categoria, imagen) VALUES (?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$nombre, $descripcion, $ingredientes, $precio, $categoria, $imagen])) {
                setFlashMessage('success', 'Producto agregado exitosamente');
                header('Location: products.php');
                exit();
            } else {
                $errors[] = 'Error al agregar el producto';
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
            <h2 style="margin-bottom: 30px;">Agregar Nuevo Producto</h2>
            
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
            <form method="POST" action="product-add.php">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label class="form-label">Nombre del Producto *</label>
                    <input type="text" name="nombre" class="form-control" required maxlength="50"
                           value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                           placeholder="Ej: Hamburguesa Clásica">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Descripción *</label>
                    <input type="text" name="descripcion" class="form-control" required maxlength="120"
                           value="<?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?>"
                           placeholder="Breve descripción del producto">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ingredientes *</label>
                    <input type="text" name="ingredientes" class="form-control" required maxlength="120"
                           value="<?php echo htmlspecialchars($_POST['ingredientes'] ?? ''); ?>"
                           placeholder="Ej: Carne, queso, lechuga, tomate">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Precio *</label>
                    <input type="number" name="precio" class="form-control" required step="0.01" min="0.01"
                           value="<?php echo htmlspecialchars($_POST['precio'] ?? ''); ?>"
                           placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Categoría *</label>
                    <select name="categoria" class="form-select" required>
                        <option value="">Seleccionar categoría</option>
                        <option value="hamburguesas" <?php echo (($_POST['categoria'] ?? '') === 'hamburguesas') ? 'selected' : ''; ?>>Hamburguesas</option>
                        <option value="pollo" <?php echo (($_POST['categoria'] ?? '') === 'pollo') ? 'selected' : ''; ?>>Pollo</option>
                        <option value="acompañamientos" <?php echo (($_POST['categoria'] ?? '') === 'acompañamientos') ? 'selected' : ''; ?>>Acompañamientos</option>
                        <option value="bebidas" <?php echo (($_POST['categoria'] ?? '') === 'bebidas') ? 'selected' : ''; ?>>Bebidas</option>
                        <option value="ensaladas" <?php echo (($_POST['categoria'] ?? '') === 'ensaladas') ? 'selected' : ''; ?>>Ensaladas</option>
                        <option value="otros" <?php echo (($_POST['categoria'] ?? '') === 'otros') ? 'selected' : ''; ?>>Otros</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nombre de Imagen</label>
                    <input type="text" name="imagen" class="form-control" maxlength="255"
                           value="<?php echo htmlspecialchars($_POST['imagen'] ?? 'default.jpg'); ?>"
                           placeholder="nombre-imagen.jpg">
                    <small style="color: var(--gray);">Nombre del archivo de imagen en /assets/images/</small>
                </div>
                
                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i> Guardar Producto
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
