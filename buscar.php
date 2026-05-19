<?php include 'db.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de búsqueda - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="catalogo" style="padding-top: 40px;">
        <h2 style="width:100%; text-align:center; margin-bottom: 20px;">
            <?php 
            $q = isset($_GET['q']) ? $_GET['q'] : '';
            if ($q !== '') {
                echo 'Resultados de búsqueda para: "' . htmlspecialchars($q) . '"';
            } else {
                echo 'Búsqueda';
            }
            ?>
        </h2>
        
        <?php
        if ($q !== '') {
            $stmt = $pdo->prepare("SELECT * FROM productos WHERE titulo LIKE ? OR artista LIKE ? ORDER BY Fecha_agregado DESC");
            $like_q = "%$q%";
            $stmt->execute([$like_q, $like_q]);
            $resultados = $stmt->fetchAll();

            if (count($resultados) > 0) {
                foreach ($resultados as $row) {
                    $precio_final = $row['precio_oferta'] ? $row['precio_oferta'] : $row['precio'];
        ?>
                    <div class="producto-card">
                        <img src="uploads/portadas/<?php echo $row['imagen_url']; ?>" alt="Portada">
                        <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($row['artista']); ?> - <strong><?php echo htmlspecialchars($row['formato']); ?></strong></p>
                        
                        <div class="reproductor">
                            <audio controls controlsList="nodownload">
                                <source src="uploads/demos/<?php echo $row['demo_url']; ?>" type="audio/mpeg">
                            </audio>
                        </div>

                        <p class="precio">$<?php echo $precio_final; ?> MXN</p>
                        <form action="agregar_carrito.php" method="POST" style="margin:0;">
                            <?php 
                            $id_val = isset($row['id']) ? $row['id'] : (isset($row['ID']) ? $row['ID'] : (isset($row['id_producto']) ? $row['id_producto'] : ''));
                            ?>
                            <input type="hidden" name="id_producto" value="<?php echo $id_val; ?>">
                            <input type="hidden" name="cantidad" value="1">
                            <button type="submit" class="btn-agregar">agregar al carrito</button>
                        </form>
                    </div>
        <?php 
                }
            } else {
                echo '<p style="width:100%; text-align:center;">No se encontraron resultados. Intenta con otros términos.</p>';
            }
        } else {
            echo '<p style="width:100%; text-align:center;">Por favor, ingresa un término de búsqueda válido.</p>';
        }
        ?>
    </main>
</body>
<?php include 'footer.php'; ?>
</html>
