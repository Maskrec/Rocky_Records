<?php
// verificación de errores activa por seguridad
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión PDO local idéntica a la que ya funcionó
$host = 'localhost';
$db   = 'Rocky_Records'; 
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Silenciamos el mensaje tosco para que no rompa visualmente el diseño
    $error_conexion = $e->getMessage();
}

// Consulta de Colecciones
$colecciones = [];
if (isset($pdo)) {
    try {
        $query = "SELECT `ID`, `Nombre_Colección`, `Portada_Colección`, `Descripción_Colección` FROM `colecciones`";
        $stmt = $pdo->query($query); 
        $colecciones = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error_tabla = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groove Records - Colecciones Especiales</title>
    
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/colecciones.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="colecciones-container">
        <h1 class="main-title">COLECCIONES ESPECIALES</h1>
        
        <section class="colecciones-grid">
            <?php 
            if (!empty($colecciones)) {
                foreach ($colecciones as $col) { 
            ?>
                    <a href="cds.php?coleccion_id=<?php echo $col['ID']; ?>" class="coleccion-card">
                        <div class="coleccion-img-wrapper">
                            <img src="<?php echo $col['Portada_Colección']; ?>" alt="<?php echo $col['Nombre_Colección']; ?>">
                        </div>
                        <div class="coleccion-info">
                            <h2><?php echo $col['Nombre_Colección']; ?></h2>
                            <p><?php echo $col['Descripción_Colección']; ?></p>
                        </div>
                    </a>
            <?php 
                } // Fin del foreach
            } else {
                $respaldos = [
                    ['ID' => 1, 'Nombre' => 'Ediciones Limitadas', 'Descripción' => 'Joyas musicales en formatos exclusivos y tirajes ultra recortados para coleccionistas.', 'Portada' => 'img/EdicionLimit.jpg'],
                    ['ID' => 2, 'Nombre' => 'Los Más Vendidos', 'Descripción' => 'Los discos que están haciendo historia y no paran de sonar en los tocadiscos de todo el mundo.', 'Portada' => 'img/LosVendidos.jpg'],
                    ['ID' => 3, 'Nombre' => 'Descubrimientos', 'Descripción' => 'Novedades para tu oído dentro de la industria musical.', 'Portada' => 'img/Descubrimientos.jpg'],
                    ['ID' => 4, 'Nombre' => 'TOP 5', 'Descripción' => 'Lo mejor de los mejores 5 artistas en este momento.', 'Portada' => 'img/Top5.jpg'],
                    ['ID' => 5, 'Nombre' => 'Clásicos', 'Descripción' => 'Para que no olvides la buena música que dió lugar a lo nuevo.', 'Portada' => 'img/Clásicos.jpg'],
                    ['ID' => 6, 'Nombre' => 'Poco Conocido', 'Descripción' => 'Una búsqueda que termina con un encuentro con música escondida de gran valor.', 'Portada' => 'img/PocoCono.jpg']
                ];

                foreach ($respaldos as $col) {
            ?>
                    <a href="cds.php?coleccion_id=<?php echo $col['ID']; ?>" class="coleccion-card">
                        <div class="coleccion-img-wrapper">
                            <img src="<?php echo $col['Portada']; ?>" alt="<?php echo $col['Nombre']; ?>">
                        </div>
                        <div class="coleccion-info">
                            <h2><?php echo $col['Nombre']; ?></h2>
                            <p><?php echo $col['Descripción']; ?></p>
                        </div>
                    </a>
            <?php 
                } // Fin del foreach de respaldo
            } // Fin del else
            ?>
        </section>
    </main>

    <?php 
    if (file_exists('footer.php')) { include 'footer.php'; } 
    ?>

</body>
</html>
<?php
// Arreglo dinámico con los datos de las colecciones (idéntico a tu diseño)
// Cada colección tiene un color de fondo único en formato Hexadecimal (bg_color)
$colecciones = [
    [
        'titulo' => 'CLÁSICOS ESENCIALES',
        'descripcion' => 'Discos icónicos que no pueden faltar en tu colección física. Desde los Beatles hasta Pink Floyd, la verdadera historia de la música.',
        'imagen' => 'img/colecciones/clasicos.png',
        'bg_color' => '#5c2c16' // Marrón / Terracota
    ],
    [
        'titulo' => 'INDIE MODERNO',
        'descripcion' => 'Descubre los sonidos alternativos que están definiendo a nuestra generación actual. Bandas independientes, ediciones raras y joyas ocultas.',
        'imagen' => 'img/colecciones/indie.png',
        'bg_color' => '#2b3a2a' // Verde Oliva / Oscuro
    ],
    [
        'titulo' => 'EDICIONES LIMITADAS',
        'descripcion' => 'Joyas de colección con discos de colores, portadas alternativas y material exclusivo que solo unos pocos tendrán la suerte de poseer.',
        'imagen' => 'img/colecciones/limitadas.png',
        'bg_color' => '#8e712a' // Ocre / Mostaza vintage
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colecciones - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="colecciones.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="colecciones-container">
        <h1 class="main-title">COLECCIONES</h1>

        <section class="colecciones-list">
            <?php 
            // Aplicamos nuestro truco del foreach para recorrer las colecciones
            foreach ($colecciones as $coleccion): 
            ?>
                <div class="coleccion-row" style="background-color: <?php echo $coleccion['bg_color']; ?>;">
                    
                    <div class="coleccion-info">
                        <h2><?php echo $coleccion['titulo']; ?></h2>
                        <p><?php echo $coleccion['descripcion']; ?></p>
                    </div>

                    <div class="coleccion-media">
                        <img src="<?php echo $coleccion['imagen']; ?>" alt="<?php echo $coleccion['titulo']; ?>">
                    </div>

                </div>
            <?php endforeach; ?>
        </section>
    </main>

</body>
</html>