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