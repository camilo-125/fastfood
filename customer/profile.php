<?php
// Customer Profile Page
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

requireRole('cliente');

$user_id = $_SESSION['user_id'];
$pdo = getConnection();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    
    // Validate inputs
    if (empty($nombre) || empty($email)) {
        setFlashMessage('El nombre y email son obligatorios', 'error');
    } else {
        // Check if email is already taken by another user
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?");
        $stmt->execute([$email, $user_id]);
        
        if ($stmt->fetch()) {
            setFlashMessage('El email ya está en uso por otro usuario', 'error');
        } else {
            // Update user information
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, direccion = ? WHERE id_usuario = ?");
            
            if ($stmt->execute([$nombre, $email, $telefono, $direccion, $user_id])) {
                $_SESSION['user_nombre'] = $nombre;
                $_SESSION['user_email'] = $email;
                setFlashMessage('Perfil actualizado exitosamente', 'success');
            } else {
                setFlashMessage('Error al actualizar el perfil', 'error');
            }
        }
    }
    
    header('Location: profile.php');
    exit();
}

// Get current user data
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: ../logout.php');
    exit();
}
?>

<div class="container" style="margin-top: 40px; margin-bottom: 80px;">
    <h1 style="text-align: center; margin-bottom: 40px;">Mi Perfil</h1>
    
    <div style="max-width: 600px; margin: 0 auto;">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="nombre" style="display: block; margin-bottom: 8px; font-weight: 600;">Nombre Completo *</label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($user['nombre']); ?>" 
                            required
                            style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius);"
                        >
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600;">Email *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($user['email']); ?>" 
                            required
                            style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius);"
                        >
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="telefono" style="display: block; margin-bottom: 8px; font-weight: 600;">Teléfono</label>
                        <input 
                            type="tel" 
                            id="telefono" 
                            name="telefono" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>"
                            style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius);"
                        >
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="direccion" style="display: block; margin-bottom: 8px; font-weight: 600;">Dirección</label>
                        <textarea 
                            id="direccion" 
                            name="direccion" 
                            class="form-control" 
                            rows="3"
                            style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius); resize: vertical;"
                        ><?php echo htmlspecialchars($user['direccion'] ?? ''); ?></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px;">
                        <a href="../index.php" class="btn btn-outline">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card" style="margin-top: 20px;">
            <div class="card-body">
                <h3 style="margin-bottom: 15px;">Información de la Cuenta</h3>
                <p style="color: var(--muted-foreground); margin-bottom: 10px;">
                    <strong>Fecha de registro:</strong> 
                    <?php echo date('d/m/Y', strtotime($user['fecha_registro'])); ?>
                </p>
                <p style="color: var(--muted-foreground);">
                    <strong>Rol:</strong> 
                    <?php echo ucfirst(htmlspecialchars($user['rol'])); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
