<?php
// Panel de administrador
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireRole('administrador');

// Obtener estadísticas generales
$pdo = getConnection();

$stmt = $pdo->query("SELECT COUNT(*) FROM productos WHERE activo = 1");
$total_productos = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente' AND activo = 1");
$total_clientes = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM pedidos");
$total_pedidos = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE estado = 'Entregado'");
$ventas_totales = $stmt->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" style="margin-top: 40px;">
    <h1 style="margin-bottom: 40px;">Panel de Administración</h1>
    
    <!-- Estadísticas -->
    <div class="grid grid-4" style="margin-bottom: 40px;">
        <div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white;">
            <div class="card-body" style="text-align: center; padding: 30px;">
                <i class="fas fa-utensils" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
                <h2 style="font-size: 2.5rem; margin-bottom: 5px;"><?php echo $total_productos; ?></h2>
                <p style="font-size: 1.1rem;">Productos</p>
            </div>
        </div>
        
        <div class="card" style="background: linear-gradient(135deg, var(--info) 0%, #0096c7 100%); color: white;">
            <div class="card-body" style="text-align: center; padding: 30px;">
                <i class="fas fa-users" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
                <h2 style="font-size: 2.5rem; margin-bottom: 5px;"><?php echo $total_clientes; ?></h2>
                <p style="font-size: 1.1rem;">Clientes</p>
            </div>
        </div>
        
        <div class="card" style="background: linear-gradient(135deg, var(--secondary) 0%, #e67300 100%); color: white;">
            <div class="card-body" style="text-align: center; padding: 30px;">
                <i class="fas fa-shopping-cart" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
                <h2 style="font-size: 2.5rem; margin-bottom: 5px;"><?php echo $total_pedidos; ?></h2>
                <p style="font-size: 1.1rem;">Pedidos</p>
            </div>
        </div>
        
        <div class="card" style="background: linear-gradient(135deg, var(--success) 0%, #00b894 100%); color: white;">
            <div class="card-body" style="text-align: center; padding: 30px;">
                <i class="fas fa-dollar-sign" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
                <h2 style="font-size: 2.5rem; margin-bottom: 5px;">$<?php echo number_format($ventas_totales, 0); ?></h2>
                <p style="font-size: 1.1rem;">Ventas Totales</p>
            </div>
        </div>
    </div>
    
    <!-- Accesos rápidos -->
    <h2 style="margin-bottom: 30px;">Gestión del Sistema</h2>
    
    <div class="grid grid-3">
        <!-- Converted absolute URLs to relative URLs -->
        <a href="products.php" class="card" style="text-decoration: none; transition: transform 0.3s;">
            <div class="card-body" style="text-align: center; padding: 40px;">
                <i class="fas fa-hamburger" style="font-size: 3rem; color: var(--primary); margin-bottom: 20px;"></i>
                <h3 style="color: var(--dark);">Gestionar Menú</h3>
                <p style="color: var(--gray);">Agregar, editar y eliminar productos</p>
            </div>
        </a>
        
        <a href="customers.php" class="card" style="text-decoration: none; transition: transform 0.3s;">
            <div class="card-body" style="text-align: center; padding: 40px;">
                <i class="fas fa-user-friends" style="font-size: 3rem; color: var(--info); margin-bottom: 20px;"></i>
                <h3 style="color: var(--dark);">Gestionar Clientes</h3>
                <p style="color: var(--gray);">Administrar usuarios del sistema</p>
            </div>
        </a>
        
        <a href="orders.php" class="card" style="text-decoration: none; transition: transform 0.3s;">
            <div class="card-body" style="text-align: center; padding: 40px;">
                <i class="fas fa-clipboard-list" style="font-size: 3rem; color: var(--secondary); margin-bottom: 20px;"></i>
                <h3 style="color: var(--dark);">Ver Todos los Pedidos</h3>
                <p style="color: var(--gray);">Historial completo de pedidos</p>
            </div>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
