<?php
/**
 * Intranet BPEZ - Componente frontend del Aula Virtual
 * Archivo: frontend/aula_virtual_frontend.php
 *
 * Uso desde cualquier página PHP de la Intranet (menú, dashboard, inicio):
 *
 *     require_once __DIR__ . '/frontend/aula_virtual_frontend.php';
 *     aula_virtual_tarjeta();                    // tarjeta de acceso
 *     aula_virtual_enlace_menu();                // ítem <li> para un menú
 *
 * Si la página que lo incluye NO está en la raíz del proyecto,
 * pasar la ruta correcta como argumento, p. ej.:
 *     aula_virtual_tarjeta('../modules/aula_virtual/aula_virtual.php');
 *
 * Abierto directamente en el navegador, muestra una página mínima de acceso.
 */

if (!function_exists('aula_virtual_url')) {
    function aula_virtual_url($ruta = null) {
        return $ruta !== null ? $ruta : 'modules/aula_virtual/aula_virtual.php';
    }
}

if (!function_exists('aula_virtual_tarjeta')) {
    /** Tarjeta de acceso al Aula Virtual (Bootstrap 5 + Font Awesome). */
    function aula_virtual_tarjeta($ruta = null) {
        $href = htmlspecialchars(aula_virtual_url($ruta), ENT_QUOTES, 'UTF-8');
        echo '<div class="card border-0 shadow-sm" style="border-radius:20px;max-width:420px">'
           . '<div class="card-body p-4">'
           . '<div class="d-flex align-items-center gap-3 mb-2">'
           . '<span style="width:46px;height:46px;border-radius:50%;background:#e6f5f6;color:#003366;display:inline-flex;align-items:center;justify-content:center">'
           . '<i class="fa-solid fa-graduation-cap"></i></span>'
           . '<h5 class="mb-0" style="color:#003366;font-weight:700">Aula Virtual BPEZ</h5></div>'
           . '<p class="text-secondary mb-3">Curso: Conociendo la Biblioteca Pública del Zulia “María Calcaño”.</p>'
           . '<a class="btn" style="background:#0077b6;color:#fff;border-radius:12px;font-weight:600" href="' . $href . '">'
           . '<i class="fa-solid fa-arrow-right me-2"></i>Entrar al aula</a>'
           . '</div></div>';
    }
}

if (!function_exists('aula_virtual_enlace_menu')) {
    /** Ítem de menú <li> listo para insertar en un <ul class="nav">. */
    function aula_virtual_enlace_menu($ruta = null) {
        $href = htmlspecialchars(aula_virtual_url($ruta), ENT_QUOTES, 'UTF-8');
        echo '<li class="nav-item"><a class="nav-link" href="' . $href . '">'
           . '<i class="fa-solid fa-graduation-cap me-2"></i>Aula Virtual</a></li>';
    }
}

/* Vista independiente cuando se abre este archivo directamente */
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aula Virtual BPEZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body style="background:#f5f7fb">
    <div class="container py-5">
        <?php aula_virtual_tarjeta('../modules/aula_virtual/aula_virtual.php'); ?>
    </div>
</body>
</html>
    <?php
}
