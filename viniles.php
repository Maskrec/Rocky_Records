<?php include 'db.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
=======
>>>>>>> e9df818397b3f3e56ce8a0ec827d4fea679014c5
    <title>Viniles - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="catalogo" style="padding-top: 40px;">
        <h2 style="width:100%; text-align:center; margin-bottom: 20px;">Catálogo de Viniles</h2>
        
        <?php
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'Vinil' ORDER BY Fecha_agregado DESC");
        $stmt->execute();
        $resultados = $stmt->fetchAll();

<<<<<<< HEAD
            if (count($resultados) > 0) {
                foreach ($resultados as $row) {
                    $precio_final = $row['precio_oferta'] ? $row['precio_oferta'] : $row['precio'];
                    $disc_class = 'vinyl';
                    $format_label = 'VINYL';
            ?>
                    <div class="tarjeta-producto" id="card-<?php echo $row['id']; ?>">
                        <!-- Contenedor de Funda con portada y disco que se asoma -->
                        <div class="contenedor-funda">
                            <span class="etiqueta-nuevo">NUEVO</span>
                            
                            <!-- Funda / Portada -->
                            <div class="contenedor-portada">
                                <img src="uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>" alt="Portada de <?php echo htmlspecialchars($row['titulo']); ?>" class="imagen-portada">
                            </div>
                            
                            <!-- Disco Físico (Vinilo) -->
                            <div class="disco-soporte <?php echo $disc_class; ?>">
                                <div class="etiqueta-disco" style="background-image: url('uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>'); background-size: cover; background-position: center;">
                                    <div class="centro-disco"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles del Producto -->
                        <span class="artista-album"><?php echo htmlspecialchars($row['artista']); ?></span>
                        <h3 class="titulo-album" title="<?php echo htmlspecialchars($row['titulo']); ?>"><?php echo htmlspecialchars($row['titulo']); ?></h3>
                        <div>
                            <span class="insignia-formato formato-vinilo"><?php echo $format_label; ?></span>
                        </div>

                        <!-- Mini Reproductor de Demostración -->
                        <div class="reproductor">
                            <audio controls controlsList="nodownload" preload="none">
                                <source src="uploads/demos/<?php echo htmlspecialchars($row['demo_url']); ?>" type="audio/mpeg">
                            </audio>
                        </div>

                        <!-- Precio y Botón de Carrito -->
                        <div class="pie-tarjeta">
                            <span class="etiqueta-precio">$<?php echo number_format($precio_final, 0); ?> MXN</span>
                            
                            <form action="agregar_carrito.php" method="POST" style="margin:0;">
                                <input type="hidden" name="id_producto" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="cantidad" value="1">
                                <button type="submit" class="boton-agregar-carrito" title="Agregar al carrito">
                                    <img src="img/svg/icono-carrito.svg" alt="Carrito" class="icono-carrito-btn">
                                </button>
                            </form>
                        </div>
=======
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
>>>>>>> e9df818397b3f3e56ce8a0ec827d4fea679014c5
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
            echo '<p style="width:100%; text-align:center;">Aún no hay viniles disponibles.</p>';
        }
        ?>
    </main>
</body>
<?php include 'footer.php'; ?>
</html>
