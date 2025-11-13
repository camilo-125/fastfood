# FastBite - Sistema de Comida Rápida

Versión PHP para XAMPP con diseño moderno y responsivo.

## 📋 Requisitos

- XAMPP (PHP 7.4 o superior)
- MySQL/MariaDB
- Navegador web moderno

## 🚀 Instalación

### 1. Copiar archivos a XAMPP

Copia todos los archivos de este proyecto a la carpeta `htdocs` de XAMPP:

\`\`\`
C:\xampp\htdocs\fastbite\
\`\`\`

### 2. Estructura de carpetas necesaria

Asegúrate de que existan estas carpetas:

\`\`\`
fastbite/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
├── config/
│   ├── database.php (si usas base de datos)
│   └── session.php
├── includes/
│   ├── header.php
│   └── footer.php
├── public/
│   └── (imágenes de productos)
├── index.php
└── README.md
\`\`\`

### 3. Configurar base de datos (opcional)

Si tu proyecto requiere base de datos, crea un archivo `config/database.php`:

\`\`\`php
<?php
function getConnection() {
    $host = 'localhost';
    $dbname = 'fastbite';
    $username = 'root';
    $password = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
?>
\`\`\`

### 4. Iniciar XAMPP

1. Abre el Panel de Control de XAMPP
2. Inicia Apache
3. Inicia MySQL (si usas base de datos)

### 5. Acceder al sitio

Abre tu navegador y visita:

\`\`\`
http://localhost/fastbite/
\`\`\`

## 🎨 Características

- ✅ Diseño moderno y responsivo
- ✅ Sistema de sesiones seguro
- ✅ Mensajes flash para notificaciones
- ✅ Header y footer reutilizables
- ✅ Navegación suave (smooth scroll)
- ✅ Menú móvil responsivo
- ✅ Paleta de colores profesional
- ✅ Tipografía optimizada

## 📁 Archivos principales

- **index.php**: Página principal con hero, menú destacado y características
- **includes/header.php**: Header con navegación y carrito
- **includes/footer.php**: Footer con enlaces y redes sociales
- **assets/css/style.css**: Estilos modernos con variables CSS
- **assets/js/main.js**: Funcionalidades JavaScript
- **config/session.php**: Manejo de sesiones y funciones auxiliares

## 🔧 Solución de problemas

### Error: "Call to undefined function getFlashMessage()"

✅ **Solucionado**: La función `getFlashMessage()` ya está incluida en `config/session.php`

### Las imágenes no se muestran

1. Verifica que las imágenes estén en la carpeta `public/`
2. Ajusta las rutas en `index.php` si es necesario

### Los estilos no se aplican

1. Verifica que la ruta en el header sea correcta: `/assets/css/style.css`
2. Limpia la caché del navegador (Ctrl + F5)

## 📝 Personalización

### Cambiar colores

Edita las variables CSS en `assets/css/style.css`:

\`\`\`css
:root {
  --primary: oklch(0.55 0.22 25);
  --secondary: oklch(0.65 0.18 65);
  /* ... más colores ... */
}
\`\`\`

### Agregar más productos

Edita el array `$menuItems` en `index.php`:

\`\`\`php
$menuItems = [
    [
        'name' => 'Tu Producto',
        'description' => 'Descripción del producto',
        'price' => '9.99',
        'image' => 'public/tu-imagen.jpg',
        'popular' => false
    ]
];
\`\`\`

## 📞 Soporte

Si encuentras problemas:

1. Verifica que Apache esté corriendo en XAMPP
2. Revisa los logs de error de PHP
3. Asegúrate de que todas las rutas sean correctas

## 📄 Licencia

Este proyecto es de código abierto y está disponible para uso personal y comercial.
