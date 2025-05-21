<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
// Configuración de colores corporativos
$colorPrimario = '#000000'; // Negro
$colorSecundario = '#FF6600'; // Naranjo
$colorFile = __DIR__ . '/assets/color-primario.txt';
if (file_exists($colorFile)) {
    $colorPrimario = trim(file_get_contents($colorFile));
}
$colorFile = __DIR__ . '/assets/color-secundario.txt';
if (file_exists($colorFile)) {
    $colorSecundario = trim(file_get_contents($colorFile));
}

// Valores de referencia
$salarioMinimo = 1423500;
$auxilioTransporte = 200000;

// Datos de ejemplo para nómina en una constructora
// Datos adaptados al formato del PDF
$empleadosAdministrativa = [
    [
        "id" => 1, 
        "nombre" => "MATEO BUITRAGO CAMPUZANO", 
        "contrato" => "SOCIO", 
        "cedula" => "1.063.862.835",
        "salarioReal" => 3000000,
        "salarioBasico" => 1423500,
        "incapacidades" => 0,
        "valorIncapacidad" => 0,
        "diasLiquidados" => 15,
        "salarioDevengado" => 711750,
        "auxilioNoSalarial" => 88200,
        "primaLiquidaciones" => 0,
        "auxilioTransporte" => 100000,
        "horaExtra" => 0,
        "dom" => 0,
        "totalDevengado" => 1500000,
        "salud" => 28470,
        "pension" => 28470,
        "otrasDeduccciones" => 0,
        "total" => 1443060
    ],
    [
        "id" => 2, 
        "nombre" => "SANTIAGO QUINTERO JIMENEZ", 
        "contrato" => "SOCIO", 
        "cedula" => "1.053.865.035",
        "salarioReal" => 2000000,
        "salarioBasico" => 1423500,
        "incapacidades" => 0,
        "valorIncapacidad" => 0,
        "diasLiquidados" => 15,
        "salarioDevengado" => 711750,
        "auxilioNoSalarial" => 88200,
        "primaLiquidaciones" => 0,
        "auxilioTransporte" => 100000,
        "horaExtra" => 0,
        "dom" => 0,
        "totalDevengado" => 1000000,
        "salud" => 28470,
        "pension" => 28470,
        "otrasDeduccciones" => 0,
        "total" => 943060
    ],
    // Más empleados...
];

$empleadosTorreMirlo = [
    [
        "id" => 10, 
        "nombre" => "JUAN DE DIOS GONZÁLEZ GONZÁLEZ", 
        "contrato" => "OBRA O LABOR", 
        "cedula" => "5.862.896",
        "salarioReal" => 1423500,
        "salarioBasico" => 1423500,
        "incapacidades" => 0,
        "valorIncapacidad" => 0,
        "diasLiquidados" => 15,
        "salarioDevengado" => 711750,
        "auxilioNoSalarial" => 200000,
        "primaLiquidaciones" => 0,
        "auxilioTransporte" => 66667,
        "horaExtra" => 0,
        "dom" => 0,
        "totalDevengado" => 978417,
        "salud" => 28470,
        "pension" => 28470,
        "otrasDeduccciones" => 0,
        "total" => 921477
    ],
    // Más empleados...
];

$empleadosCharleston = [
    [
        "id" => 20, 
        "nombre" => "DIEGO QUINTERO", 
        "contrato" => "CONTRATISTA", 
        "cedula" => "",
        "salarioReal" => 2000000,
        "salarioBasico" => 2000000,
        "incapacidades" => 0,
        "valorIncapacidad" => 0,
        "diasLiquidados" => 15,
        "salarioDevengado" => 1000000,
        "auxilioNoSalarial" => 0,
        "primaLiquidaciones" => 0,
        "auxilioTransporte" => 0,
        "horaExtra" => 0,
        "dom" => 0,
        "totalDevengado" => 1000000,
        "salud" => 0,
        "pension" => 0,
        "otrasDeduccciones" => 0,
        "total" => 1000000
    ],
    // Más empleados...
];

// Función para formatear números
function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nómina | Mirlo Construcciones S.A.S.</title>
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
        
        .ref-values {
            background-color: #e7f5e7;
            border-radius: var(--border-radius);
            padding: 10px 15px;
            margin-bottom: 20px;
        }
        
        .ref-values p {
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }
        
        .ref-values strong {
            color: var(--color-primary);
        }
        
        .total-row {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .pendiente {
            color: #dc3545;
            font-weight: 600;
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
        
        /* Sticky header para tablas largas */
        .sticky-header th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f0f0f0;
        }
        
        /* Responsive */
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
                    <a class="nav-link active" href="nomina.php">Nómina</a>
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
        <h2 class="section-title mb-0"><i class="bi bi-people-fill me-2"></i> Nómina</h2>
        <div>
            <button class="btn btn-custom me-2" onclick="window.print()">
                <i class="bi bi-printer"></i> Imprimir
            </button>
            <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#nuevoEmpleadoModal">
                <i class="bi bi-person-plus"></i> Nuevo Empleado
            </button>
        </div>
    </div>
    
    <!-- Valores de referencia -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">MIRLO CONSTRUCCIONES S.A.S</h5>
            <div class="text-white">NÓMINA MAYO 15 DE 2025</div>
        </div>
        <div class="card-body">
            <div class="ref-values">
                <h6 class="fw-bold mb-2">Diligenciar los siguientes valores de referencia:</h6>
                <p><span>Salario mínimo</span> <strong>$ <?php echo formatNumber($salarioMinimo); ?></strong></p>
                <p><span>Auxilio de transporte</span> <strong>$ <?php echo formatNumber($auxilioTransporte); ?></strong></p>
                <p class="border-top pt-2 fw-bold"><span>Total</span> <strong>$ <?php echo formatNumber($salarioMinimo + $auxilioTransporte); ?></strong></p>
            </div>
            
            <!-- Administrativa -->
            <div class="mb-4">
                <div class="bg-light p-2 text-center fw-bold">ADMINISTRATIVA</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="sticky-header">
                            <tr>
                                <th>NOMBRE DEL EMPLEADO</th>
                                <th>CONTRATO</th>
                                <th>CÉDULA</th>
                                <th>Salario Real</th>
                                <th>Salario básico</th>
                                <th>Incapacidades</th>
                                <th>Valor incapacidad</th>
                                <th>Días liquidados</th>
                                <th colspan="5" class="text-center">DEVENGOS</th>
                                <th colspan="3" class="text-center">DEDUCCIONES</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th colspan="8"></th>
                                <th>Salario devengado</th>
                                <th>Auxilio No salarial</th>
                                <th>Prima o Liquidaciones</th>
                                <th>Auxilio de transporte</th>
                                <th>Hora Extra</th>
                                <th>Dom</th>
                                <th>Total devengado</th>
                                <th>Salud</th>
                                <th>Pensión</th>
                                <th>Otras deducciones</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalAdministrativa = 0;
                            foreach ($empleadosAdministrativa as $emp): 
                                $totalAdministrativa += $emp['total'];
                            ?>
                            <tr>
                                <td><?php echo $emp['nombre']; ?></td>
                                <td><?php echo $emp['contrato']; ?></td>
                                <td><?php echo $emp['cedula']; ?></td>
                                <td>$ <?php echo formatNumber($emp['salarioReal']); ?></td>
                                <td>$ <?php echo formatNumber($emp['salarioBasico']); ?></td>
                                <td><?php echo $emp['incapacidades'] ? $emp['incapacidades'] : ''; ?></td>
                                <td><?php echo $emp['valorIncapacidad'] ? '$ ' . formatNumber($emp['valorIncapacidad']) : ''; ?></td>
                                <td><?php echo $emp['diasLiquidados']; ?></td>
                                <td>$ <?php echo formatNumber($emp['salarioDevengado']); ?></td>
                                <td><?php echo $emp['auxilioNoSalarial'] ? '$ ' . formatNumber($emp['auxilioNoSalarial']) : ''; ?></td>
                                <td><?php echo $emp['primaLiquidaciones'] ? '$ ' . formatNumber($emp['primaLiquidaciones']) : ''; ?></td>
                                <td><?php echo $emp['auxilioTransporte'] ? '$ ' . formatNumber($emp['auxilioTransporte']) : ''; ?></td>
                                <td><?php echo $emp['horaExtra'] ? '$ ' . formatNumber($emp['horaExtra']) : ''; ?></td>
                                <td><?php echo $emp['dom'] ? '$ ' . formatNumber($emp['dom']) : ''; ?></td>
                                <td>$ <?php echo formatNumber($emp['totalDevengado']); ?></td>
                                <td>$ <?php echo formatNumber($emp['salud']); ?></td>
                                <td>$ <?php echo formatNumber($emp['pension']); ?></td>
                                <td><?php echo $emp['otrasDeduccciones'] ? '$ ' . formatNumber($emp['otrasDeduccciones']) : ''; ?></td>
                                <td>$ <?php echo formatNumber($emp['total']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="total-row">
                                <td colspan="14" class="text-end">TOTAL ADMINISTRATIVA</td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosAdministrativa, 'totalDevengado'))); ?></td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosAdministrativa, 'salud'))); ?></td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosAdministrativa, 'pension'))); ?></td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosAdministrativa, 'otrasDeduccciones'))); ?></td>
                                <td>$ <?php echo formatNumber($totalAdministrativa); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Torre Mirlo -->
            <div class="mb-4">
                <div class="bg-light p-2 text-center fw-bold">TORRE MIRLO</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="sticky-header">
                            <tr>
                                <th>NOMBRE DEL EMPLEADO</th>
                                <th>CONTRATO</th>
                                <th>CÉDULA</th>
                                <th>Salario Real</th>
                                <th>Salario básico</th>
                                <th>Incapacidades</th>
                                <th>Valor incapacidad</th>
                                <th>Días liquidados</th>
                                <th colspan="5" class="text-center">DEVENGOS</th>
                                <th colspan="3" class="text-center">DEDUCCIONES</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th colspan="8"></th>
                                <th>Salario devengado</th>
                                <th>Auxilio No salarial</th>
                                <th>Prima o Liquidaciones</th>
                                <th>Auxilio de transporte</th>
                                <th>Hora Extra</th>
                                <th>Dom</th>
                                <th>Total devengado</th>
                                <th>Salud</th>
                                <th>Pensión</th>
                                <th>Otras deducciones</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalTorreMirlo = 0;
                            foreach ($empleadosTorreMirlo as $emp): 
                                $totalTorreMirlo += $emp['total'];
                            ?>
                            <tr>
                                <td><?php echo $emp['nombre']; ?></td>
                                <td><?php echo $emp['contrato']; ?></td>
                                <td><?php echo $emp['cedula']; ?></td>
                                <td>$ <?php echo formatNumber($emp['salarioReal']); ?></td>
                                <td>$ <?php echo formatNumber($emp['salarioBasico']); ?></td>
                                <td><?php echo $emp['incapacidades'] ? $emp['incapacidades'] : ''; ?></td>
                                <td><?php echo $emp['valorIncapacidad'] ? '$ ' . formatNumber($emp['valorIncapacidad']) : ''; ?></td>
                                <td><?php echo $emp['diasLiquidados']; ?></td>
                                <td>$ <?php echo formatNumber($emp['salarioDevengado']); ?></td>
                                <td><?php echo $emp['auxilioNoSalarial'] ? '$ ' . formatNumber($emp['auxilioNoSalarial']) : ''; ?></td>
                                <td><?php echo $emp['primaLiquidaciones'] ? '$ ' . formatNumber($emp['primaLiquidaciones']) : ''; ?></td>
                                <td><?php echo $emp['auxilioTransporte'] ? '$ ' . formatNumber($emp['auxilioTransporte']) : ''; ?></td>
                                <td><?php echo $emp['horaExtra'] ? '$ ' . formatNumber($emp['horaExtra']) : ''; ?></td>
                                <td><?php echo $emp['dom'] ? '$ ' . formatNumber($emp['dom']) : ''; ?></td>
                                <td>$ <?php echo formatNumber($emp['totalDevengado']); ?></td>
                                <td>$ <?php echo formatNumber($emp['salud']); ?></td>
                                <td>$ <?php echo formatNumber($emp['pension']); ?></td>
                                <td><?php echo $emp['otrasDeduccciones'] ? '$ ' . formatNumber($emp['otrasDeduccciones']) : ''; ?></td>
                                <td>$ <?php echo formatNumber($emp['total']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="total-row">
                                <td colspan="14" class="text-end">TOTAL TORRE MIRLO</td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosTorreMirlo, 'totalDevengado'))); ?></td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosTorreMirlo, 'salud'))); ?></td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosTorreMirlo, 'pension'))); ?></td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosTorreMirlo, 'otrasDeduccciones'))); ?></td>
                                <td>$ <?php echo formatNumber($totalTorreMirlo); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Charleston -->
            <div class="mb-4">
                <div class="bg-light p-2 text-center fw-bold">CHARLESTON</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="sticky-header">
                            <tr>
                                <th>NOMBRE DEL EMPLEADO</th>
                                <th>CONTRATO</th>
                                <th>CÉDULA</th>
                                <th>Salario Real</th>
                                <th>Salario básico</th>
                                <th>Incapacidades</th>
                                <th>Valor incapacidad</th>
                                <th>Días liquidados</th>
                                <th colspan="5" class="text-center">DEVENGOS</th>
                                <th colspan="3" class="text-center">DEDUCCIONES</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th colspan="8"></th>
                                <th>Salario devengado</th>
                                <th>Auxilio No salarial</th>
                                <th>Prima o Liquidaciones</th>
                                <th>Auxilio de transporte</th>
                                <th>Hora Extra</th>
                                <th>Dom</th>
                                <th>Total devengado</th>
                                <th>Salud</th>
                                <th>Pensión</th>
                                <th>Otras deducciones</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalCharleston = 0;
                            foreach ($empleadosCharleston as $emp): 
                                $totalCharleston += $emp['total'];
                            ?>
                            <tr>
                                <td><?php echo $emp['nombre']; ?></td>
                                <td><?php echo $emp['contrato']; ?></td>
                                <td><?php echo $emp['cedula']; ?></td>
                                <td>$ <?php echo formatNumber($emp['salarioReal']); ?></td>
                                <td>$ <?php echo formatNumber($emp['salarioBasico']); ?></td>
                                <td><?php echo $emp['incapacidades'] ? $emp['incapacidades'] : ''; ?></td>
                                <td><?php echo $emp['valorIncapacidad'] ? '$ ' . formatNumber($emp['valorIncapacidad']) : ''; ?></td>
                                <td><?php echo $emp['diasLiquidados']; ?></td>
                                <td>$ <?php echo formatNumber($emp['salarioDevengado']); ?></td>
                                <td><?php echo $emp['auxilioNoSalarial'] ? '$ ' . formatNumber($emp['auxilioNoSalarial']) : ''; ?></td>
                                <td><?php echo $emp['primaLiquidaciones'] ? '$ ' . formatNumber($emp['primaLiquidaciones']) : ''; ?></td>
                                <td><?php echo $emp['auxilioTransporte'] ? '$ ' . formatNumber($emp['auxilioTransporte']) : ''; ?></td>
                                <td><?php echo $emp['horaExtra'] ? '$ ' . formatNumber($emp['horaExtra']) : ''; ?></td>
                                <td><?php echo $emp['dom'] ? '$ ' . formatNumber($emp['dom']) : ''; ?></td>
                                <td>$ <?php echo formatNumber($emp['totalDevengado']); ?></td>
                                <td>$ <?php echo formatNumber($emp['salud']); ?></td>
                                <td>$ <?php echo formatNumber($emp['pension']); ?></td>
                                <td><?php echo $emp['otrasDeduccciones'] ? '$ ' . formatNumber($emp['otrasDeduccciones']) : ''; ?></td>
                                <td>$ <?php echo formatNumber($emp['total']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="total-row">
                                <td colspan="14" class="text-end">TOTAL CHARLESTON</td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosCharleston, 'totalDevengado'))); ?></td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosCharleston, 'salud'))); ?></td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosCharleston, 'pension'))); ?></td>
                                <td>$ <?php echo formatNumber(array_sum(array_column($empleadosCharleston, 'otrasDeduccciones'))); ?></td>
                                <td>$ <?php echo formatNumber($totalCharleston); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Totales -->
            <div class="mt-4">
                <div class="alert alert-success">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Resumen General</h5>
                            <p><strong>Total Administrativa:</strong> $ <?php echo formatNumber($totalAdministrativa); ?></p>
                            <p><strong>Total Torre Mirlo:</strong> $ <?php echo formatNumber($totalTorreMirlo); ?></p>
                            <p><strong>Total Charleston:</strong> $ <?php echo formatNumber($totalCharleston); ?></p>
                            <p class="border-top pt-2 h5"><strong>TOTAL NÓMINA:</strong> $ <?php echo formatNumber($totalAdministrativa + $totalTorreMirlo + $totalCharleston); ?></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="d-flex flex-column h-100 justify-content-end">
                                <p class="mb-1">Generado el: <?php echo date('d/m/Y H:i'); ?></p>
                                <p class="mb-1">Período: 1 al 15 de Mayo de 2025</p>
                                <p>Usuario: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botones de acción -->
            <div class="d-flex justify-content-end mt-4 no-print">
                <button class="btn btn-outline-secondary me-2">
                    <i class="bi bi-pencil"></i> Editar
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

<!-- Modal para Personalizar -->
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-custom">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts (sólo Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 