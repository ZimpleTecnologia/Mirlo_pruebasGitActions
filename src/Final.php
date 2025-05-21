<?php
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

// =============================================
// CÁLCULOS Y PROCESAMIENTO DE DATOS
// =============================================

/**
 * Cálculo de estadísticas del inventario
 * - Total de materiales
 * - Items críticos
 * - Items con bajo stock
 * - Valor total del inventario
 */
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
    <title>Control de Inventario de Mirlo Constucciones S.A.S</title>
    
    <!-- Estilos CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <style>
        /* Estilos generales */
        body {
            background-color: #f8f9fa;
            color: #495057;
        }
        
        /* Estilos para tarjetas */
        .custom-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .custom-card:hover {
            transform: translateY(-5px);
        }
        
        /* Estilos para barras de progreso */
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        
        /* Estilos para tablas */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        
        /* Estilos para badges de estado */
        .badge-critical {
            background-color: #dc3545;
            color: white;
        }
        .badge-low {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-ok {
            background-color: #28a745;
            color: white;
        }
        
        /* Estilos para contenedores de gráficos */
        .chart-container {
            height: 300px;
        }
        
        /* Estilos para navegación */
        .nav-pills .nav-link.active {
            background-color: #6c757d;
        }
        
        /* Estilos para botones de filtro */
        .filter-btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .filter-btn.active {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Encabezado -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="mb-3">Control de Inventario de Mirlo Constucciones S.A.S</h1>
                
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
                <div class="custom-card card bg-white h-100">
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
                <div class="custom-card card bg-white h-100">
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
                <div class="custom-card card bg-white h-100">
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
                <div class="custom-card card bg-white h-100">
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
        
        <!-- Sección de Gráficos y Carga -->
        <div class="row mb-4">
            <div class="col-md-8 mb-3">
                <div class="custom-card card bg-white">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Resumen del Estado del Inventario</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="inventoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="custom-card card bg-white h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Importar Datos de Inventario</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="mb-3">
                            <div class="mb-3">
                                <label for="excelFile" class="form-label">Cargar Hoja de Cálculo Excel</label>
                                <input class="form-control form-control-sm" id="excelFile" type="file" accept=".xlsx, .xls, .csv">
                                <div class="form-text">Formatos soportados: .xlsx, .xls, .csv</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Datos a Importar</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="materials" checked>
                                    <label class="form-check-label" for="materials">
                                        Inventario de Materiales
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="suppliers">
                                    <label class="form-check-label" for="suppliers">
                                        Información de Proveedores
                                    </label>
                                </div>
                            </div>
                            <button type="submit" name="upload_file" class="btn btn-primary btn-sm">
                                <i class="fas fa-upload me-1"></i> Subir y Procesar
                            </button>
                        </form>
                        <div class="text-center py-3">
                            <i class="fas fa-file-excel fa-4x text-success opacity-50"></i>
                            <p class="mt-2 mb-0 small">Arrastre y suelte archivos aquí o haga clic para buscar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de Inventario de Materiales -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="custom-card card bg-white">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Inventario de Materiales</h5>
                        <div class="d-flex align-items-center">
                            <div class="input-group input-group-sm me-2" style="width: 250px;">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control" id="materialSearch" placeholder="Buscar materiales...">
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                                <i class="fas fa-plus me-1"></i> Agregar Material
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Filtros:</strong>
                                <button class="btn btn-sm btn-outline-secondary" id="clearFilters">Limpiar Todo</button>
                            </div>
                            
                            <div class="mb-2">
                                <span class="text-muted me-2">Categoría:</span>
                                <button class="btn btn-sm btn-outline-secondary filter-btn active" data-filter="category" data-value="all">Todos</button>
                                <?php foreach ($categories as $category): ?>
                                <button class="btn btn-sm btn-outline-secondary filter-btn" data-filter="category" data-value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></button>
                                <?php endforeach; ?>
                            </div>
                            
                            <div>
                                <span class="text-muted me-2">Proveedor:</span>
                                <button class="btn btn-sm btn-outline-secondary filter-btn active" data-filter="supplier" data-value="all">Todos</button>
                                <?php foreach ($supplier_names as $supplier): ?>
                                <button class="btn btn-sm btn-outline-secondary filter-btn" data-filter="supplier" data-value="<?php echo htmlspecialchars($supplier); ?>"><?php echo htmlspecialchars($supplier); ?></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle" id="materialsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Material</th>
                                        <th>Categoría</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                        <th>Proveedor</th>
                                        <th>Precio</th>
                                        <th>Último Pedido</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($materials as $material): ?>
                                    <tr data-category="<?php echo htmlspecialchars($material['category']); ?>" data-supplier="<?php echo htmlspecialchars($material['supplier']); ?>">
                                        <td>
                                            <strong><?php echo htmlspecialchars($material['name']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($material['category']); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2"><?php echo $material['current_qty']; ?></span>
                                                <div class="progress flex-grow-1">
                                                    <?php
                                                    $percentage = min(100, ($material['current_qty'] / $material['min_qty']) * 100);
                                                    $bar_class = 'bg-success';
                                                    
                                                    if ($percentage < 50) {
                                                        $bar_class = 'bg-danger';
                                                    } elseif ($percentage < 100) {
                                                        $bar_class = 'bg-warning';
                                                    }
                                                    ?>
                                                    <div class="progress-bar <?php echo $bar_class; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="ms-2 text-muted small">Mín: <?php echo $material['min_qty']; ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            switch ($material['status']) {
                                                case 'Críticos':
                                                    $status_class = 'badge-critical';
                                                    break;
                                                case 'Bajo':
                                                    $status_class = 'badge-low';
                                                    break;
                                                case 'OK':
                                                    $status_class = 'badge-ok';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?php echo $status_class; ?>"><?php echo $material['status']; ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($material['supplier']); ?></td>
                                        <td>$<?php echo number_format($material['price'], 2); ?></td>
                                        <td><?php echo $material['last_order']; ?></td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <?php if ($material['status'] == 'Críticos' || $material['status'] == 'Bajo'): ?>
                                                <form method="post" class="me-1">
                                                    <input type="hidden" name="material_name" value="<?php echo htmlspecialchars($material['name']); ?>">
                                                    <input type="hidden" name="supplier_name" value="<?php echo htmlspecialchars($material['supplier']); ?>">
                                                    <button type="submit" name="order_material" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                                
                                                <a href="mailto:<?php
                                                    $supplier_email = '';
                                                    foreach ($suppliers as $supplier) {
                                                        if ($supplier['name'] == $material['supplier']) {
                                                            $supplier_email = $supplier['email'];
                                                            break;
                                                        }
                                                    }
                                                    echo $supplier_email;
                                                ?>" class="btn btn-outline-secondary btn-sm me-1">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                                
                                                <?php
                                                $supplier_phone = '';
                                                foreach ($suppliers as $supplier) {
                                                    if ($supplier['name'] == $material['supplier']) {
                                                        $supplier_phone = $supplier['phone'];
                                                        break;
                                                    }
                                                }
                                                // Eliminar caracteres no numéricos para el enlace de WhatsApp
                                                $clean_phone = preg_replace('/[^0-9]/', '', $supplier_phone);
                                                ?>
                                                
                                                <a href="https://wa.me/<?php echo $clean_phone; ?>" target="_blank" class="btn btn-outline-success btn-sm">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            </div>
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
        
        <!-- Pestañas de Proveedores y Órdenes de Compra -->
        <div class="row">
            <div class="col-12">
                <div class="custom-card card bg-white">
                    <div class="card-header bg-white">
                        <ul class="nav nav-pills" id="tabsNav" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="suppliers-tab" data-bs-toggle="tab" data-bs-target="#suppliers-tab-pane" type="button" role="tab" aria-controls="suppliers-tab-pane" aria-selected="true">Proveedores</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders-tab-pane" type="button" role="tab" aria-controls="orders-tab-pane" aria-selected="false">Órdenes de Compra</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="tabsContent">
                            <!-- Pestaña de Proveedores -->
                            <div class="tab-pane fade show active" id="suppliers-tab-pane" role="tabpanel" aria-labelledby="suppliers-tab" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Persona de Contacto</th>
                                                <th>Correo Electrónico</th>
                                                <th>Teléfono</th>
                                                <th>Productos</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($suppliers as $supplier): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($supplier['name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($supplier['contact']); ?></td>
                                                <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                                                <td><?php echo htmlspecialchars($supplier['phone']); ?></td>
                                                <td><?php echo htmlspecialchars($supplier['products']); ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-end">
                                                        <a href="mailto:<?php echo htmlspecialchars($supplier['email']); ?>" class="btn btn-outline-secondary btn-sm me-1">
                                                            <i class="fas fa-envelope"></i>
                                                        </a>
                                                        <?php
                                                        // Eliminar caracteres no numéricos para el enlace de WhatsApp
                                                        $clean_phone = preg_replace('/[^0-9]/', '', $supplier['phone']);
                                                        ?>
                                                        <a href="https://wa.me/<?php echo $clean_phone; ?>" target="_blank" class="btn btn-outline-success btn-sm">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Pestaña de Órdenes de Compra -->
                            <div class="tab-pane fade" id="orders-tab-pane" role="tabpanel" aria-labelledby="orders-tab" tabindex="0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID de Orden</th>
                                                <th>Fecha</th>
                                                <th>Proveedor</th>
                                                <th>Items</th>
                                                <th>Monto</th>
                                                <th>Estado</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($purchase_orders as $order): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($order['id']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($order['date']); ?></td>
                                                <td><?php echo htmlspecialchars($order['supplier']); ?></td>
                                                <td><?php echo htmlspecialchars($order['items']); ?></td>
                                                <td>$<?php echo number_format($order['amount'], 2); ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = '';
                                                    switch ($order['status']) {
                                                        case 'Pendiente':
                                                            $status_class = 'bg-warning text-dark';
                                                            break;
                                                        case 'Entregado':
                                                            $status_class = 'bg-success text-white';
                                                            break;
                                                        case 'Cancelado':
                                                            $status_class = 'bg-danger text-white';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge <?php echo $status_class; ?>"><?php echo $order['status']; ?></span>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn-outline-secondary btn-sm me-1" data-bs-toggle="tooltip" title="Ver Detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-print"></i>
                                                        </button>
                                                    </div>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
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
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-sm">Agregar Material</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
         * Configuración del gráfico de inventario
         * Muestra la comparación entre cantidad actual y mínima de cada material
         */
        const inventoryChartCtx = document.getElementById('inventoryChart').getContext('2d');
        const inventoryChart = new Chart(inventoryChartCtx, {
            type: 'bar',
            data: {
                labels: [<?php echo "'" . implode("', '", array_column($materials, 'name')) . "'"; ?>],
                datasets: [
                    {
                        label: 'Cantidad Actual',
                        data: [<?php echo implode(", ", array_column($materials, 'current_qty')); ?>],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Cantidad Mínima',
                        data: [<?php echo implode(", ", array_column($materials, 'min_qty')); ?>],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        type: 'line'
                    }
                ]
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
</body>
</html>
