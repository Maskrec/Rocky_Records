<?php include 'db.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CD'S - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="contenedor-seccion">
        <div class="cabecera-seccion">
            <h2>Catálogo de CD'S</h2>
        </div>
        
        <div class="catalogo">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'CD' ORDER BY fecha_agregado DESC");
            $stmt->execute();
            $resultados = $stmt->fetchAll();

            if (count($resultados) > 0) {
                foreach ($resultados as $row) {
                    $precio_final = $row['precio_oferta'] ? $row['precio_oferta'] : $row['precio'];
                    $disc_class = 'cd';
                    $format_label = 'CD';
            ?>
                    <div class="tarjeta-producto" id="card-<?php echo $row['id']; ?>">
                        <!-- Contenedor de Funda con portada y disco que se asoma -->
                        <div class="contenedor-funda">
                            <span class="etiqueta-nuevo">NUEVO</span>
                            
                            <!-- Funda / Portada -->
                            <div class="contenedor-portada">
                                <img src="uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>" alt="Portada de <?php echo htmlspecialchars($row['titulo']); ?>" class="imagen-portada">
                            </div>
                            
                            <!-- Disco Físico (CD) -->
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
                            <span class="insignia-formato"><?php echo $format_label; ?></span>
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
                    </div>
            <?php 
                }
            } else {
                echo '<p style="width:100%; text-align:center;">Aún no hay CD\'S disponibles.</p>';
            }
            ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Script de Reproducción Interactiva de Audio -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reproductores = document.querySelectorAll('.reproductor audio');
            reproductores.forEach(reproductor => {
                const tarjeta = reproductor.closest('.tarjeta-producto');
                
                reproductor.addEventListener('play', () => {
                    reproductores.forEach(otroReproductor => {
                        if (otroReproductor !== reproductor) {
                            otroReproductor.pause();
                        }
                    });
                    tarjeta.classList.add('reproduciendo');
                });
                
                reproductor.addEventListener('pause', () => {
                    tarjeta.classList.remove('reproduciendo');
                });
                
                reproductor.addEventListener('ended', () => {
                    tarjeta.classList.remove('reproduciendo');
                });
            });
        });
    </script>
</body>
</html>
