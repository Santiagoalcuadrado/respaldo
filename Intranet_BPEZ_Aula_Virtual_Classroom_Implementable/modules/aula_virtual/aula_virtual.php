<?php
/**
 * Intranet BPEZ - Módulo Aula Virtual
 * Archivo: modules/aula_virtual/aula_virtual.php
 *
 * Punto de acceso al módulo. Inicia sesión (si no está iniciada),
 * aplica una verificación opcional de acceso y carga la interfaz
 * tipo Classroom (classroom.php).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
 * CONFIGURACIÓN DE INTEGRACIÓN
 * ----------------------------
 * - $requerirSesion : true  => exige usuario autenticado en la Intranet.
 * - $claveSesion    : nombre(s) de la variable de sesión donde la Intranet
 *                     guarda al usuario. Ajustar según el sistema existente.
 * - $rutaLogin      : ruta a la que se redirige si no hay sesión.
 */
$requerirSesion = false;
$clavesSesion   = ['usuario', 'nombre', 'user', 'username'];
$rutaLogin      = '../../index.php';

if ($requerirSesion) {
    $autenticado = false;
    foreach ($clavesSesion as $clave) {
        if (!empty($_SESSION[$clave])) {
            $autenticado = true;
            break;
        }
    }
    if (!$autenticado) {
        header('Location: ' . $rutaLogin);
        exit;
    }
}

require __DIR__ . '/classroom.php';
