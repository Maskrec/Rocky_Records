<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
    $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
    $session_id = session_id();

    if ($producto_id > 0) {
        $stmt = $pdo->prepare("SELECT id, cantidad FROM carrito WHERE session_id = ? AND producto_id = ?");
        $stmt->execute([$session_id, $producto_id]);
        $item = $stmt->fetch();

        if ($item) {
            $nueva_cantidad = $item['cantidad'] + $cantidad;
            $update = $pdo->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
            $update->execute([$nueva_cantidad, $item['id']]);
        } else {
            $insert = $pdo->prepare("INSERT INTO carrito (session_id, producto_id, cantidad) VALUES (?, ?, ?)");
            $insert->execute([$session_id, $producto_id, $cantidad]);
        }
        
        header('Location: ../carrito.php');
        exit;
    }
}

header('Location: ../index.php');
exit;
?>
