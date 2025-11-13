<?php
// RF09 - Gestionar clientes
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('administrador');

// Obtener todos los usuarios
$pdo = getConnection();
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC");
$usuarios = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="margin-top: 40px;">
    <h1 style="margin-bottom: 40px;">Gestión de Clientes</h1>
    
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Género</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id_usuario']; ?></td>
                        <td><strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo ucfirst($usuario['genero']); ?></td>
                        <td>
                            <?php
                            $rol_badges = [
                                'cliente' => '<span class="badge badge-info">Cliente</span>',
                                'empleado' => '<span class="badge badge-warning">Empleado</span>',
                                'administrador' => '<span class="badge badge-danger">Admin</span>',
                            ];
                            echo $rol_badges[$usuario['rol']] ?? $usuario['rol'];
                            ?>
                        </td>
                        <td>
                            <?php if ($usuario['activo']): ?>
                                <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                        <td>
                            <div style="display: flex; gap: 10px;">
                                <!-- Converted absolute URLs to relative URLs -->
                                <a href="customer-edit.php?id=<?php echo $usuario['id_usuario']; ?>" 
                                   class="btn btn-outline" style="padding: 8px 16px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($usuario['id_usuario'] != $_SESSION['user_id']): ?>
                                <form method="POST" action="customer-delete.php" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="usuario_id" value="<?php echo $usuario['id_usuario']; ?>">
                                    <button type="submit" class="btn btn-danger" style="padding: 8px 16px;"
                                            onclick="return confirm('¿Desactivar este usuario?')">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
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
