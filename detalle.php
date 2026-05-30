<?php
include 'includes/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

if (!$producto) {
    header("Location: index.php");
    exit;
}

$precio_final = $producto['precio_oferta'] ? $producto['precio_oferta'] : $producto['precio'];
$is_cd = ($producto['formato'] === 'CD');
$disc_class = $is_cd ? 'cd' : 'vinyl';
$format_label = $is_cd ? 'CD' : 'VINYL';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['titulo']); ?> - Rocky Records</title>
    <!-- Cache busting version parameter for stylesheets -->
    <link rel="stylesheet" href="css/estilos.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/detalle.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-seccion">
        <div class="enlace-retorno">
            <a href="index.php" class="btn-regresar">➔ VOLVER AL INICIO</a>
        </div>

        <div class="detalle-album-grid">
            <!-- Columna Izquierda: Tocadiscos Realista -->
            <div class="columna-interactiva">
                <div class="tocadiscos-caja">
                    <div class="base-tocadiscos">
                        <div class="plato-giratorio">
                            <!-- El disco interactivo (CD o Vinilo) montado sobre el plato -->
                            <div class="disco-interactivo <?php echo $disc_class; ?>" id="disco-soporte">
                                <div class="etiqueta-disco"
                                    style="background-image: url('uploads/portadas/<?php echo htmlspecialchars($producto['imagen_url']); ?>'); background-size: cover; background-position: center;">
                                    <div class="centro-disco"></div>
                                </div>
                            </div>

                            <!-- El brazo metálico del tocadiscos en la esquina superior derecha -->
                            <div class="brazo-aguja" id="brazo-aguja">
                                <div class="pivote"></div>
                                <div class="cuerpo-brazo"></div>
                                <div class="cabezal-aguja"></div>
                            </div>
                        </div>

                        <!-- metronomo Analogico -->
                        <div class="vu-meter-retro">
                            <div class="vu-pantalla">
                                <div class="vu-escala">
                                    <div class="vu-tick"></div>
                                    <div class="vu-tick"></div>
                                    <div class="vu-tick"></div>
                                    <div class="vu-tick"></div>
                                    <div class="vu-tick"></div>
                                </div>
                                <div class="vu-aguja" id="vu-aguja-izq"></div>
                                <span class="vu-canal">L</span>
                            </div>
                            <div class="vu-pantalla">
                                <div class="vu-escala">
                                    <div class="vu-tick"></div>
                                    <div class="vu-tick"></div>
                                    <div class="vu-tick"></div>
                                    <div class="vu-tick"></div>
                                    <div class="vu-tick"></div>
                                </div>
                                <div class="vu-aguja" id="vu-aguja-der"></div>
                                <span class="vu-canal">R</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selector de Edición Especial -->
                <div class="selector-ediciones">
                    <span
                        class="selector-titulo"><?php echo $is_cd ? 'EDICIÓN DEL CD:' : 'EDICIÓN DEL VINILO:'; ?></span>
                    <div class="opciones-color">
                        <?php if ($is_cd): ?>
                            <button type="button" class="swatch cd-silver activo" data-color="silver"
                                title="Plata Estándar"></button>
                            <button type="button" class="swatch cd-gold" data-color="gold"
                                title="Oro de Colección"></button>
                            <button type="button" class="swatch cd-blue" data-color="blue" title="Azul Metálico"></button>
                            <button type="button" class="swatch cd-purple" data-color="purple"
                                title="Púrpura Edición Especial"></button>
                        <?php else: ?>
                            <button type="button" class="swatch vinyl-black activo" data-color="black"
                                title="Negro Clásico"></button>
                            <button type="button" class="swatch vinyl-orange" data-color="orange"
                                title="Naranja Translúcido"></button>
                            <button type="button" class="swatch vinyl-blue" data-color="blue"
                                title="Azul Eléctrico"></button>
                            <button type="button" class="swatch vinyl-red" data-color="red" title="Rojo Rubí"></button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Reproductor del Demo -->
                <?php if ($producto['demo_url']): ?>
                    <div class="reproductor-estilizado">
                        <h4>Preview del Álbum</h4>
                        <div class="controles-reproductor">
                            <button class="btn-play-pause" id="btn-play-pause" type="button">
                                <span class="icono-play">▶</span>
                                <span class="icono-pause" style="display:none;">❚❚</span>
                            </button>
                            <div class="progreso-barra">
                                <div class="progreso-relleno" id="progreso-relleno"></div>
                            </div>
                            <span class="tiempo-reproduccion" id="tiempo-reproduccion">0:00</span>
                        </div>
                        <audio id="audio-demo" preload="none">
                            <source src="uploads/demos/<?php echo htmlspecialchars($producto['demo_url']); ?>"
                                type="audio/mpeg">
                        </audio>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Columna Derecha Estuche Abierto (Portada  Detalles  Lista de canciones) -->
            <div class="columna-informacion">
                <div class="tarjeta-informacion">
                    <div class="estuche-layout">
                        <!-- Portada (Lado Izquierdo del Estuche Abierto) -->
                        <div class="estuche-portada">
                            <img src="uploads/portadas/<?php echo htmlspecialchars($producto['imagen_url']); ?>"
                                alt="Portada del álbum">
                        </div>


                        <div class="estuche-detalles">
                            <span
                                class="detalle-formato <?php echo !$is_cd ? 'formato-vinilo' : ''; ?>"><?php echo $format_label; ?></span>
                            <h1 class="detalle-titulo"><?php echo htmlspecialchars($producto['titulo']); ?></h1>
                            <h2 class="detalle-artista"><?php echo htmlspecialchars($producto['artista']); ?></h2>

                            <div class="detalle-precio-box">
                                <?php if ($producto['precio_oferta']): ?>
                                    <span class="precio-oferta-val">$<?php echo number_format($precio_final, 0); ?>
                                        MXN</span>
                                    <del class="precio-original-val">$<?php echo number_format($producto['precio'], 0); ?>
                                        MXN</del>
                                <?php else: ?>
                                    <span class="precio-val">$<?php echo number_format($producto['precio'], 0); ?>
                                        MXN</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="detalle-meta">
                        <p><strong>Género:</strong> <?php echo htmlspecialchars($producto['genero']); ?></p>
                        <?php if ($producto['Fecha_Publicacion']): ?>
                            <p><strong>Lanzamiento:</strong>
                                <?php echo date('d/m/Y', strtotime($producto['Fecha_Publicacion'])); ?></p>
                        <?php endif; ?>
                        <p><strong>Disponibilidad:</strong>
                            <?php echo $producto['stock'] > 0 ? '<span class="en-stock">En Stock (' . $producto['stock'] . ' unidades)</span>' : '<span class="sin-stock">Agotado</span>'; ?>
                        </p>
                    </div>

                    <?php if ($producto['descripcion']): ?>
                        <div class="detalle-descripcion">
                            <h3>Descripción</h3>
                            <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
                        </div>
                    <?php endif; ?>


                    <div class="detalle-tracklist">
                        <h3>Lista de Canciones</h3>
                        <?php if (!empty($producto['detalles'])): ?>
                            <ul class="canciones-lista">
                                <?php
                                $canciones = explode("\n", $producto['detalles']);
                                foreach ($canciones as $cancion) {
                                    $cancion = trim($cancion);
                                    if ($cancion !== '') {
                                        echo '<li>' . htmlspecialchars($cancion) . '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        <?php else: ?>
                            <p class="sin-canciones">No hay lista de canciones disponible para este álbum.</p>
                        <?php endif; ?>
                    </div>



                    <?php if ($producto['stock'] > 0): ?>
                        <form action="actions/agregar_carrito.php" method="POST" class="form-compra">
                            <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                            <div class="cantidad-selector">
                                <label for="cantidad">Cantidad:</label>
                                <input type="number" id="cantidad" name="cantidad" value="1" min="1"
                                    max="<?php echo $producto['stock']; ?>" class="input-cantidad">
                            </div>
                            <button type="submit" class="btn-agregar-detalle">
                                AGREGAR AL CARRITO
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn-agregar-detalle btn-agotado" disabled>AGOTADO</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const audio = document.getElementById('audio-demo');
            const playPauseBtn = document.getElementById('btn-play-pause');
            const progresoRelleno = document.getElementById('progreso-relleno');
            const tiempoRep = document.getElementById('tiempo-reproduccion');
            const brazo = document.getElementById('brazo-aguja');
            const disco = document.getElementById('disco-soporte');

            // Nodos del VU Meter
            const needleIzq = document.getElementById('vu-aguja-izq');
            const needleDer = document.getElementById('vu-aguja-der');
            let audioCtx = null;
            let analyser = null;
            let source = null;
            let dataArray = null;
            let animationFrameId = null;

            function initAnalyser() {
                try {
                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                    audioCtx = new AudioContext();
                    analyser = audioCtx.createAnalyser();
                    analyser.fftSize = 64; // Pequeño para respuesta ágil y rápida
                    source = audioCtx.createMediaElementSource(audio);
                    source.connect(analyser);
                    analyser.connect(audioCtx.destination);
                    dataArray = new Uint8Array(analyser.frequencyBinCount);
                } catch (e) {
                    console.log("Web Audio API bloqueado o no soportado (ej. CORS local). Usando simulación de VU.");
                }
            }

            function updateVUMeter() {
                if (audio && !audio.paused && !audio.ended) {
                    let volL = 0;
                    let volR = 0;

                    if (analyser && dataArray) {
                        analyser.getByteFrequencyData(dataArray);
                        let sum = 0;
                        for (let i = 0; i < dataArray.length; i++) {
                            sum += dataArray[i];
                        }
                        const avg = sum / dataArray.length;
                        volL = (avg / 255) * 1.2;
                        volR = (avg / 255) * 1.0 + Math.random() * 0.15; // Ligeras diferencias para efecto estéreo
                    } else {
                        // Simulación matemática muy realista con ruido armónico
                        const t = Date.now();
                        volL = 0.35 + Math.sin(t / 90) * 0.2 + Math.random() * 0.3;
                        volR = 0.35 + Math.sin(t / 110) * 0.18 + Math.random() * 0.3;
                    }

                    // Mapear a ángulos de rotación de la aguja (-45deg a 40deg)
                    const angleL = -45 + (volL * 85);
                    const angleR = -45 + (volR * 85);

                    // Limitar rotación física
                    const clampedL = Math.max(-45, Math.min(40, angleL));
                    const clampedR = Math.max(-45, Math.min(40, angleR));

                    if (needleIzq) needleIzq.style.transform = `rotate(${clampedL}deg)`;
                    if (needleDer) needleDer.style.transform = `rotate(${clampedR}deg)`;

                    animationFrameId = requestAnimationFrame(updateVUMeter);
                } else {
                    resetNeedles();
                }
            }

            function resetNeedles() {
                if (needleIzq) needleIzq.style.transform = 'rotate(-45deg)';
                if (needleDer) needleDer.style.transform = 'rotate(-45deg)';
            }

            // Lógica del Selector de Edición Especial
            const swatches = document.querySelectorAll('.swatch');
            if (swatches.length > 0 && disco) {
                swatches.forEach(swatch => {
                    swatch.addEventListener('click', () => {
                        swatches.forEach(s => s.classList.remove('activo'));
                        swatch.classList.add('activo');

                        const color = swatch.dataset.color;

                        // Limpiar clases de color previas
                        disco.classList.remove(
                            'color-orange', 'color-blue', 'color-red',
                            'color-gold', 'color-purple'
                        );

                        // Añadir la nueva clase si no es la estándar (black o silver)
                        if (color !== 'black' && color !== 'silver') {
                            disco.classList.add(`color-${color}`);
                        }
                    });
                });
            }

            if (audio && playPauseBtn) {
                const playIcon = playPauseBtn.querySelector('.icono-play');
                const pauseIcon = playPauseBtn.querySelector('.icono-pause');

                playPauseBtn.addEventListener('click', () => {
                    // Inicializar el analizador de audio en la primera interacción del usuario
                    if (!audioCtx) {
                        initAnalyser();
                    }
                    if (audioCtx && audioCtx.state === 'suspended') {
                        audioCtx.resume();
                    }

                    if (audio.paused) {
                        audio.play();
                    } else {
                        audio.pause();
                    }
                });

                audio.addEventListener('play', () => {
                    playIcon.style.display = 'none';
                    pauseIcon.style.display = 'inline';

                    brazo.classList.add('brazo-reproduciendo');
                    disco.classList.add('girando-reproduccion');

                    // Iniciar animación del VU Meter
                    updateVUMeter();
                });

                audio.addEventListener('pause', () => {
                    playIcon.style.display = 'inline';
                    pauseIcon.style.display = 'none';

                    brazo.classList.remove('brazo-reproduciendo');
                    disco.classList.remove('girando-reproduccion');

                    if (animationFrameId) {
                        cancelAnimationFrame(animationFrameId);
                    }
                    resetNeedles();
                });

                audio.addEventListener('ended', () => {
                    playIcon.style.display = 'inline';
                    pauseIcon.style.display = 'none';

                    brazo.classList.remove('brazo-reproduciendo');
                    disco.classList.remove('girando-reproduccion');
                    progresoRelleno.style.width = '0%';

                    if (animationFrameId) {
                        cancelAnimationFrame(animationFrameId);
                    }
                    resetNeedles();
                });

                audio.addEventListener('timeupdate', () => {
                    const pct = (audio.currentTime / audio.duration) * 100;
                    progresoRelleno.style.width = `${pct}%`;

                    const mins = Math.floor(audio.currentTime / 60);
                    const secs = Math.floor(audio.currentTime % 60);
                    tiempoRep.textContent = `${mins}:${secs < 10 ? '0' : ''}${secs}`;
                });

                const progresoBarra = document.querySelector('.progreso-barra');
                progresoBarra.addEventListener('click', (e) => {
                    const rect = progresoBarra.getBoundingClientRect();
                    const clickX = e.clientX - rect.left;
                    const width = rect.width;
                    const pct = clickX / width;
                    audio.currentTime = pct * audio.duration;
                });
            }
        });
    </script>
</body>

</html>