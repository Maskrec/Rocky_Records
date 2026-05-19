<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
    $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
    $session_id = session_id();

    if ($producto_id > 0) {
        // Verificar si el producto ya existe en el carrito
        $stmt = $pdo->prepare("SELECT id, cantidad FROM carrito WHERE session_id = ? AND producto_id = ?");
        $stmt->execute([$session_id, $producto_id]);
        $item = $stmt->fetch();

        if ($item) {
            // Actualizar cantidad
            $nueva_cantidad = $item['cantidad'] + $cantidad;
            $update = $pdo->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
            $update->execute([$nueva_cantidad, $item['id']]);
        } else {
            // Insertar nuevo producto en el carrito
            $insert = $pdo->prepare("INSERT INTO carrito (session_id, producto_id, cantidad) VALUES (?, ?, ?)");
            $insert->execute([$session_id, $producto_id, $cantidad]);
        }
        
        // Redirigir al carrito o a donde el usuario estaba
        header('Location: carrito.php');
        exit;
    }
}

// Si no hay datos, regresar a inicio
header('Location: index.php');
exit;
