<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
// Leer colores personalizados
$colorPrimario = '#1e293b';
$colorSecundario = '#0d6efd';
$colorFile = __DIR__ . '/assets/color-primario.txt';
if (file_exists($colorFile)) {
    $colorPrimario = trim(file_get_contents($colorFile));
}
$colorFile = __DIR__ . '/assets/color-secundario.txt';
if (file_exists($colorFile)) {
    $colorSecundario = trim(file_get_contents($colorFile));
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';
    if ($user === 'admin' && $pass === 'admin') {
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit();
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Mirlo Construcciones S.A.S.</title>
    <!-- Solo incluimos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to left, <?php echo $colorPrimario; ?> 80%, <?php echo $colorSecundario; ?> 20%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        .login-container {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2.5rem 2rem;
            max-width: 400px;
            width: 100%;
        }
        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 1rem;
        }
        .btn-custom {
            background-color: <?php echo $colorPrimario; ?>;
            border-color: <?php echo $colorPrimario; ?>;
            color: white;
        }
        .btn-custom:hover {
            background-color: <?php echo $colorPrimario; ?>;
            filter: brightness(90%);
            color: white;
        }
        h1 {
            color: <?php echo $colorPrimario; ?>;
        }
        /* Estilo para iconos */
        .bi {
            color: <?php echo $colorSecundario; ?>;
        }
    </style>
</head>
<body>
    <div class="login-container text-center">
        <img src="assets/logo.png" alt="Logo Mirlo" class="logo mb-3" onerror="this.src=''">
        <h2 class="mb-2">Bienvenido a</h2>
        <h1 class="mb-4 fw-bold">Mirlo Construcciones S.A.S.</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger py-2" role="alert"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div class="mb-3 text-start">
                <label for="user" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="user" name="user" required autofocus>
            </div>
            <div class="mb-4 text-start">
                <label for="pass" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="pass" name="pass" required>
            </div>
            <button type="submit" class="btn btn-custom w-100">Iniciar sesión</button>
        </form>
        <div class="mt-4">
            <small class="text-muted">© 2024 Mirlo Construcciones S.A.S.</small>
        </div>
    </div>
    <!-- Solo script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 