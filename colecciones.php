<?php
// verificación de errorse activo por seguridad
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Conexión PDO local idéntica a la que yaa funcionó
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
    die("<p style='color:red; background:white; padding:10px;'>Error de conexión local: " . $e->getMessage() . "</p>");
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
    
    <link rel="stylesheet" href="colecciones.css">
    
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
                            <img src="<?php echo $col['Portada_Colección']; ?>" alt="<?php echo $col['Nombre']; ?>">
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
                            <div style="width:100%; height:100%; background:#222; display:flex; align-items:center; justify-content:center; color:#cbbead; font-weight:bold; font-family:sans-serif;"></div>
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