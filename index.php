<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Obtener productos destacados de la base de datos
$pdo = getConnection();
$stmt = $pdo->prepare("SELECT * FROM productos WHERE activo = 1 LIMIT 4");
$stmt->execute();
$productos_db = $stmt->fetchAll();

// Si no hay productos en la DB, usar datos de ejemplo
if (empty($productos_db)) {
    $menuItems = [
        [
            'id_producto' => 1,
            'nombre' => 'Mega Burger Clásica',
            'descripcion' => 'Doble carne, queso cheddar, lechuga, tomate y nuestra salsa especial',
            'ingredientes' => 'Doble carne, queso cheddar, lechuga, tomate y nuestra salsa especial',
            'precio' => '12.99',
            'imagen' => 'classic-double-cheeseburger-with-lettuce-and-tomat.jpg',
            'popular' => true
        ],
        [
            'id_producto' => 2,
            'nombre' => 'Pizza Suprema',
            'descripcion' => 'Pepperoni, champiñones, pimientos, aceitunas y extra queso',
            'ingredientes' => 'Pepperoni, champiñones, pimientos, aceitunas y extra queso',
            'precio' => '18.99',
            'imagen' => 'supreme-pizza-with-pepperoni-mushrooms-and-peppers.jpg',
            'popular' => true
        ],
        [
            'id_producto' => 3,
            'nombre' => 'Alitas BBQ',
            'descripcion' => '12 alitas crujientes con salsa BBQ ahumada y aderezo ranch',
            'ingredientes' => '12 alitas crujientes con salsa BBQ ahumada y aderezo ranch',
            'precio' => '14.99',
            'imagen' => 'crispy-bbq-chicken-wings-with-ranch-dressing.jpg',
            'popular' => false
        ],
        [
            'id_producto' => 4,
            'nombre' => 'Papas Cargadas',
            'descripcion' => 'Papas fritas con queso fundido, tocino y cebollín',
            'ingredientes' => 'Papas fritas con queso fundido, tocino y cebollín',
            'precio' => '8.99',
            'imagen' => 'loaded-french-fries-with-cheese-bacon-and-green-on.jpg',
            'popular' => false
        ]
    ];
} else {
    $menuItems = $productos_db;
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <div class="badge">
                    <span class="badge-dot"></span>
                    <span>Entrega en 30 minutos o gratis</span>
                </div>
                
                <h1 class="hero-title">Sabor auténtico en cada bocado</h1>
                
                <p class="hero-description">
                    Las mejores hamburguesas, pizzas y alitas de la ciudad. Ingredientes frescos, preparación rápida y sabor inolvidable.
                </p>
                
                <div class="hero-buttons">
                    <!-- Fixed link to point to menu.php -->
                    <a href="<?php echo $base_path; ?>/menu.php" class="btn btn-primary">
                        Ver Menú Completo
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="#promociones" class="btn btn-outline">Ofertas Especiales</a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat">
                        <div class="stat-value">50K+</div>
                        <div class="stat-label">Clientes felices</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <div class="stat-value">4.8★</div>
                        <div class="stat-label">Calificación promedio</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <div class="stat-value">30min</div>
                        <div class="stat-label">Tiempo de entrega</div>
                    </div>
                </div>
            </div>
            
            <div class="hero-image">
                <div class="hero-blob hero-blob-1"></div>
                <div class="hero-blob hero-blob-2"></div>
                <img src="<?php echo $base_path; ?>/public/delicious-gourmet-burger-with-fries-and-drink-on-w.jpg" alt="Hamburguesa deliciosa">
            </div>
        </div>
    </div>
</section>

<!-- Featured Menu -->
<section id="menu" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nuestros favoritos</h2>
            <p class="section-description">
                Los platillos más populares que nuestros clientes aman. Preparados frescos todos los días.
            </p>
        </div>
        
        <div class="menu-grid">
            <?php foreach ($menuItems as $item): ?>
            <div class="menu-card">
                <div class="menu-card-image">
                    <?php if (isset($item['popular']) && $item['popular']): ?>
                    <div class="menu-card-badge">Popular</div>
                    <?php endif; ?>
                    <img src="<?php echo $base_path; ?>/public/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                </div>
                <div class="menu-card-content">
                    <h3 class="menu-card-title"><?php echo htmlspecialchars($item['nombre']); ?></h3>
                    <p class="menu-card-description"><?php echo htmlspecialchars($item['descripcion'] ?? $item['ingredientes']); ?></p>
                    <div class="menu-card-footer">
                        <span class="menu-card-price">$<?php echo number_format($item['precio'], 2); ?></span>
                        
                        <!-- Added functional add to cart button -->
                        <?php if (isLoggedIn() && hasRole('cliente')): ?>
                            <form method="POST" action="<?php echo $base_path; ?>/customer/add-to-cart.php" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="producto_id" value="<?php echo $item['id_producto']; ?>">
                                <input type="hidden" name="cantidad" value="1">
                                <button type="submit" class="btn-icon" title="Agregar al carrito">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 5v14M5 12h14"/>
                                    </svg>
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo $base_path; ?>/login.php" class="btn-icon" title="Inicia sesión para ordenar">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="section-cta">
            <!-- Fixed link to point to menu.php -->
            <a href="<?php echo $base_path; ?>/menu.php" class="btn btn-outline-large">Ver Menú Completo</a>
        </div>
    </div>
</section>

<!-- Features -->
<section id="nosotros" class="section section-muted">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">¿Por qué elegirnos?</h2>
            <p class="section-description">
                Nos comprometemos a brindarte la mejor experiencia en comida rápida
            </p>
        </div>
        
        <div class="features-grid">
            <div class="feature">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="3" width="15" height="13"/>
                        <path d="M16 8V3l5 5-5 5v-2"/>
                    </svg>
                </div>
                <h3 class="feature-title">Entrega rápida</h3>
                <p class="feature-description">Recibe tu pedido en 30 minutos o es gratis</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <h3 class="feature-title">Abierto 24/7</h3>
                <p class="feature-description">Disponibles todos los días, a cualquier hora</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Calidad garantizada</h3>
                <p class="feature-description">Ingredientes frescos y de primera calidad</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h3 class="feature-title">Opciones saludables</h3>
                <p class="feature-description">Menú con alternativas vegetarianas y veganas</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
