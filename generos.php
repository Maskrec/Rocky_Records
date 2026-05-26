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
    <title>Rocky Records-Géneros Musicales</title>
    <link rel="stylesheet" href="generos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            // 4. Recorremos los resultados que trajimos de phpMyAdmin
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