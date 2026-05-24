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
    <title>Groove Records - Colecciones</title>
    <link rel="stylesheet" href="colecciones.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
</head>
<body>

    <header class="navbar">
        <div class="logo">Groove Records</div>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="viniles.php">Viniles</a>
            <a href="cds.php">CD's</a>
            <a href="generos.php">Géneros</a>
            <a href="colecciones.php" class="active">Colecciones</a>
        </nav>
        <div class="nav-icons">
            <i class="fas fa-search"></i>
            <i class="fas fa-user"></i>
            <i class="fas fa-shopping-cart"></i>
        </div>
    </header>

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