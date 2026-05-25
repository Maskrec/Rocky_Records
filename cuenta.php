<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Cuenta - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="contenedor-cuenta">
        <h1>Mi Cuenta</h1>
        
        <h2>Datos del Usuario</h2>
        <div style="overflow-x:auto;">
        <?php
        try {
            // Obtener el primer usuario registrado en la base de datos
            $stmt = $pdo->query("SELECT * FROM usuarios LIMIT 1");
            $usuario = $stmt->fetch();
            if ($usuario) {
                echo '<table class="tabla-datos">';
                foreach ($usuario as $key => $value) {
                    echo '<tr><th>' . htmlspecialchars(ucfirst(str_replace('_', ' ', $key))) . '</th><td>' . htmlspecialchars((string)$value) . '</td></tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No se encontraron datos de usuario.</p>';
            }
        } catch (Exception $e) {
            echo '<p>Error al cargar usuario: ' . $e->getMessage() . '</p>';
        }
        ?>
        </div>

        <h2>Mis Pedidos</h2>
        <div style="overflow-x:auto;">
        <?php
        try {
            // Obtener todos los pedidos registrados en la base de datos
            $stmt = $pdo->query("SELECT * FROM pedidos");
            $pedidos = $stmt->fetchAll();
            if (count($pedidos) > 0) {
                echo '<table class="tabla-datos">';
                echo '<tr>';
                // Encabezados de columnas
                foreach (array_keys($pedidos[0]) as $key) {
                    echo '<th>' . htmlspecialchars(ucfirst(str_replace('_', ' ', $key))) . '</th>';
                }
                echo '</tr>';
                // Filas de datos
                foreach ($pedidos as $pedido) {
                    echo '<tr>';
                    foreach ($pedido as $value) {
                        echo '<td>' . htmlspecialchars((string)$value) . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No tienes pedidos registrados.</p>';
            }
        } catch (Exception $e) {
            echo '<p>Error al cargar pedidos: ' . $e->getMessage() . '</p>';
        }
        ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>