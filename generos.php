<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (file_exists('db.php')) { include 'db.php'; }

// Forzamos la conexión PDO local con tus datos exactos de phpMyAdmin
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

$generos = [];
if (isset($pdo)) {
    try {
        $query = "SELECT `ID`, `Nombre_Género`, `Descripción`, `Portada` FROM `género_músical`";
        $stmt = $pdo->query($query); 
        $generos = $stmt->fetchAll();
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
    <title>Groove Records - Géneros Musicales</title>
    
    <link rel="stylesheet" href="css/estilos.css">     
    
    <link rel="stylesheet" href="generos.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="generos-container">
        <h1 class="main-title">GÉNEROS MUSICALES</h1>
        
        <section class="generos-grid">
            <?php 
            if (!empty($generos)) {
                foreach ($generos as $genero) { 
            ?>
                    <a href="cds.php?genero_id=<?php echo $genero['ID']; ?>" class="genero-card">
                        <div class="genero-img-wrapper">
                            <img src="<?php echo $genero['Portada']; ?>" alt="<?php echo $genero['Nombre_Género']; ?>">
                        </div>
                        <div class="genero-info">
                            <h2><?php echo $genero['Nombre_Género']; ?></h2>
                            <p><?php echo $genero['Descripción']; ?></p>
                        </div>
                    </a>
            <?php 
                } // Fin del foreach
            } else {
                // Mensaje en caso de que la tabla esté vacía o no exista todavía
                echo "<p class='error-msg'>No se encontraron géneros musicales en la base de datos o la tabla no está lista.</p>";
                if (isset($error_tabla)) {
                    echo "<p style='color:#e74c3c; font-size:0.9rem; text-align:center; grid-column: 1 / -1;'>Detalle: " . $error_tabla . "</p>";
                }
            }
            ?>
        </section>
    </main>

    <?php 
    if (file_exists('footer.php')) { include 'footer.php'; } 
    ?>

</body>
</html>
<?php
// 1. Buscamos de forma segura el archivo db.php en la raíz del proyecto
if (file_exists('db.php')) {
    include 'db.php';
} elseif (file_exists('../db.php')) {
    include '../db.php';
} else {
    die("Error crítico: No se encontró el archivo db.php en el proyecto.");
}

// 2. Verificamos si la variable $pdo existe tras el include, si no, intentamos rescatarla
if (!isset($pdo)) {
    // Si tus compañeros metieron la conexión dentro de una función o clase,
    // o si el archivo db.php usa otra variable global, creamos un respaldo aquí.
    if (isset($conn)) { $pdo = $conn; }
    elseif (isset($conexion)) { $pdo = $conexion; }
    elseif (isset($db_connect)) { $pdo = $db_connect; }
}

// 3. Hacemos la consulta usando el formato PDO seguro
$generos = []; // Inicializamos vacío por seguridad
if (isset($pdo) && $pdo !== null) {
    try {
        $query = "SELECT ID, Nombre_Género, Descripción, Portada FROM género_músical";
        $stmt = $pdo->query($query); 
        $generos = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "<p style='color:red; background:white; padding:10px;'>Error en la consulta SQL: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:orange; background:white; padding:10px;'>Aviso: El archivo db.php se cargó, pero la variable de conexión \$pdo sigue vacía o inaccesible.</p>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géneros Musicales - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="generos.css">
</head>
<body>

    <?php 
    // Insertamos el menú de navegación (si existe en la raíz)
    if (file_exists('header.php')) { include 'header.php'; } 
    ?>

    <main class="generos-container">
        <h1 class="main-title">GÉNEROS MUSICALES</h1>
        
        <section class="generos-grid">
            <?php 
            // 4. Recorremos los resultados que trajsimos de phpMyAdmin
            if (!empty($generos)):
                foreach ($generos as $genero): 
            ?>
                    <a href="cds.php?genero_id=<?php echo $genero['ID']; ?>" class="genero-card">
                        <div class="genero-img-wrapper">
                            <img src="<?php echo $genero['Portada']; ?>" alt="<?php echo $genero['Nombre_Género']; ?>">
                        </div>
                        <div class="genero-info">
                            <h2><?php echo $genero['Nombre_Género']; ?></h2>
                            <p><?php echo $genero['Descripción']; ?></p>
                        </div>
                    </a>
            <?php 
                endforeach; 
            else:
                echo "<p class='error-msg'>No se pudieron cargar los géneros. Verifica que la tabla 'género_músical' tenga datos y que el nombre de la Base de Datos coincida.</p>";
            endif;
            ?>
        </section>
    </main>

    <?php 
    // Insertamos el pie de página (si existe en la raíz)
    if (file_exists('footer.php')) { include 'footer.php'; } 
    ?>

</body>
</html>