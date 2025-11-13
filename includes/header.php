<?php
require_once __DIR__ . '/../config/session.php';

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $redirect = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: " . $redirect);
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);

function getBaseUrl() {
    $protocol = 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    
    // Get directory of current script
    $dir = dirname($script);
    
    // Remove /customer or /includes if present
    $dir = preg_replace('#/(customer|includes|admin|employee)$#', '', $dir);
    
    // Ensure no trailing slash except for root
    $dir = $dir === '/' ? '' : rtrim($dir, '/');
    
    return $protocol . $host . $dir;
}

function getRelativePath() {
    $script = $_SERVER['SCRIPT_NAME'];
    $dir = dirname($script);
    
    // Remove /customer or /includes if present
    $dir = preg_replace('#/(customer|includes|admin|employee)$#', '', $dir);
    
    // Ensure no trailing slash except for root
    $dir = $dir === '/' ? '' : rtrim($dir, '/');
    
    return $dir;
}

$base_url = getBaseUrl();
$base_path = getRelativePath();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastBite - Comida rápida deliciosa</title>
    <!-- Using base_url for CSS to ensure proper loading from any directory -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="header-left">
                    <a href="<?php echo $base_path; ?>/index.php" class="logo">
                        <div class="logo-icon">
                            <span>F</span>
                        </div>
                        <span class="logo-text">FastBite</span>
                    </a>
                    
                    <nav class="nav">
                        <a href="<?php echo $base_path; ?>/menu.php" class="nav-link <?php echo $current_page === 'menu.php' ? 'active' : ''; ?>">Menú</a>
                        <a href="<?php echo $base_path; ?>/index.php#promociones" class="nav-link">Promociones</a>
                        <a href="<?php echo $base_path; ?>/index.php#nosotros" class="nav-link">Nosotros</a>
                        <a href="<?php echo $base_path; ?>/index.php#contacto" class="nav-link">Contacto</a>
                    </nav>
                </div>
                
                <div class="header-right">
                    <?php if (isLoggedIn() && hasRole('cliente')): ?>
                        <a href="<?php echo $base_path; ?>/customer/cart.php" class="btn-cart">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"/>
                                <circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="cart-badge"><?php echo count($_SESSION['cart']); ?></span>
                            <?php else: ?>
                                <span class="cart-badge">0</span>
                            <?php endif; ?>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo $base_path; ?>/login.php" class="btn-cart" title="Inicia sesión para ver tu carrito">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"/>
                                <circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                            <span class="cart-badge">0</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php if (hasRole('cliente')): ?>
                            <div class="dropdown">
                                <button class="btn btn-primary">
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($_SESSION['user_nombre']); ?>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_path; ?>/customer/profile.php"><i class="fas fa-user-circle"></i> Mi Perfil</a></li>
                                    <li><a href="<?php echo $base_path; ?>/customer/orders.php"><i class="fas fa-receipt"></i> Mis Pedidos</a></li>
                                    <li><a href="<?php echo $base_path; ?>/customer/cart.php"><i class="fas fa-shopping-cart"></i> Mi Carrito</a></li>
                                    <li><a href="<?php echo $base_path; ?>/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                                </ul>
                            </div>
                        <?php elseif (hasRole('empleado')): ?>
                            <a href="<?php echo $base_path; ?>/employee/dashboard.php" class="btn btn-primary">Panel Empleado</a>
                            <a href="<?php echo $base_path; ?>/logout.php" class="btn btn-outline">Cerrar Sesión</a>
                        <?php elseif (hasRole('administrador')): ?>
                            <a href="<?php echo $base_path; ?>/admin/dashboard.php" class="btn btn-primary">Panel Admin</a>
                            <a href="<?php echo $base_path; ?>/logout.php" class="btn btn-outline">Cerrar Sesión</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo $base_path; ?>/login.php" class="btn btn-primary <?php echo $current_page === 'login.php' ? 'active' : ''; ?>">Iniciar Sesión</a>
                        <a href="<?php echo $base_path; ?>/register.php" class="btn btn-primary <?php echo $current_page === 'register.php' ? 'active' : ''; ?>">Registrarse</a>
                    <?php endif; ?>
                    <button class="btn-menu">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12h18M3 6h18M3 18h18"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <main class="main-content">
        <?php
        $flash = getFlashMessage();
        if ($flash):
        ?>
        <div class="alert alert-<?php echo $flash['type']; ?>">
            <?php echo htmlspecialchars($flash['message']); ?>
        </div>
        <?php endif; ?>
