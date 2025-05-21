<?php
// importar_usuarios.php
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

// Datos de muestra para usuarios
$usuarios = [
    [
        'nombre' => 'Juan Carlos Pérez',
        'documento' => '1234567890',
        'cargo' => 'Ingeniero Civil',
        'area' => 'Administrativa',
        'estado' => 'Activo'
    ],
    [
        'nombre' => 'María Rodríguez',
        'documento' => '0987654321',
        'cargo' => 'Arquitecta',
        'area' => 'Torre Mirlo',
        'estado' => 'Activo'
    ],
    [
        'nombre' => 'Pedro González',
        'documento' => '5678901234',
        'cargo' => 'Maestro de Obra',
        'area' => 'Charleston',
        'estado' => 'Inactivo'
    ],
    [
        'nombre' => 'Ana López',
        'documento' => '4321098765',
        'cargo' => 'Supervisora',
        'area' => 'Torre Mirlo',
        'estado' => 'Activo'
    ]
];

// Datos de muestra para registros de asistencia
$registros_asistencia = [
    [
        'fecha' => '2025-05-20',
        'empleado' => 'Juan Carlos Pérez',
        'documento' => '1234567890',
        'entrada' => '07:30:00',
        'salida' => '17:30:00',
        'total_horas' => '10:00',
        'estado' => 'Completo'
    ],
    [
        'fecha' => '2025-05-20',
        'empleado' => 'María Rodríguez',
        'documento' => '0987654321',
        'entrada' => '07:45:00',
        'salida' => '17:00:00',
        'total_horas' => '9:15',
        'estado' => 'Completo'
    ],
    [
        'fecha' => '2025-05-20',
        'empleado' => 'Ana López',
        'documento' => '4321098765',
        'entrada' => '08:15:00',
        'salida' => null,
        'total_horas' => '-',
        'estado' => 'En curso'
    ]
];

// Estadísticas de asistencia
$estadisticas = [
    'total_usuarios' => count($usuarios),
    'presentes_hoy' => 3,
    'ausentes_hoy' => 1
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Usuarios | Mirlo Construcciones S.A.S.</title>
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
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            background-color: var(--color-white);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: var(--color-primary);
            color: var(--color-white);
            font-weight: 600;
            border-top-left-radius: var(--border-radius) !important;
            border-top-right-radius: var(--border-radius) !important;
        }
        
        .section-title {
            font-size: 1.8rem;
            color: var(--color-primary);
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
            font-weight: 700;
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
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table th {
            background-color: #f0f0f0;
            font-weight: 600;
            color: var(--color-primary);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(255, 102, 0, 0.05);
        }
        
        /* ...existing styles... */
        
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
        
        @media (max-width: 992px) {
            .container {
                max-width: 100%;
                padding: 0 10px;
            }
            
            .table {
                font-size: 0.85rem;
            }
        }
        
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding-top: 0;
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
                    <a class="nav-link" href="metricas.php">Métricas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="importar_usuarios.php">Asistencia</a>
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
        <h2 class="section-title mb-0"><i class="bi bi-people-fill me-2"></i> Gestión de Usuarios</h2>
        <div>
            <button class="btn btn-custom me-2" onclick="window.print()">
                <i class="bi bi-printer"></i> Imprimir
            </button>
            <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
                <i class="bi bi-person-plus"></i> Nuevo Usuario
            </button>
        </div>
    </div>
    
    <!-- Importación de Excel -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">MIRLO CONSTRUCCIONES S.A.S</h5>
            <div class="text-white">GESTIÓN DE USUARIOS Y ASISTENCIA</div>
        </div>
        <div class="card-body">
            <!-- Sección de importación -->
            <div class="ref-values mb-4">
                <h6 class="fw-bold mb-2">Importar usuarios desde Excel</h6>
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Selecciona el archivo Excel</label>
                        <input type="file" class="form-control" accept=".xlsx,.xls">
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-custom">
                            <i class="bi bi-file-earmark-excel"></i> Importar Usuarios
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Lista de Usuarios -->
            <div class="mb-4">
                <div class="bg-light p-2 text-center fw-bold">USUARIOS REGISTRADOS</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="sticky-header">
                            <tr>
                                <th>NOMBRE COMPLETO</th>
                                <th>DOCUMENTO</th>
                                <th>CARGO</th>
                                <th>ÁREA</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['documento']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['cargo']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['area']); ?></td>
                                <td>
                                    <span class="badge <?php echo $usuario['estado'] === 'Activo' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo htmlspecialchars($usuario['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Registro de Asistencia -->
            <div class="mb-4">
                <div class="bg-light p-2 text-center fw-bold">REGISTRO DE ASISTENCIA</div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar por documento...">
                            <button class="btn btn-custom" type="button">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-success me-2">
                            <i class="bi bi-clock"></i> Marcar Entrada
                        </button>
                        <button class="btn btn-danger">
                            <i class="bi bi-clock-history"></i> Marcar Salida
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="sticky-header">
                            <tr>
                                <th>FECHA</th>
                                <th>EMPLEADO</th>
                                <th>DOCUMENTO</th>
                                <th>ENTRADA</th>
                                <th>SALIDA</th>
                                <th>TOTAL HORAS</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registros_asistencia as $registro): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($registro['fecha'])); ?></td>
                                <td><?php echo htmlspecialchars($registro['empleado']); ?></td>
                                <td><?php echo htmlspecialchars($registro['documento']); ?></td>
                                <td><?php echo $registro['entrada']; ?></td>
                                <td><?php echo $registro['salida'] ?? '-'; ?></td>
                                <td><?php echo $registro['total_horas']; ?></td>
                                <td>
                                    <span class="badge <?php echo $registro['estado'] === 'Completo' ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo htmlspecialchars($registro['estado']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Totales y Resumen -->
            <div class="mt-4">
                <div class="alert alert-success">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Resumen de Asistencia</h5>
                            <p><strong>Total Usuarios:</strong> <span id="totalUsuarios"><?php echo $estadisticas['total_usuarios']; ?></span></p>
                            <p><strong>Presentes Hoy:</strong> <span id="presentesHoy"><?php echo $estadisticas['presentes_hoy']; ?></span></p>
                            <p><strong>Ausentes Hoy:</strong> <span id="ausentesHoy"><?php echo $estadisticas['ausentes_hoy']; ?></span></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="d-flex flex-column h-100 justify-content-end">
                                <p class="mb-1">Generado el: <?php echo date('d/m/Y H:i'); ?></p>
                                <p>Usuario: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botones de acción -->
            <div class="d-flex justify-content-end mt-4 no-print">
                <button class="btn btn-outline-secondary me-2">
                    <i class="bi bi-gear"></i> Configuración
                </button>
                <button class="btn btn-success me-2">
                    <i class="bi bi-file-excel"></i> Exportar a Excel
                </button>
                <button class="btn btn-danger me-2">
                    <i class="bi bi-file-pdf"></i> Generar PDF
                </button>
                <button class="btn btn-custom" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
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

<script>
function usuarioImportacion() {
    return {
        usuarios: [],
        usuarios_preview: [],
        modal_abierto: false,
        modal_editando: false,
        edit_index: null,
        form: {
            nombre: '', apellido: '', edad: '', cargo: '', email: ''
        },
        registro: {
            proyecto: '',
            buscar: ''
        },
        leerExcel(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                const data = new Uint8Array(event.target.result);
                const workbook = XLSX.read(data, {type: 'array'});
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const rows = XLSX.utils.sheet_to_json(sheet, {header: 1});
                this.usuarios_preview = rows.slice(1).map(row => ({
                    nombre: row[0] || '',
                    apellido: row[1] || '',
                    edad: row[2] || '',
                    cargo: row[3] || '',
                    email: row[4] || '',
                }));
            };
            reader.readAsArrayBuffer(file);
        },
        confirmarImportacion() {
            this.usuarios.push(...this.usuarios_preview);
            this.usuarios_preview = [];
        },
        abrirModal(index = null) {
            this.modal_abierto = true;
            if (index !== null) {
                this.modal_editando = true;
                this.edit_index = index;
                this.form = {...this.usuarios[index]};
            } else {
                this.modal_editando = false;
                this.edit_index = null;
                this.form = {nombre: '', apellido: '', edad: '', cargo: '', email: ''};
            }
        },
        cerrarModal() {
            this.modal_abierto = false;
        },
        guardarUsuario() {
            if (this.modal_editando) {
                this.usuarios[this.edit_index] = {...this.form};
            } else {
                this.usuarios.push({...this.form});
            }
            this.cerrarModal();
        },
        eliminarUsuario(index) {
            this.usuarios.splice(index, 1);
        },
        marcarEntrada() {
            if (!this.registro.proyecto || !this.registro.buscar) return;
            alert(`Entrada marcada para ${this.registro.buscar} a las ${new Date().toLocaleTimeString()}`);
        },
        marcarSalida() {
            if (!this.registro.proyecto || !this.registro.buscar) return;
            alert(`Salida marcada para ${this.registro.buscar} a las ${new Date().toLocaleTimeString()}`);
        }
    };
}
</script>

<!-- Scripts (sólo Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
