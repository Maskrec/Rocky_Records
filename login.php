<?php
include 'db.php';

// Asegurar que exista el administrador por defecto solicitado (admin / 12345678)
try {
    $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE nombre = ? OR email = ?");
    $stmt_check->execute(['admin', 'admin@rockyrecords.com']);
    if (!$stmt_check->fetch()) {
        $pass_hash = password_hash('12345678', PASSWORD_DEFAULT);
        $insert_admin = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        $insert_admin->execute(['admin', 'admin@rockyrecords.com', $pass_hash, 'admin']);
    }
} catch (Exception $e) {
    // Silenciar
}

$mensaje_error = '';
$mensaje_exito = '';

// Procesar Acceso Rapido Administrador (Bypass)
if (isset($_POST['bypass_admin'])) {
    try {
        // Verificar si ya existe el administrador en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = ?");
        $stmt->execute(['admin']);
        $admin = $stmt->fetch();

        if (!$admin) {
            $pass_hash = password_hash('12345678', PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
            $insert->execute(['admin', 'admin@rockyrecords.com', $pass_hash, 'admin']);
            
            $stmt->execute(['admin']);
            $admin = $stmt->fetch();
        }

        // Establecer sesion
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['nombre'] = $admin['nombre'];
        $_SESSION['rol'] = $admin['rol'];

        header('Location: admin.php');
        exit;
    } catch (Exception $e) {
        $mensaje_error = 'Error al iniciar sesion de administrador: ' . $e->getMessage();
    }
}

// Procesar Registro
if (isset($_POST['register'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nombre !== '' && $email !== '' && $password !== '') {
        try {
            // Verificar si el email o nombre ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? OR nombre = ?");
            $stmt->execute([$email, $nombre]);
            if ($stmt->fetch()) {
                $mensaje_error = 'El correo electronico o nombre de usuario ya esta registrado.';
            } else {
                $pass_hash = password_hash($password, PASSWORD_DEFAULT);
                $insert = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')");
                $insert->execute([$nombre, $email, $pass_hash]);
                $mensaje_exito = 'Usuario registrado con exito. Ya puedes iniciar sesion.';
            }
        } catch (Exception $e) {
            $mensaje_error = 'Error al registrar usuario: ' . $e->getMessage();
        }
    } else {
        $mensaje_error = 'Por favor, llena todos los campos del registro.';
    }
}

// Procesar Iniciar Sesion (Permite usar Email o Nombre)
if (isset($_POST['login'])) {
    $email_or_user = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email_or_user !== '' && $password !== '') {
        try {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? OR nombre = ?");
            $stmt->execute([$email_or_user, $email_or_user]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['rol'] = $user['rol'];

                // Redirigir segun el rol
                if ($user['rol'] === 'admin') {
                    header('Location: admin.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $mensaje_error = 'Usuario o contraseña incorrectos.';
            }
        } catch (Exception $e) {
            $mensaje_error = 'Error al iniciar sesion: ' . $e->getMessage();
        }
    } else {
        $mensaje_error = 'Por favor, introduce tu usuario y contraseña.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .contenedor-login {
            max-width: 900px;
            margin: 60px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            background-color: var(--color-tarjeta);
            background-image: url('css/textura-papel.jpg');
            border: 1px solid var(--color-borde);
            border-radius: 6px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .col-formulario {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .col-formulario h2 {
            font-family: var(--fuente-sans);
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--texto-oscuro);
            border-bottom: 2px solid var(--color-borde);
            padding-bottom: 8px;
            text-transform: uppercase;
        }
        .formulario-login {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .formulario-login label {
            font-size: 12px;
            font-weight: 700;
            color: var(--texto-atenuado);
            text-transform: uppercase;
        }
        .formulario-login input {
            padding: 10px;
            border: 1px solid var(--color-borde);
            border-radius: 4px;
            font-family: var(--fuente-sans);
            font-size: 14px;
            background-color: #fff;
            outline: none;
            transition: border-color 0.3s;
        }
        .formulario-login input:focus {
            border-color: var(--color-naranja);
        }
        .alert {
            padding: 10px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        .alert-error {
            background-color: #fde8e8;
            color: #9b1c1c;
            border: 1px solid #f8b4b4;
        }
        .alert-success {
            background-color: #edfdfd;
            color: #03543f;
            border: 1px solid #bcf0da;
        }
        .seccion-bypass {
            grid-column: 1 / -1;
            text-align: center;
            border-top: 1px solid var(--color-borde);
            padding-top: 25px;
            margin-top: 10px;
        }
        .seccion-bypass p {
            font-size: 13px;
            color: var(--texto-atenuado);
            margin-bottom: 12px;
        }
        .btn-bypass {
            background-color: #292524;
            color: var(--texto-claro);
            border: 1px solid #44403c;
            padding: 10px 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-bypass:hover {
            background-color: #1c1917;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        @media (max-width: 768px) {
            .contenedor-login {
                grid-template-columns: 1fr;
                margin: 30px 15px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="contenedor-seccion">
        <h1 style="text-align:center; font-family: var(--fuente-serif); margin-bottom: 30px;">MI CUENTA</h1>

        <?php if ($mensaje_error !== ''): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($mensaje_error); ?></div>
        <?php endif; ?>

        <?php if ($mensaje_exito !== ''): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($mensaje_exito); ?></div>
        <?php endif; ?>

        <div class="contenedor-login">
            <!-- Formulario Iniciar Sesion -->
            <div class="col-formulario" style="border-right: 1px solid var(--color-borde);">
                <h2>Iniciar Sesion</h2>
                <form action="login.php" method="POST" class="formulario-login">
                    <div>
                        <label for="login-email">Correo o Usuario</label><br>
                        <input type="text" id="login-email" name="email" required style="width: 100%;">
                    </div>
                    <div>
                        <label for="login-password">Contraseña</label><br>
                        <input type="password" id="login-password" name="password" required style="width: 100%;">
                    </div>
                    <button type="submit" name="login" class="boton-explorar" style="align-self: flex-start; margin-top: 10px;">INGRESAR</button>
                </form>
            </div>

            <!-- Formulario Registrarse -->
            <div class="col-formulario">
                <h2>Registrarse</h2>
                <form action="login.php" method="POST" class="formulario-login">
                    <div>
                        <label for="reg-nombre">Nombre de Usuario</label><br>
                        <input type="text" id="reg-nombre" name="nombre" required style="width: 100%;">
                    </div>
                    <div>
                        <label for="reg-email">Correo Electronico</label><br>
                        <input type="email" id="reg-email" name="email" required style="width: 100%;">
                    </div>
                    <div>
                        <label for="reg-password">Contraseña</label><br>
                        <input type="password" id="reg-password" name="password" required style="width: 100%;">
                    </div>
                    <button type="submit" name="register" class="boton-explorar" style="align-self: flex-start; margin-top: 10px;">REGISTRARSE</button>
                </form>
            </div>

            <!-- Acceso Rapido Administrador -->
            <div class="seccion-bypass">
                <p>¿Eres el administrador de la tienda? Accede directamente sin contraseñas de prueba.</p>
                <form action="login.php" method="POST">
                    <button type="submit" name="bypass_admin" class="btn-bypass">ACCESO RAPIDO ADMINISTRADOR ➔</button>
                </form>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
