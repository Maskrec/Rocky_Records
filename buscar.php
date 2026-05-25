<?php include 'db.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de búsqueda - Groove Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="contenedor-seccion">
        <div class="cabecera-seccion">
            <h2>
                <?php 
                $q = isset($_GET['q']) ? $_GET['q'] : '';
                if ($q !== '') {
                    echo 'Resultados de búsqueda para: "' . htmlspecialchars($q) . '"';
                } else {
                    echo 'Búsqueda';
                }
                ?>
            </h2>
        </div>
        
        <div class="catalogo">
            <?php
            if ($q !== '') {
                // Buscar por título, artista o género
                $stmt = $pdo->prepare("SELECT * FROM productos WHERE titulo LIKE ? OR artista LIKE ? OR genero LIKE ? ORDER BY fecha_agregado DESC");
                $like_q = "%$q%";
                $stmt->execute([$like_q, $like_q, $like_q]);
                $resultados = $stmt->fetchAll();

                if (count($resultados) > 0) {
                    foreach ($resultados as $row) {
                        $precio_final = $row['precio_oferta'] ? $row['precio_oferta'] : $row['precio'];
                        $is_cd = ($row['formato'] === 'CD');
                        $disc_class = $is_cd ? 'cd' : 'vinyl';
                        $format_label = $is_cd ? 'CD' : 'VINYL'; ?>
                        <div class="tarjeta-producto" id="card-<?php echo $row['id']; ?>">
                            
                            <div class="contenedor-funda">
                                <span class="etiqueta-nuevo">NUEVO</span>
                                
                               
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
                                <span class="etiqueta-precio">$<?php echo number_format($precio_final, 0); ?> MXN</span>
                                
                                <form action="agregar_carrito.php" method="POST" style="margin:0;">
                                    <input type="hidden" name="id_producto" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="cantidad" value="1">
                                    <button type="submit" class="boton-agregar-carrito" title="Agregar al carrito">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="21" r="1"></circle>
                                            <circle cx="20" cy="21" r="1"></circle>
                                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
            <?php 
                    }
                } else {
                    echo '<p style="width:100%; text-align:center;">No se encontraron resultados para la búsqueda.</p>';
                }
            } else {
                echo '<p style="width:100%; text-align:center;">Por favor, ingresa un término de búsqueda válido.</p>';
            }
            ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>

   
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
