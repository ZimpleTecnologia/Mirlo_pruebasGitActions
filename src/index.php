<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
// Configuración de colores corporativos
$colorPrimario = '#000000'; // Negro
$colorSecundario = '#FF6600'; // Naranja
$colorFile = __DIR__ . '/assets/color-primario.txt';
if (file_exists($colorFile)) {
    $colorPrimario = trim(file_get_contents($colorFile));
}
$colorFile = __DIR__ . '/assets/color-secundario.txt';
if (file_exists($colorFile)) {
    $colorSecundario = trim(file_get_contents($colorFile));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | Mirlo Construcciones S.A.S.</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: <?php echo $colorPrimario; ?>;
            --color-secondary: <?php echo $colorSecundario; ?>;
            --color-background: #F9F9F9;
            --color-text: #444444;
            --color-white: #FFFFFF;
            --border-radius: 12px;
            --box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            --box-shadow-hover: 0 8px 16px rgba(0,0,0,0.12);
            --transition: all 0.3s ease-in-out;
        }
        
        body {
            background-color: var(--color-background);
            min-height: 100vh;
            padding-top: 60px;
            font-family: 'Poppins', sans-serif;
            color: var(--color-text);
        }
        
        .logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        
        .navbar {
            background-color: var(--color-primary) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background: linear-gradient(to right, var(--color-secondary) 20%, var(--color-primary) 20%) !important;
            padding: 0.25rem 0.5rem !important; /* Reducir aún más la altura */
        }
        
        .navbar .container {
            padding-left: 0; /* Eliminar padding izquierdo */
        }
        
        .navbar-brand {
            margin-left: 0 !important; /* Mover al extremo izquierdo */
            padding-left: 5px !important;
        }
        
        .nav-link {
            padding: 0.4rem 0.8rem !important; /* Reducir padding de los enlaces */
        }
        
        .navbar-brand, .nav-link {
            color: var(--color-white) !important;
            transition: var(--transition);
        }
        
        .nav-link:hover {
            color: var(--color-secondary) !important;
        }
        
        .btn-custom {
            background-color: var(--color-secondary) !important;
            border-color: var(--color-secondary) !important;
            color: var(--color-white) !important;
            text-transform: uppercase;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            transition: var(--transition);
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            filter: brightness(90%);
            box-shadow: var(--box-shadow);
        }
        
        .btn-outline-custom {
            background-color: transparent !important;
            border-color: var(--color-secondary) !important;
            color: var(--color-text) !important;
            text-transform: uppercase;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            transition: var(--transition);
        }
        
        .btn-outline-custom:hover {
            background-color: var(--color-secondary) !important;
            color: var(--color-white) !important;
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
            background-color: var(--color-white);
        }
        
        .card:hover {
            transform: scale(1.03);
            box-shadow: var(--box-shadow-hover);
        }
        
        .card-title {
            color: var(--color-primary);
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .section-title {
            font-size: 2rem;
            color: var(--color-primary);
            margin-bottom: 2rem;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background-color: var(--color-secondary);
            bottom: -10px;
            left: 0;
        }
        
        /* Estilo para iconos */
        .bi {
            color: var(--color-secondary);
            font-size: 2.5rem;
            margin-bottom: 1rem;
            transition: var(--transition);
        }
        
        .card:hover .bi {
            transform: scale(1.1);
        }
        
        .icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: rgba(255, 102, 0, 0.1);
            margin: 0 auto 1.5rem;
            transition: var(--transition);
        }
        
        .card:hover .icon-circle {
            background-color: rgba(255, 102, 0, 0.2);
        }
        
        /* Footer */
        .footer {
            background-color: var(--color-white);
            padding: 1rem 0;
            margin-top: 3rem;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            font-size: 0.9rem;
        }
        
        /* User badge in navbar */
        .user-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 0.25rem 1rem;
            margin-left: 1rem;
            border: 1px solid var(--color-secondary);
        }
        
        .user-badge i {
            color: var(--color-secondary);
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }
        
        /* Spacing */
        .container {
            padding: 2rem 1rem;
        }
        
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .card-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
        
        @media (max-width: 576px) {
            .card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid px-2">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="assets/logo.png" alt="Logo" class="logo me-2" onerror="this.src=''" style="vertical-align: middle"> 
            <span class="fw-bold">Mirlo Construcciones</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="nomina.php">Nómina</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="inventario.php">Inventario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Notificaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="metricas.php">Métricas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="importar_usuarios.php">Asistencia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#customModal">Personalizar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">Salir</a>
                </li>
                <li class="nav-item d-none d-lg-block">
                    <div class="user-badge">
                        <i class="bi bi-person-circle"></i>
                        <span><?php echo htmlspecialchars($_SESSION['user']); ?></span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido principal -->
<div class="container">
    <div class="text-center mb-5">
        <h2 class="section-title">Bienvenido a Mirlo Construcciones</h2>
        <p class="lead">Selecciona un módulo para comenzar a gestionar tu empresa</p>
    </div>
    
    <div class="card-grid">
        <div class="card">
            <div class="card-body text-center">
                <div class="icon-circle">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h5 class="card-title">Gestión de Nómina</h5>
                <p class="card-text">Administra los pagos, beneficios y contratos de tus empleados.</p>
                <a href="nomina.php" class="btn btn-custom">Acceder</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="icon-circle">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h5 class="card-title">Inventario</h5>
                <p class="card-text">Controla materiales, herramientas y equipos de construcción.</p>
                <a href="inventario.php" class="btn btn-custom">Acceder</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="icon-circle">
                    <i class="bi bi-graph-up"></i>
                </div>
                <h5 class="card-title">Métricas</h5>
                <p class="card-text">Visualiza datos clave de rendimiento de tus proyectos.</p>
                <a href="metricas.php" class="btn btn-custom">Acceder</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="icon-circle">
                    <i class="bi bi-bell"></i>
                </div>
                <h5 class="card-title">Notificaciones</h5>
                <p class="card-text">Recibe alertas importantes sobre tus proyectos y tareas.</p>
                <a href="#" class="btn btn-custom">Acceder</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="icon-circle">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h5 class="card-title">Asistencia</h5>
                <p class="card-text">Controla la asistencia y horarios del personal.</p>
                <a href="importar_usuarios.php" class="btn btn-custom">Acceder</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="icon-circle">
                    <i class="bi bi-gear"></i>
                </div>
                <h5 class="card-title">Configuración</h5>
                <p class="card-text">Personaliza la apariencia y ajustes de la aplicación.</p>
                <a href="#" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#customModal">Acceder</a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer mt-auto py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <span class="text-muted">© 2024 Mirlo Construcciones S.A.S.</span>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span class="text-muted">Versión 1.0.0</span>
            </div>
        </div>
    </div>
</footer>

<!-- Modal para personalizar empresa -->
<div class="modal fade" id="customModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="upload_logo.php" enctype="multipart/form-data">
                <div class="modal-header" style="background-color: var(--color-primary); color: var(--color-white);">
                    <h5 class="modal-title">Personalizar empresa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="logo" class="form-label">Subir logo</label>
                        <input class="form-control" type="file" id="logo" name="logo" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="colorPrimario" class="form-label">Color Primario</label>
                        <input type="color" class="form-control form-control-color" id="colorPrimario" name="colorPrimario" value="<?php echo $colorPrimario; ?>" title="Elige el color primario">
                    </div>
                    <div class="mb-3">
                        <label for="colorSecundario" class="form-label">Color Secundario</label>
                        <input type="color" class="form-control form-control-color" id="colorSecundario" name="colorSecundario" value="<?php echo $colorSecundario; ?>" title="Elige el color secundario">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vista previa de colores</label>
                        <div class="d-flex">
                            <div class="p-3 me-2 rounded" style="background-color: <?php echo $colorPrimario; ?>; color: white; flex: 1;">
                                Color Primario
                            </div>
                            <div class="p-3 rounded" style="background-color: <?php echo $colorSecundario; ?>; color: white; flex: 1;">
                                Color Secundario
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-custom">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts (sólo Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Favicon -->
<link rel="icon" type="image/png" href="assets/favicon.ico">
</body>
</html> 