<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Si ya está logueado, redirigir
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $errors[] = 'Token de seguridad inválido';
    } else {
        // Obtener y sanitizar datos
        $nombre = sanitize($_POST['nombre'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $genero = sanitize($_POST['genero'] ?? '');
        
        // Validaciones (RF01)
        if (empty($nombre) || strlen($nombre) > 50) {
            $errors[] = 'El nombre es requerido y debe tener máximo 50 caracteres';
        }
        
        if (empty($email) || !isValidEmail($email) || strlen($email) > 50) {
            $errors[] = 'El email es inválido o excede 50 caracteres';
        }
        
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        if ($password !== $password_confirm) {
            $errors[] = 'Las contraseñas no coinciden';
        }
        
        if (!in_array($genero, ['masculino', 'femenino', 'otro'])) {
            $errors[] = 'Debe seleccionar un género válido';
        }
        
        // Verificar si el email ya existe
        if (empty($errors)) {
            try {
                $pdo = getConnection();
                $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->fetch()) {
                    $errors[] = 'Este email ya está registrado';
                }
            } catch (PDOException $e) {
                $errors[] = 'Error de conexión a la base de datos: ' . $e->getMessage();
            }
        }
        
        // Registrar usuario (RNF05 - Encriptación)
        if (empty($errors)) {
            try {
                $hashed_password = hashPassword($password);
                
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contrasena, genero, rol) VALUES (?, ?, ?, ?, 'cliente')");
                
                if ($stmt->execute([$nombre, $email, $hashed_password, $genero])) {
                    $success = true;
                    setFlashMessage('Registro exitoso. Por favor inicia sesión.', 'success');
                    header('Location: login.php');
                    exit();
                } else {
                    $errors[] = 'Error al registrar el usuario. Intenta nuevamente.';
                }
            } catch (PDOException $e) {
                $errors[] = 'Error al registrar el usuario: ' . $e->getMessage();
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 500px; margin-top: 60px;">
    <div class="card">
        <div class="card-body" style="padding: 40px;">
            <h2 style="text-align: center; margin-bottom: 30px;">Crear Cuenta</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="register.php" id="registerForm">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" name="nombre" class="form-control" required maxlength="50" 
                           value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                           placeholder="Ingresa tu nombre completo">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required maxlength="50"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           placeholder="tu@email.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Contraseña *</label>
                    <input type="password" name="password" class="form-control" required minlength="6"
                           placeholder="Mínimo 6 caracteres">
                    <small style="color: var(--gray);">La contraseña será encriptada de forma segura</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirmar Contraseña *</label>
                    <input type="password" name="password_confirm" class="form-control" required minlength="6"
                           placeholder="Repite tu contraseña">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Género *</label>
                    <select name="genero" class="form-select" required>
                        <option value="">Selecciona una opción</option>
                        <option value="masculino" <?php echo (($_POST['genero'] ?? '') === 'masculino') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="femenino" <?php echo (($_POST['genero'] ?? '') === 'femenino') ? 'selected' : ''; ?>>Femenino</option>
                        <option value="otro" <?php echo (($_POST['genero'] ?? '') === 'otro') ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                    Registrarse
                </button>
            </form>
            
            <p style="text-align: center; margin-top: 20px; color: var(--gray);">
                ¿Ya tienes cuenta? <a href="login.php" style="color: var(--primary); font-weight: 600;">Inicia sesión</a>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
