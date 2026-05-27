<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="contenedor-carrito">
        <h1>Mi Carrito</h1>
        
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
                echo '<table class="tabla-datos">';
                echo '<tr>';
                echo '<th>Imagen</th>';
                echo '<th>Producto</th>';
                echo '<th>Formato</th>';
                echo '<th>Precio Unitario</th>';
                echo '<th>Cantidad</th>';
                echo '<th>Subtotal</th>';
                echo '</tr>';
                
                $total = 0;
                
                foreach ($productos as $item) {
                    $precio_final = $item['precio_oferta'] ? $item['precio_oferta'] : $item['precio'];
                    $subtotal = $precio_final * $item['cantidad'];
                    $total += $subtotal;
                    
                    echo '<tr>';
                    echo '<td><img src="uploads/portadas/' . htmlspecialchars((string)$item['imagen_url']) . '" alt="Portada" style="width: 80px;"></td>';
                    echo '<td><strong>' . htmlspecialchars((string)$item['titulo']) . '</strong><br>' . htmlspecialchars((string)$item['artista']) . '</td>';
                    echo '<td>' . htmlspecialchars((string)$item['formato']) . '</td>';
                    echo '<td>$' . number_format($precio_final, 2) . ' MXN</td>';
                    echo '<td>' . htmlspecialchars((string)$item['cantidad']) . '</td>';
                    echo '<td>$' . number_format($subtotal, 2) . ' MXN</td>';
                    echo '</tr>';
                }
                echo '<tr>';
                echo '<td colspan="5" style="text-align: right;"><strong>Total:</strong></td>';
                echo '<td><strong>$' . number_format($total, 2) . ' MXN</strong></td>';
                echo '</tr>';
                echo '</table>';
                
                echo '<div style="text-align: right; margin-top: 20px;">';
                echo '<button class="boton-explorar" style="background-color: #e50914; color: white; padding: 10px 20px; border: none; cursor: pointer;">Proceder al Pago</button>';
                echo '</div>';
            } else {
                echo '<p>Tu carrito está vacío.</p>';
                echo '<a href="index.php" class="boton-explorar">Explorar catálogo</a>';
            }
        } catch (Exception $e) {
            echo '<p>Error al cargar el carrito: ' . $e->getMessage() . '</p>';
        }
        ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>