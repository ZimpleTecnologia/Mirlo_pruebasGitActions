<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear carpeta de assets si no existe
    $uploadDir = __DIR__ . '/assets/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Subir logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $filePath = $uploadDir . 'logo.png';
        move_uploaded_file($_FILES['logo']['tmp_name'], $filePath);
    }
    
    // Guardar color primario
    if (isset($_POST['colorPrimario'])) {
        file_put_contents($uploadDir . 'color-primario.txt', $_POST['colorPrimario']);
    }
    
    // Guardar color secundario
    if (isset($_POST['colorSecundario'])) {
        file_put_contents($uploadDir . 'color-secundario.txt', $_POST['colorSecundario']);
    }
}
header('Location: index.php');
exit(); 