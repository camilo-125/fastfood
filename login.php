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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $errors[] = 'Token de seguridad inválido';
    } else {
        // Obtener datos (RF02)
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validaciones
        if (empty($email) || !isValidEmail($email)) {
            $errors[] = 'Email inválido';
        }
        
        if (empty($password)) {
            $errors[] = 'La contraseña es requerida';
        }
        
        // Autenticar usuario
        if (empty($errors)) {
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND activo = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            // Verificar contraseña (RNF05)
            if ($user && verifyPassword($password, $user['contrasena'])) {
                // Regenerar ID de sesión por seguridad
                regenerateSession();
                
                // Guardar datos en sesión (RNF04)
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_nombre'] = $user['nombre'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_rol'] = $user['rol'];
                
                if ($user['rol'] === 'administrador') {
                    header('Location: admin/dashboard.php');
                } elseif ($user['rol'] === 'empleado') {
                    header('Location: employee/dashboard.php');
                } else {
                    header('Location: menu.php');
                }
                exit();
            } else {
                $errors[] = 'Email o contraseña incorrectos';
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 500px; margin-top: 80px;">
    <div class="card">
        <div class="card-body" style="padding: 40px;">
            <h2 style="text-align: center; margin-bottom: 30px;">Iniciar Sesión</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Form action changed to relative path -->
            <form method="POST" action="login.php">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           placeholder="tu@email.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required
                           placeholder="Ingresa tu contraseña">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                    Iniciar Sesión
                </button>
            </form>
            
            <!-- Link changed to relative path -->
            <p style="text-align: center; margin-top: 20px; color: var(--gray);">
                ¿No tienes cuenta? <a href="register.php" style="color: var(--primary); font-weight: 600;">Regístrate aquí</a>
            </p>
            
            <div style="margin-top: 30px; padding: 20px; background-color: var(--gray-light); border-radius: 8px;">
                <p style="margin: 0; font-size: 0.9rem; color: var(--gray); text-align: center;">
                    <strong>Usuarios de prueba:</strong><br>
                    Admin: admin@fastbite.com<br>
                    Empleado: empleado@fastbite.com<br>
                    Cliente: cliente@fastbite.com<br>
                    Contraseña: admin123
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
