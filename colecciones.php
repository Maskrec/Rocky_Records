<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (file_exists('includes/db.php')) {
    include 'includes/db.php';
} elseif (file_exists('../includes/db.php')) {
    include '../includes/db.php';
} else {
    die("Error critico: No se encontro el archivo db.php en el proyecto.");
}

if (!isset($pdo)) {
    if (isset($conn)) { $pdo = $conn; }
    elseif (isset($conexion)) { $pdo = $conexion; }
    elseif (isset($db_connect)) { $pdo = $db_connect; }
}

$colecciones = [];
if (isset($pdo)) {
    try {
        $query = "SELECT `ID`, `Nombre_Coleccion`, `Portada_Coleccion`, `Descripcion_Coleccion` FROM `colecciones`";
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
    <title>Colecciones Especiales - Rocky Records</title>
    
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/colecciones.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <main class="colecciones-container">
        <h1 class="main-title">COLECCIONES ESPECIALES</h1>
        
        <section class="colecciones-grid">
            <?php 
            if (!empty($colecciones)) {
                foreach ($colecciones as $col) { 
            ?>
                    <a href="cds.php?coleccion_id=<?php echo $col['ID']; ?>" class="coleccion-card">
                        <div class="coleccion-img-wrapper">
                            <img src="<?php echo $col['Portada_Coleccion']; ?>" alt="<?php echo $col['Nombre_Coleccion']; ?>">
                        </div>
                        <div class="coleccion-info">
                            <h2><?php echo $col['Nombre_Coleccion']; ?></h2>
                            <p><?php echo $col['Descripcion_Coleccion']; ?></p>
                        </div>
                    </a>
            <?php 
                }
            } else {
                $respaldos = [
                    ['ID' => 1, 'Nombre' => 'Ediciones Limitadas', 'Descripcion' => 'Joyas musicales en formatos exclusivos y tirajes ultra recortados para coleccionistas.', 'Portada' => 'img/EdicionLimit.jpg'],
                    ['ID' => 2, 'Nombre' => 'Los Mas Vendidos', 'Descripcion' => 'Los discos que estan haciendo historia y no paran de sonar en los tocadiscos de todo el mundo.', 'Portada' => 'img/LosVendidos.jpg'],
                    ['ID' => 3, 'Nombre' => 'Descubrimientos', 'Descripcion' => 'Novedades para tu oido dentro de la industria musical.', 'Portada' => 'img/Descubrimientos.jpg'],
                    ['ID' => 4, 'Nombre' => 'TOP 5', 'Descripcion' => 'Lo mejor de los mejores 5 artistas en este momento.', 'Portada' => 'img/Top5.jpg'],
                    ['ID' => 5, 'Nombre' => 'Clasicos', 'Descripcion' => 'Para que no olvides la buena musica que dio lugar a lo nuevo.', 'Portada' => 'img/Clasicos.jpg'],
                    ['ID' => 6, 'Nombre' => 'Poco Conocido', 'Descripcion' => 'Una busqueda que termina con un encuentro con musica escondida de gran valor.', 'Portada' => 'img/PocoCono.jpg']
                ];

                foreach ($respaldos as $col) {
            ?>
                    <a href="cds.php?coleccion_id=<?php echo $col['ID']; ?>" class="coleccion-card">
                        <div class="coleccion-img-wrapper">
                            <img src="<?php echo $col['Portada']; ?>" alt="<?php echo $col['Nombre']; ?>">
                        </div>
                        <div class="coleccion-info">
                            <h2><?php echo $col['Nombre']; ?></h2>
                            <p><?php echo $col['Descripcion']; ?></p>
                        </div>
                    </a>
            <?php 
                }
            }
            ?>
        </section>
    </main>

    <?php 
    if (file_exists('includes/footer.php')) { include 'includes/footer.php'; } 
    ?>

</body>
</html>