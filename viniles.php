<?php include 'includes/db.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viniles - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-seccion">
        <?php
        $genero_id = isset($_GET['genero_id']) ? (int)$_GET['genero_id'] : 0;
        $coleccion_id = isset($_GET['coleccion_id']) ? (int)$_GET['coleccion_id'] : 0;

        $titulo_pagina = "Catalogo de Viniles";
        $query = "SELECT * FROM productos WHERE formato = 'Vinil'";
        $params = [];

        if ($genero_id > 0) {
            $stmt_g = $pdo->prepare("SELECT Nombre_Genero FROM genero_musical WHERE ID = ?");
            $stmt_g->execute([$genero_id]);
            $g_res = $stmt_g->fetch();
            if ($g_res) {
                $genero_nombre = $g_res['Nombre_Genero'];
                $titulo_pagina = "Viniles - Género: " . htmlspecialchars($genero_nombre);
                $query .= " AND (genero LIKE ? OR genero = ?)";
                $params[] = "%$genero_nombre%";
                $params[] = $genero_nombre;
            }
        } elseif ($coleccion_id > 0) {
            $stmt_c = $pdo->prepare("SELECT Nombre_Coleccion FROM colecciones WHERE ID = ?");
            $stmt_c->execute([$coleccion_id]);
            $c_res = $stmt_c->fetch();
            $coleccion_nombre = "";
            if ($c_res) {
                $coleccion_nombre = $c_res['Nombre_Coleccion'];
            } else {
                $respaldos = [
                    1 => 'Ediciones Limitadas',
                    2 => 'Los Más Vendidos',
                    3 => 'Descubrimientos',
                    4 => 'TOP 5',
                    5 => 'Clásicos',
                    6 => 'Poco Conocido'
                ];
                if (isset($respaldos[$coleccion_id])) {
                    $coleccion_nombre = $respaldos[$coleccion_id];
                }
            }

            if ($coleccion_nombre !== "") {
                $titulo_pagina = "Viniles - Colección: " . htmlspecialchars($coleccion_nombre);
                
                switch ($coleccion_id) {
                    case 1:
                        $query .= " AND stock > 0 AND stock <= 5";
                        break;
                    case 2:
                        $query .= " AND stock > 10";
                        break;
                    case 3:
                        $query .= " AND Fecha_Publicacion >= '2020-01-01'";
                        break;
                    case 4:
                        break;
                    case 5:
                        $query .= " AND Fecha_Publicacion < '2010-01-01' AND Fecha_Publicacion > '1900-01-01'";
                        break;
                    case 6:
                        $query .= " AND (stock = 2 OR genero = 'Ruido negro' OR genero = 'jazz')";
                        break;
                }
            }
        }

        if ($coleccion_id == 4) {
            $query .= " ORDER BY precio DESC LIMIT 5";
        } else {
            $query .= " ORDER BY fecha_agregado DESC";
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $resultados = $stmt->fetchAll();
        ?>

        <div class="cabecera-seccion">
            <h2><?php echo $titulo_pagina; ?></h2>
        </div>
        
        <div class="catalogo">
            <?php
            if (count($resultados) > 0) {
                foreach ($resultados as $row) {
                    $precio_final = $row['precio_oferta'] ? $row['precio_oferta'] : $row['precio'];
                    $disc_class = 'vinyl';
                    $format_label = 'VINYL';
            ?>
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
                            <span class="insignia-formato formato-vinilo"><?php echo $format_label; ?></span>
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
                echo '<p style="width:100%; text-align:center;">Aun no hay viniles disponibles.</p>';
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
