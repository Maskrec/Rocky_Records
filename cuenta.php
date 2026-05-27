<?php
include 'db.php';

// Cerrar sesion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Redirigir si no esta logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Cuenta - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="contenedor-seccion" style="max-width: 1000px; margin: 40px auto; min-height: 70vh;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--color-borde); padding-bottom: 10px; margin-bottom: 35px;">
            <h1 style="font-family: var(--fuente-serif); margin: 0; text-transform: uppercase;">Mi Cuenta</h1>
            <a href="cuenta.php?logout=1" class="boton-explorar" style="background-color: #78716c; font-size: 11px; padding: 8px 16px;">CERRAR SESION</a>
        </div>
        
        <!-- Datos del Usuario -->
        <section style="margin-bottom: 45px;">
            <h2 style="font-family: var(--fuente-sans); font-size: 18px; font-weight: 800; text-transform: uppercase; margin-bottom: 15px; color: var(--texto-oscuro);">Datos Personales</h2>
            <?php
            try {
                $stmt = $pdo->prepare("SELECT nombre, email, rol, fecha_registro FROM usuarios WHERE id = ?");
                $stmt->execute([$user_id]);
                $usuario = $stmt->fetch();
                
                if ($usuario) {
                    echo '<table class="tabla-datos" style="width: 100%; border-collapse: collapse; background-color: var(--color-tarjeta); border: 1px solid var(--color-borde);">';
                    echo '<tr><th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left; background-color: #eae1d4; font-size: 13px; font-weight: 700; width: 30%;">NOMBRE</th><td style="padding: 12px; border: 1px solid var(--color-borde); font-size: 14px;">' . htmlspecialchars($usuario['nombre']) . '</td></tr>';
                    echo '<tr><th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left; background-color: #eae1d4; font-size: 13px; font-weight: 700;">EMAIL</th><td style="padding: 12px; border: 1px solid var(--color-borde); font-size: 14px;">' . htmlspecialchars($usuario['email']) . '</td></tr>';
                    echo '<tr><th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left; background-color: #eae1d4; font-size: 13px; font-weight: 700;">ROL</th><td style="padding: 12px; border: 1px solid var(--color-borde); font-size: 14px; text-transform: uppercase; font-weight: 700; color: ' . ($usuario['rol'] === 'admin' ? 'var(--color-naranja)' : 'inherit') . ';">' . htmlspecialchars($usuario['rol']) . '</td></tr>';
                    echo '<tr><th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left; background-color: #eae1d4; font-size: 13px; font-weight: 700;">FECHA REGISTRO</th><td style="padding: 12px; border: 1px solid var(--color-borde); font-size: 14px;">' . htmlspecialchars($usuario['fecha_registro']) . '</td></tr>';
                    echo '</table>';
                    
                    if ($usuario['rol'] === 'admin') {
                        echo '<div style="margin-top: 15px;">';
                        echo '<a href="admin.php" class="boton-explorar" style="font-size: 11px; padding: 8px 16px;">IR AL PANEL DE ADMINISTRACION ➔</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No se encontraron datos del usuario.</p>';
                }
            } catch (Exception $e) {
                echo '<p class="error-msg">Error al cargar datos del usuario: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
            ?>
        </section>

        <!-- Mis Pedidos -->
        <section>
            <h2 style="font-family: var(--fuente-sans); font-size: 18px; font-weight: 800; text-transform: uppercase; margin-bottom: 15px; color: var(--texto-oscuro);">Historial de Pedidos</h2>
            <div style="overflow-x:auto;">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT id, total, estado, fecha_pedido FROM pedidos WHERE id_usuario = ? ORDER BY fecha_pedido DESC");
                $stmt->execute([$user_id]);
                $pedidos = $stmt->fetchAll();
                
                if (count($pedidos) > 0) {
                    echo '<table class="tabla-datos" style="width: 100%; border-collapse: collapse; background-color: var(--color-tarjeta); border: 1px solid var(--color-borde);">';
                    echo '<tr style="background-color: #eae1d4;">';
                    echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left; font-size: 13px; font-weight: 700;">N° PEDIDO</th>';
                    echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left; font-size: 13px; font-weight: 700;">FECHA</th>';
                    echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left; font-size: 13px; font-weight: 700;">ESTADO</th>';
                    echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left; font-size: 13px; font-weight: 700;">TOTAL</th>';
                    echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: center; font-size: 13px; font-weight: 700;">ACCIONES</th>';
                    echo '</tr>';
                    
                    foreach ($pedidos as $pedido) {
                        echo '<tr>';
                        echo '<td style="padding: 12px; border: 1px solid var(--color-borde); font-size: 14px; font-weight: 700;">#' . $pedido['id'] . '</td>';
                        echo '<td style="padding: 12px; border: 1px solid var(--color-borde); font-size: 14px;">' . htmlspecialchars($pedido['fecha_pedido']) . '</td>';
                        echo '<td style="padding: 12px; border: 1px solid var(--color-borde); font-size: 14px; text-transform: uppercase; font-weight: 700;"><span style="color: ' . ($pedido['estado'] === 'completado' ? '#059669' : '#d97706') . ';">' . htmlspecialchars($pedido['estado']) . '</span></td>';
                        echo '<td style="padding: 12px; border: 1px solid var(--color-borde); font-size: 14px; font-weight: 700; color: var(--color-naranja);">$' . number_format($pedido['total'], 2) . ' MXN</td>';
                        echo '<td style="padding: 12px; border: 1px solid var(--color-borde); text-align: center;"><a href="ticket.php?pedido_id=' . $pedido['id'] . '" class="boton-explorar" style="font-size: 10px; padding: 6px 12px; background-color: #44403c;">VER RECIBO</a></td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p style="font-size: 14px; color: var(--texto-atenuado);">Aun no tienes pedidos registrados.</p>';
                }
            } catch (Exception $e) {
                echo '<p class="error-msg">Error al cargar pedidos: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
            ?>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>