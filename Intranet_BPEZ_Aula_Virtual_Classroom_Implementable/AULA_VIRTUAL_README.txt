=====================================================================
 INTRANET BPEZ - MÓDULO AULA VIRTUAL (INTERFAZ TIPO CLASSROOM)
=====================================================================
Curso: Conociendo la Biblioteca Pública del Zulia "María Calcaño"
Servicio Comunitario - Objetivo I: Conocer la institución

CONTENIDO DEL PAQUETE (rutas relativas a la raíz del proyecto)
---------------------------------------------------------------------
modules/aula_virtual/aula_virtual.php   Acceso al módulo
modules/aula_virtual/classroom.php      Interfaz principal tipo Classroom
frontend/aula_virtual_frontend.php      Componente frontend (tarjeta / ítem de menú)
assets/materiales/aula_virtual/         Materiales académicos (PDF y DOCX)
AULA_VIRTUAL_README.txt                 Este documento
docs/                                   Documentación del proyecto

INSTALACIÓN (3 PASOS)
---------------------------------------------------------------------
1. Extraer el archivo en la RAÍZ del proyecto de la Intranet BPEZ
   (la misma carpeta que contiene modules/, frontend/ y assets/).
   Si el sistema pregunta por archivos existentes, revisar antes de
   sobrescribir; el paquete no incluye archivos fuera de las rutas
   listadas arriba.
2. Copiar el informe base en assets/materiales/aula_virtual/ con los
   nombres indicados en LEEME.txt.
3. Abrir en el navegador:  http://SERVIDOR/RUTA_PROYECTO/modules/aula_virtual/aula_virtual.php
   Para enlazarlo desde la Intranet, ver frontend/aula_virtual_frontend.php.

REQUISITOS
---------------------------------------------------------------------
- PHP 7.0 o superior (probado en sintaxis con PHP 8.3). No requiere base
  de datos ni extensiones adicionales.
- Navegador con JavaScript y localStorage habilitados.
- Conexión a internet para cargar Bootstrap 5.3.3 y Font Awesome 6.5.2
  desde CDN (o descargarlos y servirlos localmente).

INTEGRACIÓN CON LA SESIÓN DE LA INTRANET
---------------------------------------------------------------------
En modules/aula_virtual/aula_virtual.php:
  $requerirSesion = false;   // cambiar a true para exigir inicio de sesión
  $clavesSesion   = [...];   // claves de $_SESSION donde está el usuario
  $rutaLogin      = '../../index.php';
Ajustar estos valores a los del sistema real. Si no hay sesión, el aula
muestra "Estudiante".

FUNCIONAMIENTO
---------------------------------------------------------------------
- El progreso (temas + actividades) y el resultado del cuestionario se
  guardan en localStorage del navegador, por usuario. No se envían al
  servidor; cambiar de navegador o borrar datos reinicia el avance.
- Las preguntas del cuestionario se editan en el arreglo $preguntas de
  classroom.php (campo 'ok' = índice de la respuesta correcta).

DOCUMENTACIÓN COMPLETA: docs/DOCUMENTACION_PROYECTO.md
