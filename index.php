<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rocky Records - La musica suena mejor en fisico</title>
    <link rel="stylesheet" href="css/estilos.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include 'includes/header.php'; ?>

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
        <section style="margin-bottom: 50px; position: relative;">
            <div class="cabecera-seccion" id="nuevos-lanzamientos">
                <h2>NUEVOS LANZAMIENTOS</h2>
                <div class="controles-carrusel" style="display: flex; gap: 10px; align-items: center;">
                    <button class="btn-carrusel btn-carrusel-izq" onclick="moverCarrusel(-1)">&#10094;</button>
                    <button class="btn-carrusel btn-carrusel-der" onclick="moverCarrusel(1)">&#10095;</button>
                </div>
            </div>

            <div class="carrusel-contenedor">
                <div class="carrusel-fila" id="carrusel-lanzamientos">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM productos ORDER BY fecha_agregado DESC LIMIT 7");
                    while ($row = $stmt->fetch()) {
                        $precio_final = $row['precio_oferta'] ? $row['precio_oferta'] : $row['precio'];
                        $is_cd = ($row['formato'] === 'CD');
                        $disc_class = $is_cd ? 'cd' : 'vinyl';
                        $format_label = $is_cd ? 'CD' : 'VINYL';
                        ?>
                        <div class="tarjeta-producto" id="card-<?php echo $row['id']; ?>">
                            <div class="contenedor-funda">
                                <span class="etiqueta-nuevo">NUEVO</span>

                                <a href="detalle.php?id=<?php echo $row['id']; ?>" class="enlace-detalle">
                                    <div class="contenedor-portada">
                                        <img src="uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>"
                                            alt="Portada de <?php echo htmlspecialchars($row['titulo']); ?>" class="imagen-portada">
                                    </div>
                                </a>

                                <div class="disco-soporte <?php echo $disc_class; ?>">
                                    <div class="etiqueta-disco"
                                        style="background-image: url('uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>'); background-size: cover; background-position: center;">
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
                                <span
                                    class="insignia-formato <?php echo !$is_cd ? 'formato-vinilo' : ''; ?>"><?php echo $format_label; ?></span>
                            </div>

                            <div class="reproductor">
                                <audio controls controlsList="nodownload" preload="none">
                                    <source src="uploads/demos/<?php echo htmlspecialchars($row['demo_url']); ?>"
                                        type="audio/mpeg">
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
                    <?php } ?>
                </div>
            </div>
        </section>

    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- SECCION: SCRIPT DE AUDIO REPRODUCTOR Y CARRUSEL -->
    <script>
        let indexCarrusel = 0;
        function moverCarrusel(direccion) {
            const fila = document.getElementById('carrusel-lanzamientos');
            if (!fila) return;
            const totalTarjetas = fila.children.length;
            if (totalTarjetas === 0) return;
            
            const tarjetaWidth = fila.children[0].offsetWidth;
            const gap = 25;
            const paso = tarjetaWidth + gap;
            
            const contenedorWidth = fila.parentElement.offsetWidth;
            const totalWidth = (tarjetaWidth * totalTarjetas) + (gap * (totalTarjetas - 1));
            
            const maxScroll = totalWidth - contenedorWidth;
            if (maxScroll <= 0) {
                fila.style.transform = 'translateX(0px)';
                indexCarrusel = 0;
                return;
            }
            
            const maxPasos = Math.ceil(maxScroll / paso);
            
            indexCarrusel += direccion;
            if (indexCarrusel < 0) {
                indexCarrusel = maxPasos;
            } else if (indexCarrusel > maxPasos) {
                indexCarrusel = 0;
            }
            
            let offset = -indexCarrusel * paso;
            if (Math.abs(offset) > maxScroll) {
                offset = -maxScroll;
            }
            fila.style.transform = `translateX(${offset}px)`;
        }

        window.addEventListener('resize', () => {
            const fila = document.getElementById('carrusel-lanzamientos');
            if (fila) {
                fila.style.transform = 'translateX(0px)';
                indexCarrusel = 0;
            }
        });

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