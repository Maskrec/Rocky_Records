<?php include 'includes/db.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas Especiales - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-seccion">
        <div class="cabecera-seccion">
            <h2>Ofertas Especiales</h2>
        </div>
        
        <div class="catalogo">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM productos WHERE precio_oferta IS NOT NULL AND precio_oferta > 0 ORDER BY (precio - precio_oferta) DESC");
            $stmt->execute();
            $resultados = $stmt->fetchAll();

            if (count($resultados) > 0) {
                foreach ($resultados as $row) {
                    $precio_final = $row['precio_oferta'];
                    $is_cd = ($row['formato'] === 'CD');
                    $disc_class = $is_cd ? 'cd' : 'vinyl';
                    $format_label = $is_cd ? 'CD' : 'VINYL'; ?>
                    <div class="tarjeta-producto" id="card-<?php echo $row['id']; ?>">
                        
                        <div class="contenedor-funda">
                            <span class="etiqueta-nuevo" style="background-color: #ff3366;">OFERTA</span>
                            
                            <div class="contenedor-portada">
                                <img src="uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>" alt="Portada de <?php echo htmlspecialchars($row['titulo']); ?>" class="imagen-portada">
                            </div>
                            
                            <div class="disco-soporte <?php echo $disc_class; ?>">
                                <div class="etiqueta-disco" style="background-image: url('uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>'); background-size: cover; background-position: center;">
                                    <div class="centro-disco"></div>
                                </div>
                            </div>
                        </div>

                        <span class="artista-album"><?php echo htmlspecialchars($row['artista']); ?></span>
                        <h3 class="titulo-album" title="<?php echo htmlspecialchars($row['titulo']); ?>"><?php echo htmlspecialchars($row['titulo']); ?></h3>
                        <div>
                            <span class="insignia-formato <?php echo !$is_cd ? 'formato-vinilo' : ''; ?>"><?php echo $format_label; ?></span>
                        </div>

                        <div class="reproductor">
                            <audio controls controlsList="nodownload" preload="none">
                                <source src="uploads/demos/<?php echo htmlspecialchars($row['demo_url']); ?>" type="audio/mpeg">
                            </audio>
                        </div>

                        <div class="pie-tarjeta">
                            <div class="precios-oferta" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                <span class="precio-original" style="text-decoration: line-through; color: #888; font-size: 0.85em;">$<?php echo number_format($row['precio'], 0); ?> MXN</span>
                                <span class="etiqueta-precio" style="color: #ff3366; font-weight: bold; font-size: 1.1em; margin: 0;">$<?php echo number_format($precio_final, 0); ?> MXN</span>
                            </div>
                            
                            <form action="actions/agregar_carrito.php" method="POST" style="margin:0;">
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
                echo '<p style="width:100%; text-align:center;">No hay ofertas disponibles en este momento. ¡Vuelve pronto!</p>';
            }
            ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

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
