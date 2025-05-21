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

// Datos de ejemplo para las métricas (reemplazar con datos reales de la BD)
$datosNomina = [
    'total_empleados' => 150,
    'total_nomina' => 250000000,
    'promedio_salario' => 1666666,
    'nuevos_mes' => 5
];

$asistencia = [
    'presentes' => 142,
    'ausentes' => 8,
    'tardanzas' => 12,
    'horas_extra' => 256
];

$proyectos = [
    'activos' => 3,
    'completados' => 8,
    'en_proceso' => 2,
    'porcentaje_avance' => 75
];

// Datos para horas trabajadas por empleado
$horasTrabajadas = [
    [
        'empleado' => 'Juan Pérez',
        'horas' => 180,
        'horas_extra' => 15,
        'proyecto' => 'Torre Mirlo'
    ],
    [
        'empleado' => 'María Rodríguez',
        'horas' => 176,
        'horas_extra' => 12,
        'proyecto' => 'Charleston'
    ],
    [
        'empleado' => 'Carlos López',
        'horas' => 168,
        'horas_extra' => 8,
        'proyecto' => 'Torre Mirlo'
    ],
    [
        'empleado' => 'Ana Martínez',
        'horas' => 182,
        'horas_extra' => 18,
        'proyecto' => 'Charleston'
    ]
];

// Datos de rendimiento por proyecto
$rendimientoProyectos = [
    [
        'proyecto' => 'Torre Mirlo',
        'presupuesto_inicial' => 1200000000,
        'gasto_actual' => 800000000,
        'avance' => 65,
        'empleados_asignados' => 45
    ],
    [
        'proyecto' => 'Charleston',
        'presupuesto_inicial' => 950000000,
        'gasto_actual' => 520000000,
        'avance' => 55,
        'empleados_asignados' => 38
    ]
];

// Indicadores de seguridad laboral
$seguridadLaboral = [
    'incidentes_mes' => 2,
    'dias_sin_accidentes' => 145,
    'capacitaciones_realizadas' => 8,
    'empleados_capacitados' => 120
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métricas | Mirlo Construcciones S.A.S.</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos para la barra de navegación */
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

        .logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .navbar {
            background-color: var(--color-primary) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background: linear-gradient(to right, var(--color-secondary) 20%, var(--color-primary) 20%) !important;
            padding: 0.25rem 0.5rem !important;
        }

        .navbar .container-fluid {
            padding-left: 0;
        }

        .navbar-brand {
            margin-left: 0 !important;
            padding-left: 5px !important;
            color: var(--color-white) !important;
            transition: var(--transition);
        }

        .nav-link {
            padding: 0.4rem 0.8rem !important;
            color: var(--color-white) !important;
            transition: var(--transition);
        }

        .nav-link:hover {
            color: var(--color-secondary) !important;
        }

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
        
        /* ... Estilos existentes ... */
        
        .metric-card {
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: var(--color-white);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-hover);
        }
        
        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--color-primary);
        }
        
        .metric-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
            margin-bottom: 2rem;
        }

        /* Estilos para el footer */
        .footer {
            background-color: var(--color-white);
            border-top: 1px solid rgba(0,0,0,0.1);
            position: relative;
            bottom: 0;
            width: 100%;
        }

        /* Asegura que el contenido principal no se solape con el footer */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1 0 auto;
        }

        /* Ocultar footer en impresión */
        @media print {
            .footer {
                display: none;
            }
        }
    </style>
</head>
<body>
<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid px-2">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
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
                    <a class="nav-link" href="#">Inventario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Notificaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="metricas.php">Métricas</a>
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
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">
            <i class="bi bi-graph-up me-2"></i> Dashboard
        </h2>
        <div>
            <button class="btn btn-custom" onclick="window.print()">
                <i class="bi bi-printer"></i> Imprimir Reporte
            </button>
        </div>
    </div>

    <!-- Tarjetas de métricas principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon" style="background-color: rgba(var(--color-primary), 0.1)">
                    <i class="bi bi-people-fill fs-3" style="color: var(--color-primary)"></i>
                </div>
                <div class="metric-value"><?php echo number_format($datosNomina['total_empleados']); ?></div>
                <div class="metric-label">Total Empleados</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon" style="background-color: rgba(var(--color-secondary), 0.1)">
                    <i class="bi bi-currency-dollar fs-3" style="color: var(--color-secondary)"></i>
                </div>
                <div class="metric-value">$<?php echo number_format($datosNomina['total_nomina']/1000000, 1); ?>M</div>
                <div class="metric-label">Nómina Mensual</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon" style="background-color: rgba(25, 135, 84, 0.1)">
                    <i class="bi bi-building fs-3 text-success"></i>
                </div>
                <div class="metric-value"><?php echo $proyectos['activos']; ?></div>
                <div class="metric-label">Proyectos Activos</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon" style="background-color: rgba(220, 53, 69, 0.1)">
                    <i class="bi bi-clock-history fs-3 text-danger"></i>
                </div>
                <div class="metric-value"><?php echo number_format($asistencia['horas_extra']); ?></div>
                <div class="metric-label">Horas Extra (Mes)</div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <!-- Gráfico de Asistencia -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Asistencia Diaria</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="asistenciaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Proyectos -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Estado de Proyectos</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="proyectosChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuevos gráficos -->
    <div class="row">
        <!-- Top Empleados por Horas -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Empleados por Horas Trabajadas</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="horasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rendimiento de Proyectos -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Rendimiento de Proyectos</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="rendimientoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs Detallados -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Indicadores Clave de Rendimiento</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Indicador</th>
                                    <th>Valor Actual</th>
                                    <th>Meta</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tasa de Asistencia</td>
                                    <td><?php echo round(($asistencia['presentes']/$datosNomina['total_empleados'])*100, 1); ?>%</td>
                                    <td>95%</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: 94%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Avance de Proyectos</td>
                                    <td><?php echo $proyectos['porcentaje_avance']; ?>%</td>
                                    <td>80%</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" style="width: 75%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Agregar más KPIs según necesidad -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas de Seguridad -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Seguridad Laboral</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="metric-card text-center">
                                <div class="metric-icon mx-auto" style="background-color: rgba(25, 135, 84, 0.1)">
                                    <i class="bi bi-shield-check fs-3 text-success"></i>
                            </div>
                            <div class="metric-value text-success"><?php echo $seguridadLaboral['dias_sin_accidentes']; ?></div>
                            <div class="metric-label">Días sin accidentes</div>
                        </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric-card text-center">
                                <div class="metric-icon mx-auto" style="background-color: rgba(220, 53, 69, 0.1)">
                                    <i class="bi bi-exclamation-triangle fs-3 text-danger"></i>
                            </div>
                            <div class="metric-value text-danger"><?php echo $seguridadLaboral['incidentes_mes']; ?></div>
                            <div class="metric-label">Incidentes este mes</div>
                        </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric-card text-center">
                                <div class="metric-icon mx-auto" style="background-color: rgba(13, 110, 253, 0.1)">
                                    <i class="bi bi-bookmark-star fs-3 text-primary"></i>
                            </div>
                            <div class="metric-value text-primary"><?php echo $seguridadLaboral['capacitaciones_realizadas']; ?></div>
                            <div class="metric-label">Capacitaciones</div>
                        </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric-card text-center">
                                <div class="metric-icon mx-auto" style="background-color: rgba(255, 193, 7, 0.1)">
                                    <i class="bi bi-people fs-3 text-warning"></i>
                            </div>
                            <div class="metric-value text-warning"><?php echo $seguridadLaboral['empleados_capacitados']; ?></div>
                            <div class="metric-label">Empleados Capacitados</div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer mt-auto py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <span class="text-muted">© 2024-<?php echo date('Y'); ?> Mirlo Construcciones S.A.S.</span>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span class="text-muted">Versión 1.0.0</span>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts para los gráficos -->
<script>
// Gráfico de Asistencia
const ctxAsistencia = document.getElementById('asistenciaChart').getContext('2d');
new Chart(ctxAsistencia, {
    type: 'doughnut',
    data: {
        labels: ['Presentes', 'Ausentes', 'Tardanzas'],
        datasets: [{
            data: [<?php echo $asistencia['presentes']; ?>, 
                   <?php echo $asistencia['ausentes']; ?>, 
                   <?php echo $asistencia['tardanzas']; ?>],
            backgroundColor: [
                'rgba(25, 135, 84, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Gráfico de Proyectos
const ctxProyectos = document.getElementById('proyectosChart').getContext('2d');
new Chart(ctxProyectos, {
    type: 'bar',
    data: {
        labels: ['Activos', 'En Proceso', 'Completados'],
        datasets: [{
            label: 'Número de Proyectos',
            data: [<?php echo $proyectos['activos']; ?>, 
                   <?php echo $proyectos['en_proceso']; ?>, 
                   <?php echo $proyectos['completados']; ?>],
            backgroundColor: [
                'rgba(var(--color-primary), 0.8)',
                'rgba(var(--color-secondary), 0.8)',
                'rgba(25, 135, 84, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de Horas Trabajadas por Empleado
const ctxHoras = document.getElementById('horasChart').getContext('2d');
new Chart(ctxHoras, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($horasTrabajadas, 'empleado')); ?>,
        datasets: [{
            label: 'Horas Regulares',
            data: <?php echo json_encode(array_column($horasTrabajadas, 'horas')); ?>,
            backgroundColor: 'rgba(13, 110, 253, 0.8)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 1
        },
        {
            label: 'Horas Extra',
            data: <?php echo json_encode(array_column($horasTrabajadas, 'horas_extra')); ?>,
            backgroundColor: 'rgba(255, 193, 7, 0.8)',
            borderColor: 'rgba(255, 193, 7, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Horas'
                }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Horas Trabajadas por Empleado'
            }
        }
    }
});

// Gráfico de Rendimiento de Proyectos
const ctxRendimiento = document.getElementById('rendimientoChart').getContext('2d');
new Chart(ctxRendimiento, {
    type: 'radar',
    data: {
        labels: ['Avance', 'Presupuesto Ejecutado', 'Personal Asignado'],
        datasets: [{
            label: 'Torre Mirlo',
            data: [
                <?php echo $rendimientoProyectos[0]['avance']; ?>,
                <?php echo ($rendimientoProyectos[0]['gasto_actual']/$rendimientoProyectos[0]['presupuesto_inicial'])*100; ?>,
                <?php echo ($rendimientoProyectos[0]['empleados_asignados']/150)*100; ?>
            ],
            backgroundColor: 'rgba(13, 110, 253, 0.2)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 2
        },
        {
            label: 'Charleston',
            data: [
                <?php echo $rendimientoProyectos[1]['avance']; ?>,
                <?php echo ($rendimientoProyectos[1]['gasto_actual']/$rendimientoProyectos[1]['presupuesto_inicial'])*100; ?>,
                <?php echo ($rendimientoProyectos[1]['empleados_asignados']/150)*100; ?>
            ],
            backgroundColor: 'rgba(25, 135, 84, 0.2)',
            borderColor: 'rgba(25, 135, 84, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            r: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    stepSize: 20
                }
            }
        }
    }
});
</script>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>