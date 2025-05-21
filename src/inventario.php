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

/**
 * Sistema de Control de Inventario para Proyectos de Construcción
 * 
 * Este sistema permite gestionar el inventario de materiales de construcción,
 * proveedores y órdenes de compra. Incluye funcionalidades para:
 * - Monitoreo de niveles de inventario
 * - Gestión de proveedores
 * - Seguimiento de órdenes de compra
 * - Alertas de stock bajo
 * - Importación de datos
 * 
 * @author Sistema de Gestión
 * @version 1.0
 */

// =============================================
// DATOS DE EJEMPLO - SIMULACIÓN DE BASE DE DATOS
// =============================================

/**
 * Array de materiales en inventario
 * Cada material contiene:
 * - id: Identificador único
 * - name: Nombre del material
 * - category: Categoría del material
 * - current_qty: Cantidad actual en inventario
 * - min_qty: Cantidad mínima requerida
 * - supplier: Proveedor del material
 * - price: Precio unitario
 * - status: Estado del inventario (Críticos, Bajo, OK)
 * - last_order: Fecha del último pedido
 */
$materials = [
    [
        'id' => 1,
        'name' => 'Cemento (bolsas de 50kg)',
        'category' => 'Materiales de Construcción',
        'current_qty' => 15,
        'min_qty' => 20,
        'supplier' => 'BuildSupply Co.',
        'price' => 12.50,
        'status' => 'Críticos',
        'last_order' => '2025-05-01'
    ],
    [
        'id' => 2,
        'name' => 'Varillas de Acero (12mm)',
        'category' => 'Estructural',
        'current_qty' => 120,
        'min_qty' => 50,
        'supplier' => 'MetalWorks Inc.',
        'price' => 8.75,
        'status' => 'OK',
        'last_order' => '2025-04-28'
    ],
    [
        'id' => 3,
        'name' => 'Ladrillos (Estándar)',
        'category' => 'Materiales de Construcción',
        'current_qty' => 850,
        'min_qty' => 1000,
        'supplier' => 'BuildSupply Co.',
        'price' => 0.65,
        'status' => 'Bajo',
        'last_order' => '2025-05-05'
    ],
    [
        'id' => 4,
        'name' => 'Tuberías PVC (4")',
        'category' => 'Plomería',
        'current_qty' => 35,
        'min_qty' => 30,
        'supplier' => 'PlumbFit Solutions',
        'price' => 6.25,
        'status' => 'OK',
        'last_order' => '2025-04-24'
    ],
    [
        'id' => 5,
        'name' => 'Cable Eléctrico (100m)',
        'category' => 'Eléctrico',
        'current_qty' => 8,
        'min_qty' => 10,
        'supplier' => 'ElectroPro Inc.',
        'price' => 45.99,
        'status' => 'Bajo',
        'last_order' => '2025-05-09'
    ],
    [
        'id' => 6,
        'name' => 'Pintura (20L Blanca)',
        'category' => 'Acabados',
        'current_qty' => 2,
        'min_qty' => 5,
        'supplier' => 'ColorTech Ltd.',
        'price' => 65.00,
        'status' => 'Críticos',
        'last_order' => '2025-04-15'
    ],
    [
        'id' => 7,
        'name' => 'Azulejos Cerámicos (30x30cm)',
        'category' => 'Acabados',
        'current_qty' => 200,
        'min_qty' => 100,
        'supplier' => 'TileMaster Co.',
        'price' => 2.25,
        'status' => 'OK',
        'last_order' => '2025-05-02'
    ],
    [
        'id' => 8,
        'name' => 'Tablas de Madera (2m)',
        'category' => 'Carpintería',
        'current_qty' => 45,
        'min_qty' => 40,
        'supplier' => 'WoodCraft Ltd.',
        'price' => 18.75,
        'status' => 'OK',
        'last_order' => '2025-04-22'
    ]
];

/**
 * Array de proveedores
 * Cada proveedor contiene:
 * - id: Identificador único
 * - name: Nombre de la empresa
 * - contact: Persona de contacto
 * - email: Correo electrónico
 * - phone: Número telefónico
 * - products: Tipos de productos que suministra
 */
$suppliers = [
    [
        'id' => 1,
        'name' => 'BuildSupply Co.',
        'contact' => 'Juan Pérez',
        'email' => 'juan@buildsupply.example',
        'phone' => '+1234567890',
        'products' => 'Materiales de Construcción, Herrajes',
        'rating' => 4.5
    ],
    [
        'id' => 2,
        'name' => 'MetalWorks Inc.',
        'contact' => 'María Rodríguez',
        'email' => 'maria@metalworks.example',
        'phone' => '+0987654321',
        'products' => 'Materiales Estructurales, Acero',
        'rating' => 4.8
    ],
    [
        'id' => 3,
        'name' => 'PlumbFit Solutions',
        'contact' => 'Miguel Díaz',
        'email' => 'miguel@plumbfit.example',
        'phone' => '+1122334455',
        'products' => 'Suministros de Plomería',
        'rating' => 4.2
    ],
    [
        'id' => 4,
        'name' => 'ElectroPro Inc.',
        'contact' => 'Laura Chen',
        'email' => 'laura@electropro.example',
        'phone' => '+5566778899',
        'products' => 'Suministros Eléctricos',
        'rating' => 4.7
    ],
    [
        'id' => 5,
        'name' => 'ColorTech Ltd.',
        'contact' => 'David Wilson',
        'email' => 'david@colortech.example',
        'phone' => '+2233445566',
        'products' => 'Pinturas, Recubrimientos',
        'rating' => 3.9
    ],
    [
        'id' => 6,
        'name' => 'TileMaster Co.',
        'contact' => 'Emma Brown',
        'email' => 'emma@tilemaster.example',
        'phone' => '+7788990011',
        'products' => 'Azulejos, Pisos',
        'rating' => 4.6
    ],
    [
        'id' => 7,
        'name' => 'WoodCraft Ltd.',
        'contact' => 'Jaime Anderson',
        'email' => 'jaime@woodcraft.example',
        'phone' => '+4455667788',
        'products' => 'Madera, Suministros de Carpintería',
        'rating' => 4.4
    ]
];

/**
 * Array de órdenes de compra
 * Cada orden contiene:
 * - id: Identificador de la orden
 * - date: Fecha de la orden
 * - supplier: Proveedor
 * - items: Descripción de los items
 * - amount: Monto total
 * - status: Estado de la orden (Pendiente, Entregado, Cancelado)
 */
$purchase_orders = [
    [
        'id' => 'OC-2025-001',
        'date' => '2025-05-10',
        'supplier' => 'BuildSupply Co.',
        'items' => 'Cemento (bolsas de 50kg) x 30',
        'amount' => 375.00,
        'status' => 'Pendiente'
    ],
    [
        'id' => 'OC-2025-002',
        'date' => '2025-05-09',
        'supplier' => 'ElectroPro Inc.',
        'items' => 'Cable Eléctrico (100m) x 5',
        'amount' => 229.95,
        'status' => 'Entregado'
    ],
    [
        'id' => 'OC-2025-003',
        'date' => '2025-05-05',
        'supplier' => 'BuildSupply Co.',
        'items' => 'Ladrillos (Estándar) x 1000',
        'amount' => 650.00,
        'status' => 'Entregado'
    ],
    [
        'id' => 'OC-2025-004',
        'date' => '2025-05-02',
        'supplier' => 'TileMaster Co.',
        'items' => 'Azulejos Cerámicos (30x30cm) x 300',
        'amount' => 675.00,
        'status' => 'Entregado'
    ],
    [
        'id' => 'OC-2025-005',
        'date' => '2025-04-28',
        'supplier' => 'MetalWorks Inc.',
        'items' => 'Varillas de Acero (12mm) x 150',
        'amount' => 1312.50,
        'status' => 'Entregado'
    ],
    [
        'id' => 'OC-2025-006',
        'date' => '2025-04-24',
        'supplier' => 'PlumbFit Solutions',
        'items' => 'Tuberías PVC (4") x 50',
        'amount' => 312.50,
        'status' => 'Entregado'
    ]
];

// Primero hacemos los cálculos
$total_materials = count($materials);
$critical_items = 0;
$low_items = 0;
$ok_items = 0;
$total_value = 0;

foreach ($materials as $material) {
    $total_value += $material['current_qty'] * $material['price'];
    if ($material['status'] == 'Críticos') {
        $critical_items++;
    } elseif ($material['status'] == 'Bajo') {
        $low_items++;
    } elseif ($material['status'] == 'OK') {
        $ok_items++;
    }
}

// Luego definimos los datos para los gráficos
$dashboard_data = [
    'historial_mensual' => [
        'enero' => ['entradas' => 25, 'salidas' => 18],
        'febrero' => ['entradas' => 30, 'salidas' => 22],
        'marzo' => ['entradas' => 28, 'salidas' => 25],
        'abril' => ['entradas' => 35, 'salidas' => 30],
        'mayo' => ['entradas' => 32, 'salidas' => 28]
    ],
    'proveedores_top' => [
        'BuildSupply Co.' => 12500.00,
        'MetalWorks Inc.' => 9800.50,
        'ElectroPro Inc.' => 7500.25,
        'ColorTech Ltd.' => 6200.00,
        'PlumbFit Solutions' => 5800.75
    ]
];

// Datos para el gráfico de estado
$status_data = [
    'criticos' => $critical_items,
    'bajo_stock' => $low_items,
    'ok' => $ok_items
];

// Datos para el gráfico principal
$inventory_data = [];
foreach ($materials as $material) {
    $inventory_data['labels'][] = $material['name'];
    $inventory_data['items'][] = $material['current_qty'];
    $inventory_data['valores'][] = $material['current_qty'] * $material['price'];
}

/**
 * Obtención de categorías y proveedores únicos para filtros
 * Se utilizan para poblar los selectores de filtrado en la interfaz
 */
$categories = array_unique(array_column($materials, 'category'));
$supplier_names = array_unique(array_column($materials, 'supplier'));

/**
 * Manejo de formularios y mensajes del sistema
 * Procesa las acciones de:
 * - Creación de órdenes de compra
 * - Carga de archivos Excel
 */
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['order_material'])) {
        $message = 'Orden de compra iniciada para ' . $_POST['material_name'] . ' de ' . $_POST['supplier_name'];
    } elseif (isset($_POST['upload_file'])) {
        $message = '¡Archivo Excel cargado exitosamente! Los datos serían procesados aquí.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario | Mirlo Construcciones S.A.S.</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
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
            background-color: var(--color-white);
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: scale(1.02);
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
        
        /* ===== ESTILOS ESPECÍFICOS PARA INVENTARIO ===== */
        
        /* Badges de estado */
        .badge-critical {
            background-color: #dc3545 !important;
            color: white;
        }
        
        .badge-low {
            background-color: #ffc107 !important;
            color: #212529;
        }
        
        .badge-ok {
            background-color: #198754 !important;
            color: white;
        }
        
        /* Botones de filtro */
        .filter-btn {
            margin: 0.2rem;
        }
        
        .filter-btn.active {
            background-color: var(--color-secondary) !important;
            border-color: var(--color-secondary) !important;
            color: var(--color-white) !important;
        }
        
        /* Tabla responsive */
        .table-responsive {
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            border-bottom: 2px solid #dee2e6;
            background-color: var(--color-background) !important;
            font-weight: 600;
            color: var(--color-text);
        }
        
        .table tbody tr:hover {
            background-color: rgba(255, 102, 0, 0.05);
        }
        
        /* Nav pills personalizado */
        .nav-pills .nav-link {
            color: var(--color-text);
            background-color: transparent;
            border-radius: 6px;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--color-secondary) !important;
            color: var(--color-white) !important;
        }
        
        .nav-pills .nav-link:hover {
            background-color: rgba(255, 102, 0, 0.1);
        }
        
        /* Progress bars */
        .progress {
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
        }
        
        /* Chart container */
        .chart-container {
            position: relative;
            height: 300px;
            margin: 0 auto;
        }
        
        /* Input groups */
        .input-group-text {
            background-color: var(--color-background);
            border-color: #ced4da;
        }
        
        /* Form controls */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-secondary);
            box-shadow: 0 0 0 0.2rem rgba(255, 102, 0, 0.25);
        }
        
        /* Alerts */
        .alert-success {
            background-color: rgba(25, 135, 84, 0.1);
            border-color: rgba(25, 135, 84, 0.2);
            color: #0f5132;
        }
        
        /* Modal customization */
        .modal-header {
            background-color: var(--color-primary);
            color: var(--color-white);
            border-bottom: none;
        }
        
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-hover);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
            
            .display-4 {
                font-size: 2rem;
            }
            
            .filter-btn {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .container {
                padding: 1rem 0.5rem;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .chart-container {
                height: 250px;
            }
        }

        /* Estilos para la zona de drop */
        .card-body.border {
            border-style: dashed !important;
            transition: all 0.3s ease-in-out;
        }

        /* Animación para el ícono de carga */
        .fa-upload {
            transition: transform 0.3s ease-in-out;
        }

        .btn:hover .fa-upload {
            transform: translateY(-2px);
        }

        /* Estilos para el área de arrastrar y soltar */
        .text-center.py-3 {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 2rem !important;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .text-center.py-3:hover {
            border-color: var(--color-secondary);
            background-color: rgba(var(--color-secondary-rgb), 0.05);
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
                    <a class="nav-link active" href="inventario.php">Inventario</a>
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

<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="section-title">Control de Inventario</h1>
            
            <?php if (!empty($message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-white h-100">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total de Materiales</h5>
                    <div class="d-flex align-items-center mt-3">
                        <div class="display-4"><?php echo $total_materials; ?></div>
                        <div class="ms-auto">
                            <i class="fas fa-boxes fa-3x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-white h-100">
                <div class="card-body">
                    <h5 class="card-title text-muted">Items Críticos</h5>
                    <div class="d-flex align-items-center mt-3">
                        <div class="display-4"><?php echo $critical_items; ?></div>
                        <div class="ms-auto">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-white h-100">
                <div class="card-body">
                    <h5 class="card-title text-muted">Items con Bajo Stock</h5>
                    <div class="d-flex align-items-center mt-3">
                        <div class="display-4"><?php echo $low_items; ?></div>
                        <div class="ms-auto">
                            <i class="fas fa-battery-quarter fa-3x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-white h-100">
                <div class="card-body">
                    <h5 class="card-title text-muted">Valor del Inventario</h5>
                    <div class="d-flex align-items-center mt-3">
                        <div class="display-4">$<?php echo number_format($total_value, 2); ?></div>
                        <div class="ms-auto">
                            <i class="fas fa-dollar-sign fa-3x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos adicionales -->
    <div class="row mb-4">
        <!-- Primera columna: Gráfico de Inventario -->
        <div class="col-md-6 mb-3">
            <div class="custom-card card bg-white">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Inventario de Materiales</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary active" data-view="qty">
                            Cantidades
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-view="value">
                            Valor
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda columna: Gráfico de Proveedores -->
        <div class="col-md-6 mb-3">
            <div class="custom-card card bg-white">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Proveedores Principales</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="suppliersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila adicional para gráficos de análisis -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card bg-white">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Órdenes de Compra</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Estado del Inventario</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección de Tablas -->
    <div class="row">
        <div class="col-12">
            <!-- Tabla de Inventario -->
            <div class="card bg-white mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Inventario de Materiales</h5>
                    <div class="d-flex align-items-center">
                        <div class="input-group input-group-sm me-2" style="width: 250px;">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control" id="materialSearch" placeholder="Buscar materiales...">
                        </div>
                        <button type="button" class="btn btn-custom btn-sm" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                            <i class="fas fa-plus me-1"></i> Agregar Material
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Filtros:</strong>
                            <button class="btn btn-sm btn-outline-secondary" id="clearFilters">
                                <i class="fas fa-times me-1"></i> Limpiar Filtros
                            </button>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted me-2">Categoría:</span>
                            <button class="btn btn-sm btn-outline-secondary filter-btn active" data-filter="category" data-value="all">Todos</button>
                            <?php foreach ($categories as $category): ?>
                            <button class="btn btn-sm btn-outline-secondary filter-btn" data-filter="category" data-value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Tabla de Inventario -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="materialsTable">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Categoría</th>
                                    <th>Cantidad Actual</th>
                                    <th>Cantidad Mínima</th>
                                    <th>Proveedor</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                    <th>Último Pedido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materials as $material): ?>
                                <tr data-category="<?php echo htmlspecialchars($material['category']); ?>" data-supplier="<?php echo htmlspecialchars($material['supplier']); ?>">
                                    <td><?php echo htmlspecialchars($material['name']); ?></td>
                                    <td><?php echo htmlspecialchars($material['category']); ?></td>
                                    <td><?php echo $material['current_qty']; ?></td>
                                    <td><?php echo $material['min_qty']; ?></td>
                                    <td><?php echo htmlspecialchars($material['supplier']); ?></td>
                                    <td>$<?php echo number_format($material['price'], 2); ?></td>
                                    <td>
                                        <?php if ($material['status'] == 'Críticos'): ?>
                                            <span class="badge badge-critical">Críticos</span>
                                        <?php elseif ($material['status'] == 'Bajo'): ?>
                                            <span class="badge badge-low">Bajo</span>
                                        <?php else: ?>
                                            <span class="badge badge-ok">OK</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $material['last_order']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pestañas de Proveedores y Órdenes -->
            <div class="card bg-white">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="suppliers-tab" data-bs-toggle="tab" data-bs-target="#suppliers" type="button" role="tab">
                                <i class="fas fa-building me-1"></i> Proveedores
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                                <i class="fas fa-shopping-cart me-1"></i> Órdenes de Compra
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="suppliers" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Proveedor</th>
                                            <th>Contacto</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Productos</th>
                                            <th>Calificación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($suppliers as $supplier): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($supplier['name']); ?></td>
                                            <td><?php echo htmlspecialchars($supplier['contact']); ?></td>
                                            <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                                            <td><?php echo htmlspecialchars($supplier['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($supplier['products']); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo number_format($supplier['rating'], 1); ?></span>
                                                    <div class="stars">
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star <?php echo $i <= $supplier['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="orders" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID Orden</th>
                                            <th>Fecha</th>
                                            <th>Proveedor</th>
                                            <th>Items</th>
                                            <th>Monto</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($purchase_orders as $order): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                                            <td><?php echo $order['date']; ?></td>
                                            <td><?php echo htmlspecialchars($order['supplier']); ?></td>
                                            <td><?php echo htmlspecialchars($order['items']); ?></td>
                                            <td>$<?php echo number_format($order['amount'], 2); ?></td>
                                            <td>
                                                <span class="badge <?php 
                                                    echo $order['status'] == 'Entregado' ? 'bg-success' : 
                                                        ($order['status'] == 'Pendiente' ? 'bg-warning' : 'bg-danger'); 
                                                ?>">
                                                    <?php echo $order['status']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Material -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMaterialModalLabel">Agregar Nuevo Material</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="materialName" class="form-label">Nombre del Material</label>
                        <input type="text" class="form-control form-control-sm" id="materialName" required>
                    </div>
                    <div class="mb-3">
                        <label for="materialCategoría" class="form-label">Categoría</label>
                        <select class="form-select form-select-sm" id="materialCategoría">
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="materialCurrentQty" class="form-label">Cantidad Actual</label>
                                <input type="number" class="form-control form-control-sm" id="materialCurrentQty" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="materialMinQty" class="form-label">Cantidad Mínima</label>
                                <input type="number" class="form-control form-control-sm" id="materialMinQty" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="materialProveedor" class="form-label">Proveedor</label>
                        <select class="form-select form-select-sm" id="materialProveedor">
                            <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?php echo htmlspecialchars($supplier['name']); ?>"><?php echo htmlspecialchars($supplier['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="materialPrecio" class="form-label">Precio</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="materialPrecio" step="0.01" min="0">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-custom btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-custom btn-sm">Agregar Material</button>
            </div>
        </div>
    </div>
</div>

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

<!-- Scripts JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

<script>
    /**
     * Inicialización de tooltips de Bootstrap
     * Permite mostrar información adicional al pasar el mouse sobre elementos
     */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    /**
     * Configuración de los gráficos
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfico de Inventario de Materiales (mainChart)
        const mainChartCtx = document.getElementById('mainChart').getContext('2d');
        const mainChart = new Chart(mainChartCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($materials, 'name')); ?>,
                datasets: [{
                    label: 'Cantidad Actual',
                    data: <?php echo json_encode(array_column($materials, 'current_qty')); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Cantidad Mínima',
                    data: <?php echo json_encode(array_column($materials, 'min_qty')); ?>,
                    type: 'line',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de Proveedores (suppliersChart)
        const suppliersChartCtx = document.getElementById('suppliersChart').getContext('2d');
        const suppliersChart = new Chart(suppliersChartCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_keys($dashboard_data['proveedores_top'])); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($dashboard_data['proveedores_top'])); ?>,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    title: {
                        display: true,
                        text: 'Valor de Compras por Proveedor ($)'
                    }
                }
            }
        });

        // Gráfico de Órdenes de Compra (ordersChart)
        const ordersChartCtx = document.getElementById('ordersChart').getContext('2d');
        const ordersChart = new Chart(ordersChartCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_keys($dashboard_data['historial_mensual'])); ?>,
                datasets: [{
                    label: 'Entradas',
                    data: <?php echo json_encode(array_column($dashboard_data['historial_mensual'], 'entradas')); ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Salidas',
                    data: <?php echo json_encode(array_column($dashboard_data['historial_mensual'], 'salidas')); ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de Estado del Inventario (statusChart)
        const statusChartCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusChartCtx, {
            type: 'doughnut',
            data: {
                labels: ['Críticos', 'Bajo Stock', 'OK'],
                datasets: [{
                    data: [
                        <?php echo $critical_items; ?>,
                        <?php echo $low_items; ?>,
                        <?php echo $ok_items; ?>
                    ],
                    backgroundColor: [
                        '#dc3545',  // Rojo para críticos
                        '#ffc107',  // Amarillo para bajo stock
                        '#198754'   // Verde para OK
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Manejador para cambio de vista en el gráfico de materiales
        document.querySelectorAll('[data-view]').forEach(button => {
            button.addEventListener('click', function() {
                const view = this.dataset.view;
                
                // Actualizar estado activo de los botones
                document.querySelectorAll('[data-view]').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                if (view === 'value') {
                    mainChart.data.datasets[0].label = 'Valor ($)';
                    mainChart.data.datasets[0].data = <?php echo json_encode(array_map(function($m) { 
                        return $m['current_qty'] * $m['price']; 
                    }, $materials)); ?>;
                } else {
                    mainChart.data.datasets[0].label = 'Cantidad Actual';
                    mainChart.data.datasets[0].data = <?php echo json_encode(array_column($materials, 'current_qty')); ?>;
                }
                
                mainChart.update();
            });
        });
    });

    /**
     * Funcionalidad de búsqueda de materiales
     * Filtra la tabla de materiales en tiempo real según el texto ingresado
     */
    document.getElementById('materialSearch').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const table = document.getElementById('materialsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const materialName = rows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
            const category = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
            const supplier = rows[i].getElementsByTagName('td')[4].textContent.toLowerCase();
            
            if (materialName.includes(searchValue) || category.includes(searchValue) || supplier.includes(searchValue)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
    
    /**
     * Sistema de filtrado de materiales
     * Permite filtrar por categoría y proveedor
     */
    const filterButtons = document.querySelectorAll('.filter-btn');
    let activeFilters = {
        category: 'all',
        supplier: 'all'
    };
    
    // Event listeners para botones de filtro
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filterType = this.dataset.filter;
            const filterValue = this.dataset.value;
            
            // Actualizar filtro activo
            activeFilters[filterType] = filterValue;
            
            // Actualizar estado activo en botones
            document.querySelectorAll(`.filter-btn[data-filter="${filterType}"]`).forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // Aplicar filtros
            applyFilters();
        });
    });
    
    // Event listener para botón de limpiar filtros
    document.getElementById('clearFilters').addEventListener('click', function() {
        // Restablecer filtros activos
        activeFilters = {
            category: 'all',
            supplier: 'all'
        };
        
        // Restablecer estado activo en botones
        filterButtons.forEach(button => {
            if (button.dataset.value === 'all') {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
        
        // Aplicar filtros
        applyFilters();
    });
    
    /**
     * Función para aplicar filtros a la tabla de materiales
     * Muestra u oculta filas según los filtros activos
     */
    function applyFilters() {
        const table = document.getElementById('materialsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const category = rows[i].dataset.category;
            const supplier = rows[i].dataset.supplier;
            
            const categoryMatch = activeFilters.category === 'all' || category === activeFilters.category;
            const supplierMatch = activeFilters.supplier === 'all' || supplier === activeFilters.supplier;
            
            if (categoryMatch && supplierMatch) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
</script>

<!-- Favicon -->
<link rel="icon" type="image/png" href="assets/favicon.ico">
</body>
</html>