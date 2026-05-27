<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rocky Records - La musica suena mejor en fisico</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <!-- Seccion Hero -->
    <section class="seccion-hero">
        <div class="contenedor-hero">
            <div class="contenido-hero">
                <h2>La musica <span class="destacado-hero">suena mejor</span> en fisico</h2>
                <p>Descubre nuestra cuidada seleccion de viniles y CD's para verdaderos amantes de la musica.</p>
                <a href="#catalogo" class="boton-explorar">EXPLORAR CATALOGO &nbsp;➔</a>
            </div>

            <div class="contenedor-imagen-hero">
                <img src="img/tocadiscos.png" alt="Tocadiscos retro tocando un vinilo" class="imagen-hero">
                <!-- Insignia Flotante -->
                <div class="insignia-envio">
                    <img src="img/svg/insignia-envio.svg" alt="Vinilo" class="icono-vinilo-insignia">
                    <span>Envios a<br>todo el pais</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Barra de Caracteristicas -->
    <section class="barra-caracteristicas">
        <div class="contenedor-caracteristicas">
            <div class="elemento-caracteristica">
                <img src="img/svg/caracteristica-vinilo.svg" alt="Vinilo" class="icono-caracteristica">
                <div class="texto-caracteristica">
                    <span class="titulo-caracteristica">EDICIONES EXCLUSIVAS</span>
                    <span class="subtitulo-caracteristica">Viniles de coleccion</span>
                </div>
            </div>

            <div class="elemento-caracteristica">
                <img src="img/svg/caracteristica-caja.svg" alt="Caja" class="icono-caracteristica">
                <div class="texto-caracteristica">
                    <span class="titulo-caracteristica">EMPAQUE SEGURO</span>
                    <span class="subtitulo-caracteristica">Cuidamos tu musica</span>
                </div>
            </div>

            <div class="elemento-caracteristica">
                <img src="img/svg/caracteristica-auriculares.svg" alt="Auriculares" class="icono-caracteristica">
                <div class="texto-caracteristica">
                    <span class="titulo-caracteristica">ATENCION PERSONALIZADA</span>
                    <span class="subtitulo-caracteristica">Asistencia en todo momento</span>
                </div>
            </div>

            <div class="elemento-caracteristica">
                <img src="img/svg/caracteristica-escudo.svg" alt="Escudo" class="icono-caracteristica">
                <div class="texto-caracteristica">
                    <span class="titulo-caracteristica">PAGOS SEGUROS</span>
                    <span class="subtitulo-caracteristica">Multiples metodos</span>
                </div>
            </div>

            <div class="elemento-caracteristica">
                <img src="img/svg/caracteristica-musica.svg" alt="Musica" class="icono-caracteristica">
                <div class="texto-caracteristica">
                    <span class="titulo-caracteristica">APOYA A LOS ARTISTAS</span>
                    <span class="subtitulo-caracteristica">Compra original</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido Principal del Catalogo -->
    <main class="contenedor-seccion" id="catalogo">
        <!-- Seccion de Nuevos Lanzamientos -->
        <section style="margin-bottom: 50px;">
            <div class="cabecera-seccion" id="nuevos-lanzamientos">
                <h2>NUEVOS LANZAMIENTOS</h2>
                <a href="viniles.php" class="enlace-ver-todos">VER TODOS &nbsp;➔</a>
            </div>

            <div class="catalogo">
                <?php
                // Obtener productos dinamicos de la base de datos
                $stmt = $pdo->query("SELECT * FROM productos ORDER BY fecha_agregado DESC");
                while ($row = $stmt->fetch()) {
                    $precio_final = $row['precio_oferta'] ? $row['precio_oferta'] : $row['precio'];
                    $is_cd = ($row['formato'] === 'CD');
                    $disc_class = $is_cd ? 'cd' : 'vinyl';
                    $format_label = $is_cd ? 'CD' : 'VINYL';
                    ?>
                    <div class="tarjeta-producto" id="card-<?php echo $row['id']; ?>">
                        <!-- Contenedor de Funda con portada y disco que se asoma -->
                        <div class="contenedor-funda">
                            <span class="etiqueta-nuevo">NUEVO</span>

                            <!-- Funda / Portada -->
                            <div class="contenedor-portada">
                                <img src="uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>"
                                    alt="Portada de <?php echo htmlspecialchars($row['titulo']); ?>" class="imagen-portada">
                            </div>

                            <!-- Disco Fisico (Vinilo o CD) -->
                            <div class="disco-soporte <?php echo $disc_class; ?>">
                                <div class="etiqueta-disco"
                                    style="background-image: url('uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>'); background-size: cover; background-position: center;">
                                    <div class="centro-disco"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles del Producto -->
                        <span class="artista-album"><?php echo htmlspecialchars($row['artista']); ?></span>
                        <h3 class="titulo-album" title="<?php echo htmlspecialchars($row['titulo']); ?>">
                            <?php echo htmlspecialchars($row['titulo']); ?></h3>
                        <div>
                            <span
                                class="insignia-formato <?php echo !$is_cd ? 'formato-vinilo' : ''; ?>"><?php echo $format_label; ?></span>
                        </div>

                        <!-- Mini Reproductor de Demostracion -->
                        <div class="reproductor">
                            <audio controls controlsList="nodownload" preload="none">
                                <source src="uploads/demos/<?php echo htmlspecialchars($row['demo_url']); ?>"
                                    type="audio/mpeg">
                            </audio>
                        </div>

                        <!-- Precio y Boton de Carrito -->
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
                <?php } ?>
            </div>
        </section>

    </main>

    <?php include 'footer.php'; ?>

    <!-- Script de Reproduccion  -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reproductores = document.querySelectorAll('.reproductor audio');

            reproductores.forEach(reproductor => {
                const tarjeta = reproductor.closest('.tarjeta-producto');

                reproductor.addEventListener('play', () => {
                    // Pausar todos los demas reproductores en la pagina
                    reproductores.forEach(otroReproductor => {
                        if (otroReproductor !== reproductor) {
                            otroReproductor.pause();
                        }
                    });

                    // Agregar clase de reproduccion a la tarjeta, activando el escalado de la portada y rotacion del disco
                    tarjeta.classList.add('reproduciendo');
                });

                reproductor.addEventListener('pause', () => {
                    // Remover clase de reproduccion, regresando el disco y deteniendo la rotacion
                    tarjeta.classList.remove('reproduciendo');
                });

                reproductor.addEventListener('ended', () => {
                    // Limpiar clase cuando termine la reproduccion
                    tarjeta.classList.remove('reproduciendo');
                });
            });
        });
    </script>
</body>

</html>