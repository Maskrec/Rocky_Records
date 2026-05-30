<?php include 'includes/db.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CD'S - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-seccion">
        <?php
        $genero_nombre = '';
        if (isset($_GET['genero_id'])) {
            $g_id = (int)$_GET['genero_id'];
            $stmt_g = $pdo->prepare("SELECT Nombre_Genero FROM genero_musical WHERE ID = ?");
            $stmt_g->execute([$g_id]);
            $g_res = $stmt_g->fetch();
            if ($g_res) {
                $genero_nombre = $g_res['Nombre_Genero'];
            }
        }

        $col_nombre = '';
        if (isset($_GET['coleccion_id'])) {
            $c_id = (int)$_GET['coleccion_id'];
            $stmt_c = $pdo->prepare("SELECT Nombre_Coleccion FROM colecciones WHERE ID = ?");
            $stmt_c->execute([$c_id]);
            $c_res = $stmt_c->fetch();
            if ($c_res) {
                $col_nombre = $c_res['Nombre_Coleccion'];
            }
        }
        ?>

        <div class="cabecera-seccion">
            <h2>
                <?php
                if ($genero_nombre !== '') {
                    echo "CD's de " . htmlspecialchars($genero_nombre);
                } elseif ($col_nombre !== '') {
                    echo "CD's: " . htmlspecialchars($col_nombre);
                } else {
                    echo "Catalogo de CD'S";
                }
                ?>
            </h2>
        </div>
        
        <div class="catalogo">
            <?php
            // Determinar la consulta según los filtros de género o colección
            if ($genero_nombre !== '') {
                $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'CD' AND (genero LIKE ? OR genero = ?) ORDER BY fecha_agregado DESC");
                $stmt->execute(["%$genero_nombre%", $genero_nombre]);
            } elseif (isset($_GET['coleccion_id'])) {
                $c_id = (int)$_GET['coleccion_id'];
                if ($c_id === 1) {
                    // Ediciones Limitadas: stock bajo (1 a 5 unidades)
                    $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'CD' AND stock > 0 AND stock <= 5 ORDER BY fecha_agregado DESC");
                } elseif ($c_id === 2) {
                    // Los Más Vendidos: stock alto o más de 10 unidades
                    $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'CD' AND stock > 10 ORDER BY fecha_agregado DESC");
                } elseif ($c_id === 3) {
                    // Descubrimientos: fecha de publicación reciente (desde 2020)
                    $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'CD' AND Fecha_Publicacion >= '2020-01-01' ORDER BY fecha_agregado DESC");
                } elseif ($c_id === 4) {
                    // TOP 5: los 5 más caros
                    $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'CD' ORDER BY precio DESC LIMIT 5");
                } elseif ($c_id === 5) {
                    // Clásicos: fecha de publicación anterior a 2010
                    $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'CD' AND Fecha_Publicacion < '2010-01-01' AND Fecha_Publicacion > '1900-01-01' ORDER BY fecha_agregado DESC");
                } elseif ($c_id === 6) {
                    // Poco Conocido: stock igual a 2 o géneros alternativos
                    $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'CD' AND (stock = 2 OR genero = 'Ruido negro' OR genero = 'jazz') ORDER BY fecha_agregado DESC");
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'CD' ORDER BY fecha_agregado DESC");
                }
                $stmt->execute();
            } else {
                $stmt = $pdo->prepare("SELECT * FROM productos WHERE formato = 'CD' ORDER BY fecha_agregado DESC");
                $stmt->execute();
            }
            $resultados = $stmt->fetchAll();

            if (count($resultados) > 0) {
                foreach ($resultados as $row) {
                    $precio_final = $row['precio_oferta'] ? $row['precio_oferta'] : $row['precio'];
                    $disc_class = 'cd';
                    $format_label = 'CD';
            ?>
                    <div class="tarjeta-producto" id="card-<?php echo $row['id']; ?>">
                        <div class="contenedor-funda">
                            <span class="etiqueta-nuevo">NUEVO</span>
                            
                            <a href="detalle.php?id=<?php echo $row['id']; ?>" class="enlace-detalle">
                                <div class="contenedor-portada">
                                    <img src="uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>" alt="Portada de <?php echo htmlspecialchars($row['titulo']); ?>" class="imagen-portada">
                                </div>
                            </a>
                            
                            <div class="disco-soporte <?php echo $disc_class; ?>">
                                <div class="etiqueta-disco" style="background-image: url('uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>'); background-size: cover; background-position: center;">
                                    <div class="centro-disco"></div>
                                </div>
                            </div>
                        </div>

                        <span class="artista-album"><?php echo htmlspecialchars($row['artista']); ?></span>
                        <h3 class="titulo-album" title="<?php echo htmlspecialchars($row['titulo']); ?>">
                            <a href="detalle.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                                <?php echo htmlspecialchars($row['titulo']); ?>
                            </a>
                        </h3>
                        <div>
                            <span class="insignia-formato"><?php echo $format_label; ?></span>
                        </div>

                        <div class="reproductor">
                            <audio controls controlsList="nodownload" preload="none">
                                <source src="uploads/demos/<?php echo htmlspecialchars($row['demo_url']); ?>" type="audio/mpeg">
                            </audio>
                        </div>

                        <div class="pie-tarjeta">
                            <span class="etiqueta-precio">$<?php echo number_format($precio_final, 0); ?> MXN</span>
                            
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
                echo '<p style="width:100%; text-align:center;">Aun no hay CD\'S disponibles.</p>';
            }
            ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- SECCION: SCRIPT DE AUDIO REPRODUCTOR -->
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
