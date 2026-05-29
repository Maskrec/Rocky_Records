<?php
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pedido_id = isset($_GET['pedido_id']) ? (int)$_GET['pedido_id'] : 0;
$direccion = isset($_GET['direccion']) ? trim($_GET['direccion']) : 'Direccion registrada en cuenta';
$user_id = $_SESSION['user_id'];

try {
    if ($_SESSION['rol'] === 'admin') {
        $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->execute([$pedido_id]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ? AND id_usuario = ?");
        $stmt->execute([$pedido_id, $user_id]);
    }
    $pedido = $stmt->fetch();

    if (!$pedido) {
        die("Error: El pedido no existe o no tienes autorizacion para verlo.");
    }

    $stmt_user = $pdo->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
    $stmt_user->execute([$pedido['id_usuario']]);
    $usuario = $stmt_user->fetch();

    $total = $pedido['total'];
    $costo_envio = 99.00;
    $subtotal = $total - $costo_envio;

} catch (Exception $e) {
    die("Error al cargar el recibo: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Compra #<?php echo $pedido_id; ?> - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .ticket-contenedor {
            max-width: 480px;
            margin: 40px auto;
            padding: 30px 25px;
            background-color: #fbfbfb;
            background-image: url('css/textura-papel.jpg');
            border: 2px dashed var(--color-borde);
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            font-family: 'Courier New', Courier, monospace;
            color: #1c1917;
            position: relative;
        }
        .ticket-header {
            text-align: center;
            border-bottom: 1px dashed #78716c;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .ticket-header h2 {
            font-family: var(--fuente-serif);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .ticket-header p {
            font-size: 12px;
            color: #44403c;
            margin: 2px 0;
        }
        .ticket-seccion {
            border-bottom: 1px dashed #78716c;
            padding-bottom: 15px;
            margin-bottom: 15px;
            font-size: 13px;
        }
        .ticket-fila {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .ticket-fila-bold {
            font-weight: 800;
            font-size: 14px;
        }
        .ticket-direccion {
            font-size: 12px;
            background-color: rgba(0,0,0,0.02);
            padding: 8px;
            border: 1px solid rgba(0,0,0,0.05);
            margin-top: 6px;
            white-space: pre-wrap;
            line-height: 1.4;
        }
        .ticket-total-box {
            font-size: 16px;
            font-weight: 800;
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            border-top: 2px solid #1c1917;
            padding-top: 10px;
        }
        .btn-imprimir {
            display: block;
            width: 100%;
            text-align: center;
            background-color: #292524;
            color: #fff;
            border: none;
            padding: 10px;
            margin-top: 25px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn-imprimir:hover {
            background-color: #1c1917;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .ticket-contenedor, .ticket-contenedor * {
                visibility: visible;
            }
            .ticket-contenedor {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
            .btn-imprimir, .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-seccion">
        <div class="ticket-contenedor">
            <div class="ticket-header">
                <h2>Rocky Records</h2>
                <p>La musica suena mejor en fisico</p>
                <p>RFC: RRE990527-HTD</p>
                <p>Av. Melomania #99, Col. Del Vinilo</p>
                <p>Tel: (55) 9876-5432</p>
            </div>

            <!-- Datos del Pedido -->
            <div class="ticket-seccion">
                <div class="ticket-fila">
                    <span>N° PEDIDO:</span>
                    <strong>#<?php echo htmlspecialchars((string)$pedido_id); ?></strong>
                </div>
                <div class="ticket-fila">
                    <span>FECHA:</span>
                    <span><?php echo htmlspecialchars((string)$pedido['fecha_pedido']); ?></span>
                </div>
                <div class="ticket-fila">
                    <span>CLIENTE:</span>
                    <span style="text-transform: uppercase;"><?php echo htmlspecialchars((string)$usuario['nombre']); ?></span>
                </div>
                <div class="ticket-fila">
                    <span>EMAIL:</span>
                    <span><?php echo htmlspecialchars((string)$usuario['email']); ?></span>
                </div>
            </div>

            <!-- Desglose de Precios -->
            <div class="ticket-seccion">
                <div class="ticket-fila ticket-fila-bold">
                    <span>CONCEPTO</span>
                    <span>IMPORTE</span>
                </div>
                <div class="ticket-fila" style="margin-top: 8px;">
                    <span>Subtotal Productos:</span>
                    <span>$<?php echo number_format($subtotal, 2); ?> MXN</span>
                </div>
                <div class="ticket-fila">
                    <span>Tarifa de Envio:</span>
                    <span>$<?php echo number_format($costo_envio, 2); ?> MXN</span>
                </div>
                
                <div class="ticket-total-box">
                    <span>TOTAL COMPRA:</span>
                    <span>$<?php echo number_format($total, 2); ?> MXN</span>
                </div>
            </div>

            <!-- Datos de Envio -->
            <div class="ticket-seccion" style="border-bottom: none; padding-bottom: 0; margin-bottom: 0;">
                <span style="font-weight: 800;">DIRECCION DE ENTREGA:</span>
                <div class="ticket-direccion"><?php echo htmlspecialchars((string)$direccion); ?></div>
            </div>

            <button onclick="window.print()" class="btn-imprimir">IMPRIMIR RECIBO (TICKET)</button>
            <a href="index.php" class="btn-imprimir no-print" style="background-color: var(--color-naranja); margin-top: 10px;">VOLVER A LA TIENDA</a>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
