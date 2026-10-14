<?php
/**
 * Intranet BPEZ - Aula Virtual (interfaz tipo Classroom)
 * Archivo: modules/aula_virtual/classroom.php
 *
 * Lenguajes : PHP, HTML5, CSS3, JavaScript
 * Tecnologías: Bootstrap 5, Font Awesome 6, localStorage
 *
 * Es autosuficiente: no depende de includes del sistema anfitrión.
 * Si existe una sesión de la Intranet, toma de ella el nombre del usuario.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---------- Usuario actual (con respaldo "Estudiante") ---------- */
$usuario = 'Estudiante';
foreach (['nombre', 'usuario', 'user', 'username'] as $k) {
    if (!empty($_SESSION[$k]) && is_string($_SESSION[$k])) {
        $usuario = $_SESSION[$k];
        break;
    }
}
$usuarioSeguro = htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8');

/* ---------- Materiales (carpeta del módulo) ---------- */
$dirMateriales = realpath(__DIR__ . '/../../assets/materiales/aula_virtual');
$urlMateriales = '../../assets/materiales/aula_virtual/';
$materiales = [
    'pdf'  => 'Informe_Biblioteca_Publica_del_Zulia.pdf',
    'docx' => 'Informe_Biblioteca_Publica_del_Zulia.docx',
];
/* Primera letra (UTF-8) sin depender de la extensión mbstring */
function bpez_inicial($texto) {
    return preg_match('/^./u', $texto, $m) ? $m[0] : '?';
}
function bpez_material_existe($dir, $archivo) {
    return $dir && is_file($dir . DIRECTORY_SEPARATOR . $archivo);
}
$hayPdf  = bpez_material_existe($dirMateriales, $materiales['pdf']);
$hayDocx = bpez_material_existe($dirMateriales, $materiales['docx']);

/* ---------- Datos del curso ---------- */
$tituloCurso = 'Conociendo la Biblioteca Pública del Zulia “María Calcaño”';

$temas = [
    ['t1', 'fa-bullseye',       'Misión, visión y valores',        'Comprende la identidad institucional.'],
    ['t2', 'fa-sitemap',        'Estructura organizacional',       'Niveles estratégico, staff y operativo.'],
    ['t3', 'fa-landmark',       'Reseña histórica y objetivos',    'Antecedentes desde 1873 y objetivos institucionales.'],
    ['t4', 'fa-briefcase',      'Funciones institucionales',       'Presidencia, calidad y gerencias.'],
    ['t5', 'fa-book-open-reader','Servicios y María Calcaño',      'Cinco servicios y biografía de la epónima.'],
];

$actividades = [
    ['a1', 'fa-id-card',      'Identificación institucional',
        'Identifica la institución, su administración (FUNDABIPEZ), misión, visión y valores.'],
    ['a2', 'fa-diagram-project', 'Mapa de estructura',
        'Elabora un esquema con el organigrama y las funciones de Presidencia, Gestión de Calidad, Servicios Bibliotecológicos y Gerencia Social.'],
    ['a3', 'fa-book-reader',  'Servicios de la biblioteca',
        'Describe los cinco servicios de la Biblioteca Pública del Zulia.'],
];

$contenidos = [
    'Misión, visión y valores.',
    'Estructura organizacional de FUNDABIPEZ.',
    'Reseña histórica y objetivos institucionales.',
    'Organigrama estructural.',
    'Funciones de la Presidencia.',
    'Funciones de la Oficina de Gestión de Calidad.',
    'Funciones de la Gerencia de Servicios Bibliotecológicos.',
    'Funciones de la Gerencia Social.',
    'Cinco servicios de la Biblioteca Pública del Zulia.',
    'Biografía de María Calcaño.',
];

$autores = [
    'Jhon Benavides', 'Andres Ferrer', 'Moises Alvarez', 'Santiago Ledesma',
];

/* Preguntas del cuestionario (editables). 'ok' = índice de la respuesta correcta. */
$preguntas = [
    ['q' => '¿Qué entidad administra la Biblioteca Pública del Zulia “María Calcaño”?',
     'o' => ['FUNDABIPEZ', 'La Universidad Dr. José Gregorio Hernández', 'Una plataforma educativa externa', 'Ninguna de las anteriores'], 'ok' => 0],
    ['q' => '¿A qué objetivo del Servicio Comunitario corresponde este curso?',
     'o' => ['Objetivo I: Conocer la institución', 'Objetivo II: Diseñar la solución', 'Objetivo III: Implementar el sistema', 'Objetivo IV: Evaluar resultados'], 'ok' => 0],
    ['q' => '¿Cuántos servicios de la Biblioteca Pública del Zulia se estudian en el curso?',
     'o' => ['Tres', 'Cinco', 'Siete', 'Diez'], 'ok' => 1],
    ['q' => 'Los antecedentes históricos de la institución se remontan al año:',
     'o' => ['1873', '1923', '1973', '2003'], 'ok' => 0],
    ['q' => '¿Por qué se estudia la biografía de María Calcaño?',
     'o' => ['Es la figura epónima de la biblioteca', 'Es la autora del informe académico', 'Es la presidenta actual de FUNDABIPEZ', 'Es la desarrolladora de la Intranet'], 'ok' => 0],
];

$datosJs = [
    'usuario'   => $usuario,
    'temas'     => array_column($temas, 0),
    'actividades' => array_column($actividades, 0),
    'preguntas' => array_map(function ($p) { return ['ok' => $p['ok']]; }, $preguntas),
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aula Virtual BPEZ · <?= htmlspecialchars($tituloCurso, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Bootstrap 5 y Font Awesome 6 (CDN). Para uso sin internet, descargar y servir localmente. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --av-azul: #003366;
            --av-azul-2: #0077b6;
            --av-cian: #00b8d9;
            --av-cian-suave: #e6f5f6;
            --av-fondo: #f5f7fb;
            --av-borde: #dfe5ee;
            --av-verde: #1e7a34;
        }
        body.av-body { background: var(--av-fondo); color: #2b3a4a; }
        .av-wrap { max-width: 1240px; }

        /* Cabecera */
        .av-hero {
            background: linear-gradient(135deg, var(--av-azul) 0%, #0b4a7d 100%);
            color: #fff; border-radius: 24px; padding: 2rem 2rem 1.5rem;
        }
        .av-hero .av-kicker { color: #9fe8f5; font-weight: 700; letter-spacing: .06em; font-size: .8rem; text-transform: uppercase; }
        .av-hero h1 { font-size: clamp(1.4rem, 3vw, 2rem); font-weight: 700; margin: .4rem 0; }
        .av-hero p { color: #d7e6f3; max-width: 640px; margin-bottom: 1rem; }
        .av-chip { display: inline-block; background: rgba(255,255,255,.16); border-radius: 999px; padding: .3rem .85rem; font-size: .82rem; margin: 0 .4rem .4rem 0; }
        .av-chip i { color: var(--av-cian); margin-right: .35rem; }

        /* Navegación lateral */
        .av-nav { background: #fff; border: 1px solid var(--av-borde); border-radius: 20px; padding: .75rem; }
        .av-nav .nav-link { color: #4a5b6c; border-radius: 14px; padding: .75rem 1rem; font-size: .95rem; text-align: left; }
        .av-nav .nav-link i { width: 1.4rem; color: var(--av-azul-2); }
        .av-nav .nav-link.active { background: var(--av-cian-suave); color: var(--av-azul); font-weight: 700; }
        @media (max-width: 991.98px) {
            .av-nav .nav { flex-direction: row !important; flex-wrap: nowrap; overflow-x: auto; }
            .av-nav .nav-link { white-space: nowrap; }
        }

        /* Tarjetas */
        .av-card { background: #fff; border: 1px solid var(--av-borde); border-radius: 20px; box-shadow: 0 2px 10px rgba(0,51,102,.05); padding: 1.25rem 1.5rem; }
        .av-card h2, .av-card h3 { color: var(--av-azul); font-weight: 700; }
        .av-titulo-seccion { color: var(--av-azul); font-weight: 700; font-size: 1.2rem; margin: 1.5rem 0 .75rem; }

        .progress { height: 12px; border-radius: 999px; background: #e7edf3; }
        .progress-bar { background: linear-gradient(90deg, var(--av-azul-2), var(--av-cian)); border-radius: 999px; transition: width .4s ease; }

        .av-avatar { width: 46px; height: 46px; border-radius: 50%; background: var(--av-cian-suave); color: var(--av-azul); font-weight: 700; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; }
        .av-item { display: flex; align-items: center; gap: 1rem; background: #fff; border: 1px solid var(--av-borde); border-radius: 16px; padding: .85rem 1.1rem; margin-bottom: .75rem; transition: box-shadow .2s, border-color .2s; }
        .av-item:hover { box-shadow: 0 4px 14px rgba(0,51,102,.08); border-color: #c5d3e3; }
        .av-item .av-ico { width: 42px; height: 42px; border-radius: 50%; background: var(--av-cian-suave); color: var(--av-azul-2); display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; }
        .av-item .av-info { flex: 1 1 auto; min-width: 0; }
        .av-item .av-info strong { color: var(--av-azul); display: block; }
        .av-item .av-info small { color: #6b7b8c; }
        .av-item.hecho { background: #f3faf5; border-color: #bfe3c8; }
        .av-item.hecho .av-ico { background: #dff3e4; color: var(--av-verde); }
        .form-check-input.av-check { width: 1.4rem; height: 1.4rem; cursor: pointer; border-color: var(--av-verde); }
        .form-check-input.av-check:checked { background-color: var(--av-verde); border-color: var(--av-verde); }

        .btn-av { background: var(--av-azul-2); border-color: var(--av-azul-2); color: #fff; border-radius: 12px; font-weight: 600; }
        .btn-av:hover { background: var(--av-azul); border-color: var(--av-azul); color: #fff; }
        .btn-av-suave { background: #eaf2f7; color: var(--av-azul); border-radius: 12px; font-weight: 600; border: 0; }
        .btn-av-suave:hover { background: #d8e7f0; color: var(--av-azul); }
        .btn-av-suave.disabled, .btn-av.disabled { opacity: .55; pointer-events: none; }

        .av-seccion { display: none; }
        .av-seccion.activa { display: block; animation: avfade .25s ease; }
        @keyframes avfade { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

        .av-pregunta { border-top: 1px solid var(--av-borde); padding: 1rem 0; }
        .av-pregunta:first-of-type { border-top: 0; }
        .av-opcion { display: block; border: 1px solid var(--av-borde); border-radius: 12px; padding: .55rem .9rem; margin-top: .45rem; cursor: pointer; }
        .av-opcion:hover { background: #f4f8fb; }
        .av-opcion.ok { background: #e3f4e8; border-color: #8fd0a0; }
        .av-opcion.mal { background: #fde8e8; border-color: #eba5a5; }
        .av-resultado { border-radius: 16px; padding: 1rem 1.25rem; background: var(--av-cian-suave); color: var(--av-azul); font-weight: 600; }

        .av-persona { text-align: center; }
        .av-persona .av-avatar { width: 64px; height: 64px; font-size: 1.3rem; margin-bottom: .6rem; }
    </style>
</head>
<body class="av-body">

<div class="container av-wrap py-4">

    <!-- ============ CABECERA DEL CURSO ============ -->
    <header class="av-hero mb-4">
        <div class="av-kicker">Aula Virtual BPEZ · Curso</div>
        <h1><?= htmlspecialchars($tituloCurso, ENT_QUOTES, 'UTF-8') ?></h1>
        <p>Curso introductorio para comprender la institución, su misión, visión, valores, estructura, funciones, servicios y la figura de María Calcaño.</p>
        <span class="av-chip"><i class="fa-solid fa-flag"></i>Servicio Comunitario · Objetivo I</span>
        <span class="av-chip"><i class="fa-solid fa-user"></i><?= $usuarioSeguro ?></span>
        <span class="av-chip"><i class="fa-solid fa-file-lines"></i>12 páginas de material</span>
    </header>

    <div class="row g-4">

        <!-- ============ NAVEGACIÓN LATERAL ============ -->
        <aside class="col-lg-3">
            <nav class="av-nav" aria-label="Navegación del aula">
                <div class="nav flex-lg-column" role="tablist">
                    <button class="nav-link active" data-seccion="principal"><i class="fa-solid fa-house"></i>Principal</button>
                    <button class="nav-link" data-seccion="trabajo"><i class="fa-solid fa-clipboard-list"></i>Trabajo de clase</button>
                    <button class="nav-link" data-seccion="actividades"><i class="fa-solid fa-pen-to-square"></i>Actividades</button>
                    <button class="nav-link" data-seccion="cuestionario"><i class="fa-solid fa-circle-question"></i>Cuestionario</button>
                    <button class="nav-link" data-seccion="personas"><i class="fa-solid fa-users"></i>Personas</button>
                </div>
            </nav>
        </aside>

        <main class="col-lg-9">

            <!-- ============ PRINCIPAL ============ -->
            <section id="sec-principal" class="av-seccion activa">
                <div class="av-card mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong style="color:var(--av-azul)">Progreso del curso</strong>
                        <strong id="av-pct" style="color:var(--av-azul)">0%</strong>
                    </div>
                    <div class="progress" role="progressbar" aria-label="Progreso del curso" aria-valuemin="0" aria-valuemax="100">
                        <div id="av-barra" class="progress-bar" style="width:0%"></div>
                    </div>
                </div>

                <article class="av-card mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="av-avatar">B</span>
                        <div>
                            <h2 class="h5 mb-0">Bienvenidos al aula virtual</h2>
                            <small class="text-secondary">Material de apertura · Objetivo I</small>
                        </div>
                    </div>
                    <p class="mb-3">En este espacio se estudia la institución donde se desarrollará la prestación del Servicio Comunitario: la Biblioteca Pública del Zulia “María Calcaño”. El recorrido aborda misión, visión, valores, estructura, funciones, servicios y la biografía de María Calcaño.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if ($hayPdf): ?>
                            <a class="btn btn-av" target="_blank" rel="noopener" href="<?= $urlMateriales . rawurlencode($materiales['pdf']) ?>"><i class="fa-solid fa-file-pdf me-2"></i>Abrir informe en PDF</a>
                        <?php else: ?>
                            <a class="btn btn-av disabled" tabindex="-1" aria-disabled="true" title="Copie el PDF en assets/materiales/aula_virtual/"><i class="fa-solid fa-file-pdf me-2"></i>Abrir informe en PDF</a>
                        <?php endif; ?>
                        <?php if ($hayDocx): ?>
                            <a class="btn btn-av-suave" download href="<?= $urlMateriales . rawurlencode($materiales['docx']) ?>"><i class="fa-solid fa-file-word me-2"></i>Descargar Word</a>
                        <?php else: ?>
                            <a class="btn btn-av-suave disabled" tabindex="-1" aria-disabled="true" title="Copie el DOCX en assets/materiales/aula_virtual/"><i class="fa-solid fa-file-word me-2"></i>Descargar Word</a>
                        <?php endif; ?>
                    </div>
                </article>

                <h2 class="av-titulo-seccion">Temas del curso</h2>
                <?php foreach ($temas as $i => $t): ?>
                    <div class="av-item" data-item="<?= $t[0] ?>">
                        <span class="av-ico"><i class="fa-solid <?= $t[1] ?>"></i></span>
                        <div class="av-info">
                            <strong><?= ($i + 1) ?>. <?= htmlspecialchars($t[2], ENT_QUOTES, 'UTF-8') ?></strong>
                            <small><?= htmlspecialchars($t[3], ENT_QUOTES, 'UTF-8') ?></small>
                        </div>
                        <input class="form-check-input av-check" type="checkbox" data-check="<?= $t[0] ?>" aria-label="Marcar tema <?= ($i + 1) ?> como completado">
                    </div>
                <?php endforeach; ?>
            </section>

            <!-- ============ TRABAJO DE CLASE ============ -->
            <section id="sec-trabajo" class="av-seccion">
                <div class="av-card mb-3">
                    <h2 class="h5"><i class="fa-solid fa-clipboard-list me-2"></i>Contenidos académicos del curso</h2>
                    <p class="text-secondary">Contenidos organizados a partir del informe del Servicio Comunitario sobre la Biblioteca Pública del Zulia “María Calcaño”, administrada por FUNDABIPEZ.</p>
                    <ol class="mb-0">
                        <?php foreach ($contenidos as $c): ?>
                            <li class="mb-1"><?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ol>
                </div>
                <div class="av-card">
                    <h3 class="h6">Materiales del módulo</h3>
                    <ul class="mb-0">
                        <li>Informe base en PDF (lectura): <?= $hayPdf ? '<span class="text-success">disponible</span>' : '<span class="text-danger">pendiente de copiar</span>' ?></li>
                        <li>Informe base en DOCX (descarga y consulta): <?= $hayDocx ? '<span class="text-success">disponible</span>' : '<span class="text-danger">pendiente de copiar</span>' ?></li>
                    </ul>
                </div>
            </section>

            <!-- ============ ACTIVIDADES ============ -->
            <section id="sec-actividades" class="av-seccion">
                <h2 class="av-titulo-seccion mt-0">Actividades</h2>
                <?php foreach ($actividades as $a): ?>
                    <div class="av-item" data-item="<?= $a[0] ?>">
                        <span class="av-ico"><i class="fa-solid <?= $a[1] ?>"></i></span>
                        <div class="av-info">
                            <strong><?= htmlspecialchars($a[2], ENT_QUOTES, 'UTF-8') ?></strong>
                            <small><?= htmlspecialchars($a[3], ENT_QUOTES, 'UTF-8') ?></small>
                        </div>
                        <button type="button" class="btn btn-av btn-sm" data-act="<?= $a[0] ?>">Realizar</button>
                    </div>
                <?php endforeach; ?>
            </section>

            <!-- ============ CUESTIONARIO ============ -->
            <section id="sec-cuestionario" class="av-seccion">
                <div class="av-card">
                    <h2 class="h5"><i class="fa-solid fa-circle-question me-2"></i>Cuestionario de repaso</h2>
                    <p class="text-secondary">Cinco preguntas de selección. El resultado se calcula en tu navegador.</p>
                    <form id="av-quiz" onsubmit="return false;">
                        <?php foreach ($preguntas as $qi => $p): ?>
                            <div class="av-pregunta" data-q="<?= $qi ?>">
                                <strong><?= ($qi + 1) ?>. <?= htmlspecialchars($p['q'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <?php foreach ($p['o'] as $oi => $op): ?>
                                    <label class="av-opcion">
                                        <input class="form-check-input me-2" type="radio" name="q<?= $qi ?>" value="<?= $oi ?>">
                                        <?= htmlspecialchars($op, ENT_QUOTES, 'UTF-8') ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" id="av-calcular" class="btn btn-av">Calcular resultado</button>
                            <button type="button" id="av-reiniciar-quiz" class="btn btn-av-suave">Reintentar</button>
                        </div>
                    </form>
                    <div id="av-resultado" class="av-resultado mt-3 d-none" role="status"></div>
                </div>
            </section>

            <!-- ============ PERSONAS ============ -->
            <section id="sec-personas" class="av-seccion">
                <h2 class="av-titulo-seccion mt-0">Autores del informe académico</h2>
                <div class="row g-3">
                    <?php foreach ($autores as $nombre): ?>
                        <div class="col-6 col-md-3">
                            <div class="av-card av-persona">
                                <span class="av-avatar"><?= htmlspecialchars(bpez_inicial($nombre), ENT_QUOTES, 'UTF-8') ?></span>
                                <div><strong><?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?></strong></div>
                                <small class="text-secondary">Autor del informe</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

        </main>
    </div>
</div>

<script>
(function () {
    'use strict';
    var DATA = <?= json_encode($datosJs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    var KEY = 'bpez_aula_virtual_v1_' + DATA.usuario;
    var TOTAL = DATA.temas.length + DATA.actividades.length;

    /* ---- Almacenamiento (localStorage con tolerancia a fallos) ---- */
    function leer() {
        try { return JSON.parse(localStorage.getItem(KEY)) || { done: {}, score: null }; }
        catch (e) { return { done: {}, score: null }; }
    }
    function guardar(estado) {
        try { localStorage.setItem(KEY, JSON.stringify(estado)); } catch (e) { /* modo privado */ }
    }
    var estado = leer();

    /* ---- Progreso ---- */
    function pintar() {
        var hechos = 0;
        DATA.temas.concat(DATA.actividades).forEach(function (id) {
            var ok = !!estado.done[id];
            if (ok) hechos++;
            document.querySelectorAll('[data-item="' + id + '"]').forEach(function (el) {
                el.classList.toggle('hecho', ok);
            });
            var chk = document.querySelector('[data-check="' + id + '"]');
            if (chk) chk.checked = ok;
            var btn = document.querySelector('[data-act="' + id + '"]');
            if (btn) {
                btn.textContent = ok ? 'Completada ✓' : 'Realizar';
                btn.classList.toggle('btn-av', !ok);
                btn.classList.toggle('btn-av-suave', ok);
            }
        });
        var pct = Math.round((hechos / TOTAL) * 100);
        document.getElementById('av-pct').textContent = pct + '%';
        var barra = document.getElementById('av-barra');
        barra.style.width = pct + '%';
        barra.parentElement.setAttribute('aria-valuenow', pct);
    }
    function alternar(id, valor) {
        estado.done[id] = (typeof valor === 'boolean') ? valor : !estado.done[id];
        guardar(estado);
        pintar();
    }
    document.querySelectorAll('[data-check]').forEach(function (c) {
        c.addEventListener('change', function () { alternar(c.getAttribute('data-check'), c.checked); });
    });
    document.querySelectorAll('[data-act]').forEach(function (b) {
        b.addEventListener('click', function () { alternar(b.getAttribute('data-act')); });
    });

    /* ---- Navegación lateral ---- */
    var botones = document.querySelectorAll('.av-nav .nav-link');
    function mostrar(nombre) {
        document.querySelectorAll('.av-seccion').forEach(function (s) {
            s.classList.toggle('activa', s.id === 'sec-' + nombre);
        });
        botones.forEach(function (b) {
            b.classList.toggle('active', b.getAttribute('data-seccion') === nombre);
        });
    }
    botones.forEach(function (b) {
        b.addEventListener('click', function () { mostrar(b.getAttribute('data-seccion')); });
    });

    /* ---- Cuestionario ---- */
    var resultado = document.getElementById('av-resultado');
    function mostrarResultado(puntaje) {
        resultado.classList.remove('d-none');
        resultado.textContent = 'Resultado: ' + puntaje + ' de ' + DATA.preguntas.length +
            ' respuestas correctas (' + Math.round(puntaje / DATA.preguntas.length * 100) + '%).';
    }
    document.getElementById('av-calcular').addEventListener('click', function () {
        var puntaje = 0, sinResponder = 0;
        DATA.preguntas.forEach(function (p, i) {
            var cont = document.querySelector('[data-q="' + i + '"]');
            var sel = cont.querySelector('input[type=radio]:checked');
            var labels = cont.querySelectorAll('.av-opcion');
            labels.forEach(function (l) { l.classList.remove('ok', 'mal'); });
            if (!sel) { sinResponder++; return; }
            var v = parseInt(sel.value, 10);
            if (v === p.ok) { puntaje++; labels[v].classList.add('ok'); }
            else { labels[v].classList.add('mal'); labels[p.ok].classList.add('ok'); }
        });
        if (sinResponder > 0) {
            resultado.classList.remove('d-none');
            resultado.textContent = 'Responde las ' + DATA.preguntas.length + ' preguntas para calcular el resultado (faltan ' + sinResponder + ').';
            return;
        }
        estado.score = puntaje;
        guardar(estado);
        mostrarResultado(puntaje);
    });
    document.getElementById('av-reiniciar-quiz').addEventListener('click', function () {
        document.getElementById('av-quiz').reset();
        document.querySelectorAll('.av-opcion').forEach(function (l) { l.classList.remove('ok', 'mal'); });
        resultado.classList.add('d-none');
        estado.score = null;
        guardar(estado);
    });
    if (estado.score !== null && estado.score !== undefined) { mostrarResultado(estado.score); }

    pintar();
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
