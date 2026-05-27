<?php include 'db.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rocky Records - La música suena mejor en físico</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Sección Hero -->
    <section class="seccion-hero">
        <div class="contenedor-hero">
            <div class="contenido-hero">
                <h2>La música <span class="destacado-hero">suena mejor</span> en físico</h2>
                <p>Descubre nuestra cuidada selección de viniles y CD's para verdaderos amantes de la música.</p>
                <a href="#catalogo" class="boton-explorar">EXPLORAR CATÁLOGO &nbsp;➔</a>
            </div>
            
            <div class="contenedor-imagen-hero">
                <img src="img/tocadiscos.png" alt="Tocadiscos retro tocando un vinilo" class="imagen-hero">
                <!-- Insignia Flotante -->
                <div class="insignia-envio">
                    <img src="img/svg/insignia-envio.svg" alt="Vinilo" class="icono-vinilo-insignia">
                    <span>Envíos a<br>todo el país</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Barra de Características -->
    <section class="barra-caracteristicas">
        <div class="contenedor-caracteristicas">
            <div class="elemento-caracteristica">
                <img src="img/svg/caracteristica-vinilo.svg" alt="Vinilo" class="icono-caracteristica">
                <div class="texto-caracteristica">
                    <span class="titulo-caracteristica">EDICIONES EXCLUSIVAS</span>
                    <span class="subtitulo-caracteristica">Viniles de colección</span>
                </div>
            </div>
            
            <div class="elemento-caracteristica">
                <img src="img/svg/caracteristica-caja.svg" alt="Caja" class="icono-caracteristica">
                <div class="texto-caracteristica">
                    <span class="titulo-caracteristica">EMPAQUE SEGURO</span>
                    <span class="subtitulo-caracteristica">Cuidamos tu música</span>
                </div>
            </div>
            
            <div class="elemento-caracteristica">
                <svg class="icono-caracteristica" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                </svg>
                <img src="img/svg/caracteristica-auriculares.svg" alt="Auriculares" class="icono-caracteristica">
            </div>
            
            <div class="elemento-caracteristica">
                <svg class="icono-caracteristica" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <div class="texto-caracteristica">
                    <span class="titulo-caracteristica">PAGOS SEGUROS</span>
                    <span class="subtitulo-caracteristica">Múltiples métodos</span>
                </div>
            </divimg src="img/svg/caracteristica-escudo.svg" alt="Escudo" class="icono-caracteristica"s="elemento-caracteristica">
                <svg class="icono-caracteristica" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18V5l12-2v13"></path>
                    <circle cx="6" cy="18" r="3"></circle>
                    <circle cx="18" cy="16" r="3"></circle>
                </svg>
                <div class="texto-caracteristica">
                    <span class="titulo-caracteristica">APOYA A LOS ARTISTAS</span>
                    <span class="subtitulo-caracteristica">Compra original</span>
                </div>
            </divimg src="img/svg/caracteristica-musica.svg" alt="Música" class="icono-caracteristica"rincipal del Catálogo -->
    <main class="contenedor-seccion" id="catalogo">
        <!-- Sección de Nuevos Lanzamientos -->
        <section style="margin-bottom: 50px;">
            <div class="cabecera-seccion" id="nuevos-lanzamientos">
                <h2>NUEVOS LANZAMIENTOS</h2>
                <a href="viniles.php" class="enlace-ver-todos">VER TODOS &nbsp;➔</a>
            </div>
            
            <div class="catalogo">
                <?php
                // Obtener productos dinámicos de la base de datos
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
                                <img src="uploads/portadas/<?php echo htmlspecialchars($row['imagen_url']); ?>" alt="Portada de <?php echo htmlspecialchars($row['titulo']); ?>" class="imagen-portada">
                            </div>
                            
                            <!-- Disco Físico (Vinilo o CD) -->
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
                            <span class="insignia-formato <?php echo !$is_cd ? 'formato-vinilo' : ''; ?>"><?php echo $format_label; ?></span>
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
                <?php } ?>
            </div>
        </section>

        <!-- Sección de Exploración por Géneros -->
        <section>
            <div class="cabecera-seccion" id="generos">
                <h2>EXPLORA POR GÉNEROS</h2>
                <a href="viniles.php" class="enlace-ver-todos">VER TODOS &nbsp;➔</a>
            </div>
            
            <div class="cuadricula-generos">
                <a href="buscar.php?q=Rock" class="tarjeta-genero rock">
                    <span>ROCK</span>
                </a>
                <a href="buscar.php?q=Indie" class="tarjeta-genero indie">
                    <span>INDIE</span>
                </a>
                <a href="buscar.php?q=Jazz" class="tarjeta-genero jazz">
                    <span>JAZZ</span>
                </a>
                <a href="buscar.php?q=Hip+Hop" class="tarjeta-genero hiphop">
                    <span>HIP HOP</span>
                </a>
                <a href="buscar.php?q=Pop" class="tarjeta-genero pop">
                    <span>POP</span>
                </a>
                <a href="buscar.php?q=Metal" class="tarjeta-genero metal">
                    <span>METAL</span>
                </a>
                <a href="buscar.php?q=Electronica" class="tarjeta-genero electronica">
                    <span>ELECTRÓNICA</span>
                </a>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Script de Reproducción Interactiva de Audio -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reproductores = document.querySelectorAll('.reproductor audio');
            
            reproductores.forEach(reproductor => {
                const tarjeta = reproductor.closest('.tarjeta-producto');
                
                reproductor.addEventListener('play', () => {
                    // Pausar todos los demás reproductores en la página
                    reproductores.forEach(otroReproductor => {
                        if (otroReproductor !== reproductor) {
                            otroReproductor.pause();
                        }
                    });
                    
                    // Agregar clase de reproducción a la tarjeta, activando el escalado de la portada y rotación del disco
                    tarjeta.classList.add('reproduciendo');
                });
                
                reproductor.addEventListener('pause', () => {
                    // Remover clase de reproducción, regresando el disco y deteniendo la rotación
                    tarjeta.classList.remove('reproduciendo');
                });
                
                reproductor.addEventListener('ended', () => {
                    // Limpiar clase cuando termine la reproducción
                    tarjeta.classList.remove('reproduciendo');
                });
            });
        });
    </script>
</body>
</html>