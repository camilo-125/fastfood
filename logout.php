<?php
// RF04 - Cerrar sesión
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

// Destruir la sesión
destroySession();

// Mensaje de confirmación
setFlashMessage('Has cerrado sesión exitosamente', 'success');

header('Location: index.php');
exit();
?>
