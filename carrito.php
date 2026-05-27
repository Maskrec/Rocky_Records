<?php 
include 'db.php'; 

// Logica de eliminacion de elementos del carrito
if (isset($_GET['eliminar_id'])) {
    try {
        $eliminar_id = (int)$_GET['eliminar_id'];
        $stmt_del = $pdo->prepare("DELETE FROM carrito WHERE id = ? AND session_id = ?");
        $stmt_del->execute([$eliminar_id, session_id()]);
    } catch (Exception $e) {
        // Silenciar
    }
    header('Location: carrito.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .desglose-pago {
            background-color: var(--color-tarjeta);
            background-image: url('css/textura-papel.jpg');
            border: 1px solid var(--color-borde);
            padding: 20px;
            border-radius: 4px;
            margin-top: 30px;
            max-width: 500px;
            margin-left: auto;
        }
        .desglose-linea {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .desglose-total {
            border-top: 2px solid var(--color-borde);
            padding-top: 10px;
            font-weight: 800;
            font-size: 16px;
            color: var(--color-naranja);
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="contenedor-carrito" style="max-width: 1200px; margin: 40px auto; padding: 0 20px; min-height: 70vh;">
        <h1 style="font-family: var(--fuente-serif); border-bottom: 2px solid var(--color-borde); padding-bottom: 10px; margin-bottom: 30px; text-transform: uppercase;">Mi Carrito</h1>
        
        <div style="overflow-x:auto;">
        <?php
        try {
            $session_id = session_id();
            
            $stmt = $pdo->prepare("
                SELECT c.id as carrito_id, p.imagen_url, p.titulo, p.artista, p.formato, p.precio, p.precio_oferta, c.cantidad 
                FROM carrito c 
                JOIN productos p ON c.producto_id = p.id 
                WHERE c.session_id = ?
            ");
            $stmt->execute([$session_id]);
            $productos = $stmt->fetchAll();
            
            if (count($productos) > 0) {
                echo '<table class="tabla-datos" style="width: 100%; border-collapse: collapse; background-color: var(--color-tarjeta); border: 1px solid var(--color-borde);">';
                echo '<tr style="background-color: #eae1d4;">';
                echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left;">Imagen</th>';
                echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left;">Producto</th>';
                echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left;">Formato</th>';
                echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left;">Precio Unitario</th>';
                echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left;">Cantidad</th>';
                echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: left;">Subtotal</th>';
                echo '<th style="padding: 12px; border: 1px solid var(--color-borde); text-align: center;">Acciones</th>';
                echo '</tr>';
                
                $subtotal_productos = 0;
                
                foreach ($productos as $item) {
                    $precio_final = $item['precio_oferta'] ? $item['precio_oferta'] : $item['precio'];
                    $subtotal = $precio_final * $item['cantidad'];
                    $subtotal_productos += $subtotal;
                    
                    echo '<tr>';
                    echo '<td style="padding: 12px; border: 1px solid var(--color-borde);"><img src="uploads/portadas/' . htmlspecialchars((string)$item['imagen_url']) . '" alt="Portada" style="width: 80px; display: block; box-shadow: 2px 3px 8px rgba(0,0,0,0.15);"></td>';
                    echo '<td style="padding: 12px; border: 1px solid var(--color-borde);"><strong>' . htmlspecialchars((string)$item['titulo']) . '</strong><br>' . htmlspecialchars((string)$item['artista']) . '</td>';
                    echo '<td style="padding: 12px; border: 1px solid var(--color-borde); text-transform: uppercase; font-weight: 700; font-size: 11px;">' . htmlspecialchars((string)$item['formato']) . '</td>';
                    echo '<td style="padding: 12px; border: 1px solid var(--color-borde); font-weight: 600;">$' . number_format($precio_final, 2) . ' MXN</td>';
                    echo '<td style="padding: 12px; border: 1px solid var(--color-borde);">' . htmlspecialchars((string)$item['cantidad']) . '</td>';
                    echo '<td style="padding: 12px; border: 1px solid var(--color-borde); font-weight: 700;">$' . number_format($subtotal, 2) . ' MXN</td>';
                    echo '<td style="padding: 12px; border: 1px solid var(--color-borde); text-align: center;"><a href="carrito.php?eliminar_id=' . $item['carrito_id'] . '" style="color: #b91c1c; font-weight: 700; text-decoration: none; font-size: 12px; border: 1px solid #b91c1c; padding: 5px 10px; border-radius: 3px; background-color: rgba(185, 28, 28, 0.05); transition: all 0.3s;" onmouseover="this.style.backgroundColor=\'#b91c1c\'; this.style.color=\'#ffffff\';" onmouseout="this.style.backgroundColor=\'rgba(185, 28, 28, 0.05)\'; this.style.color=\'#b91c1c\';">Eliminar</a></td>';
                    echo '</tr>';
                }
                echo '</table>';
                
                $costo_envio = 99.00;
                $total_final = $subtotal_productos + $costo_envio;
                ?>
                
                <form action="procesar_pago.php" method="POST">
                    <div class="desglose-pago">
                        <h3 style="font-family: var(--fuente-sans); border-bottom: 1px solid var(--color-borde); padding-bottom: 6px; margin-bottom: 15px; text-transform: uppercase; font-size: 14px;">Detalles de la Compra</h3>
                        
                        <div class="desglose-linea">
                            <span>Subtotal productos:</span>
                            <span style="font-weight: 600;">$<?php echo number_format($subtotal_productos, 2); ?> MXN</span>
                        </div>
                        
                        <div class="desglose-linea">
                            <span>Costo de envio:</span>
                            <span style="font-weight: 600;">$<?php echo number_format($costo_envio, 2); ?> MXN</span>
                        </div>

                        <div style="margin-top: 15px; margin-bottom: 15px;">
                            <label for="direccion_envio" style="display: block; font-size: 11px; font-weight: 700; color: var(--texto-atenuado); text-transform: uppercase; margin-bottom: 6px;">Direccion de Envio</label>
                            <textarea id="direccion_envio" name="direccion_envio" rows="3" required placeholder="Ingresa tu direccion completa para el envio..." style="width: 100%; padding: 10px; border: 1px solid var(--color-borde); border-radius: 4px; font-family: var(--fuente-sans); font-size: 13px; outline: none;"></textarea>
                        </div>
                        
                        <div class="desglose-linea desglose-total">
                            <span>Total a pagar:</span>
                            <span>$<?php echo number_format($total_final, 2); ?> MXN</span>
                        </div>
                        
                        <div style="text-align: right; margin-top: 20px;">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <input type="hidden" name="total_pago" value="<?php echo $total_final; ?>">
                                <button type="submit" class="boton-explorar" style="width: 100%; justify-content: center; font-size: 13px;">PROCEDER AL PAGO ➔</button>
                            <?php else: ?>
                                <p style="color: #c2410c; font-size: 12px; font-weight: 700; text-align: center; margin-bottom: 10px; text-transform: uppercase;">Debes iniciar sesion para realizar la compra</p>
                                <a href="login.php" class="boton-explorar" style="width: 100%; justify-content: center; font-size: 13px; background-color: #44403c;">INICIAR SESION / REGISTRARSE</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
                
                <?php
            } else {
                echo '<div style="text-align: center; padding: 40px 20px; background-color: var(--color-tarjeta); border: 1px solid var(--color-borde); border-radius: 4px; background-image: url(\'css/textura-papel.jpg\');">';
                echo '<p style="font-size: 16px; color: var(--texto-atenuado); margin-bottom: 20px;">Tu carrito esta vacio.</p>';
                echo '<a href="index.php" class="boton-explorar">EXPLORAR CATALOGO</a>';
                echo '</div>';
            }
        } catch (Exception $e) {
            echo '<p class="error-msg">Error al cargar el carrito: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>