<?php
include 'db.php';

// Validar que el usuario haya iniciado sesion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = trim($_POST['direccion_envio'] ?? '');
    $user_id = $_SESSION['user_id'];
    $session_id = session_id();

    if ($direccion === '') {
        header('Location: carrito.php');
        exit;
    }

    try {
        // 1. Obtener los productos actuales en el carrito para calcular el total real
        $stmt = $pdo->prepare("
            SELECT p.precio, p.precio_oferta, c.cantidad 
            FROM carrito c 
            JOIN productos p ON c.producto_id = p.id 
            WHERE c.session_id = ?
        ");
        $stmt->execute([$session_id]);
        $productos = $stmt->fetchAll();

        if (count($productos) > 0) {
            $subtotal_productos = 0;
            foreach ($productos as $item) {
                $precio_final = $item['precio_oferta'] ? $item['precio_oferta'] : $item['precio'];
                $subtotal_productos += $precio_final * $item['cantidad'];
            }

            // Sumar tarifa fija de envio de $99
            $costo_envio = 99.00;
            $total_final = $subtotal_productos + $costo_envio;

            // 2. Insertar el pedido en la base de datos
            $insert = $pdo->prepare("
                INSERT INTO pedidos (id_usuario, total, estado, fecha_pedido) 
                VALUES (?, ?, 'completado', CURRENT_TIMESTAMP)
            ");
            $insert->execute([$user_id, $total_final]);
            $pedido_id = $pdo->lastInsertId();

            // 3. Vaciar el carrito de la sesion actual
            $clear_cart = $pdo->prepare("DELETE FROM carrito WHERE session_id = ?");
            $clear_cart->execute([$session_id]);

            // 4. Redirigir al ticket pasando el pedido_id y la direccion
            header('Location: ticket.php?pedido_id=' . $pedido_id . '&direccion=' . urlencode($direccion));
            exit;
        }
    } catch (Exception $e) {
        die("Error al procesar el pago: " . $e->getMessage());
    }
}

// En caso de acceso incorrecto, volver al carrito
header('Location: carrito.php');
exit;
