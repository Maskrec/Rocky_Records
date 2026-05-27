<?php
if (file_exists('db.php')) {
    include 'db.php';
} elseif (file_exists('../db.php')) {
    include '../db.php';
} else {
    die("Error critico: No se encontro el archivo db.php en el proyecto.");
}

if (!isset($pdo)) {
    if (isset($conn)) { $pdo = $conn; }
    elseif (isset($conexion)) { $pdo = $conexion; }
    elseif (isset($db_connect)) { $pdo = $db_connect; }
}

$generos = [];
if (isset($pdo) && $pdo !== null) {
    try {
        $query = "SELECT ID, Nombre_Genero, Descripcion, Portada FROM genero_musical";
        $stmt = $pdo->query($query); 
        $generos = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "<p style='color:red; background:white; padding:10px;'>Error en la consulta SQL: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:orange; background:white; padding:10px;'>Aviso: El archivo db.php se cargo, pero la variable de conexion \$pdo sigue vacia o inaccesible.</p>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generos Musicales - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="generos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php 
    if (file_exists('header.php')) { include 'header.php'; } 
    ?>

    <main class="generos-container">
        <h1 class="main-title">GENEROS MUSICALES</h1>
        
        <section class="generos-grid">
            <?php 
            if (!empty($generos)):
                foreach ($generos as $genero): 
            ?>
                    <a href="cds.php?genero_id=<?php echo $genero['ID']; ?>" class="genero-card">
                        <div class="genero-img-wrapper">
                            <img src="<?php echo $genero['Portada']; ?>" alt="<?php echo $genero['Nombre_Genero']; ?>">
                        </div>
                        <div class="genero-info">
                            <h2><?php echo $genero['Nombre_Genero']; ?></h2>
                            <p><?php echo $genero['Descripcion']; ?></p>
                        </div>
                    </a>
            <?php 
                endforeach; 
            else:
                echo "<p class='error-msg'>No se pudieron cargar los generos. Verifica que la tabla 'genero_musical' tenga datos y que el nombre de la Base de Datos coincida.</p>";
            endif;
            ?>
        </section>
    </main>

    <?php 
    if (file_exists('footer.php')) { include 'footer.php'; } 
    ?>

</body>
</html>