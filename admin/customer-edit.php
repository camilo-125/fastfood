<?php
// RF09 - Editar cliente
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('administrador');

$usuario_id = (int)($_GET['id'] ?? 0);

$pdo = getConnection();
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    setFlashMessage('danger', 'Usuario no encontrado');
    header('Location: customers.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $errors[] = 'Token de seguridad inválido';
    } else {
        $nombre = sanitize($_POST['nombre'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $rol = sanitize($_POST['rol'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (empty($nombre) || strlen($nombre) > 50) {
            $errors[] = 'El nombre es requerido (máximo 50 caracteres)';
        }
        
        if (empty($email) || !isValidEmail($email) || strlen($email) > 50) {
            $errors[] = 'Email inválido';
        }
        
        if (!in_array($rol, ['cliente', 'empleado', 'administrador'])) {
            $errors[] = 'Rol inválido';
        }
        
        // Verificar email duplicado
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?");
            $stmt->execute([$email, $usuario_id]);
            if ($stmt->fetch()) {
                $errors[] = 'Este email ya está en uso';
            }
        }
        
        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, rol = ?, activo = ? WHERE id_usuario = ?");
            
            if ($stmt->execute([$nombre, $email, $rol, $activo, $usuario_id])) {
                setFlashMessage('success', 'Usuario actualizado exitosamente');
                header('Location: customers.php');
                exit();
            } else {
                $errors[] = 'Error al actualizar el usuario';
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="margin-top: 40px; max-width: 700px;">
    <!-- Converted absolute URL to relative URL -->
    <a href="customers.php" class="btn btn-outline" style="margin-bottom: 20px;">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
    
    <div class="card">
        <div class="card-body" style="padding: 40px;">
            <h2 style="margin-bottom: 30px;">Editar Usuario #<?php echo $usuario_id; ?></h2>
            
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
            <form method="POST" action="customer-edit.php?id=<?php echo $usuario_id; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" required maxlength="50"
                           value="<?php echo htmlspecialchars($_POST['nombre'] ?? $usuario['nombre']); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required maxlength="50"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? $usuario['email']); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Rol *</label>
                    <select name="rol" class="form-select" required>
                        <?php
                        $roles = ['cliente' => 'Cliente', 'empleado' => 'Empleado', 'administrador' => 'Administrador'];
                        $rol_actual = $_POST['rol'] ?? $usuario['rol'];
                        foreach ($roles as $value => $label):
                        ?>
                        <option value="<?php echo $value; ?>" <?php echo $rol_actual === $value ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="activo" value="1" 
                               <?php echo (isset($_POST['activo']) ? $_POST['activo'] : $usuario['activo']) ? 'checked' : ''; ?>
                               style="width: 20px; height: 20px; margin-right: 10px;">
                        <span style="font-weight: 600;">Usuario Activo</span>
                    </label>
                    <small style="color: var(--gray);">Los usuarios inactivos no pueden iniciar sesión</small>
                </div>
                
                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <!-- Converted absolute URL to relative URL -->
                    <a href="customers.php" class="btn btn-outline" style="flex: 1;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
