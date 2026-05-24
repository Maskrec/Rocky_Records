<?php
// En el futuro, aquí harás un SELECT * FROM generos para traerlos de la base de datos.
// Por ahora, simulamos los datos en un arreglo dinámico para que veas cómo funciona PHP.
$generos_ejemplo = [
    ['nombre' => 'ROCK', 'slug' => 'rock', 'imagen' => 'img/generos/rock.jpg'],
    ['nombre' => 'INDIE', 'slug' => 'indie', 'imagen' => 'img/generos/indie.jpg'],
    ['nombre' => 'JAZZ', 'slug' => 'jazz', 'imagen' => 'img/generos/jazz.jpg'],
    ['nombre' => 'HIP HOP', 'slug' => 'hip-hop', 'imagen' => 'img/generos/hiphop.jpg'],
    ['nombre' => 'POP', 'slug' => 'pop', 'imagen' => 'img/generos/pop.jpg'],
    ['nombre' => 'METAL', 'slug' => 'metal', 'imagen' => 'img/generos/metal.jpg'],
    ['nombre' => 'ELECTRÓNICA', 'slug' => 'electronica', 'imagen' => 'img/generos/electronica.jpg'],
    ['nombre' => 'CLÁSICA', 'slug' => 'clasica', 'imagen' => 'img/generos/clasica.jpg'],
    ['nombre' => 'SOUL', 'slug' => 'soul', 'imagen' => 'img/generos/soul.jpg']
];
?>
<!DOCTYPE html>
<html lang="es">
// <head>
    <header class="main-header">
    <div class="top-bar">
        <div class="logo">
            <img src="img/logo.png" alt="Rocky Records Logo">
            
        </div>
        
        <nav class="main-nav">
            <a href="index.php">Inicio</a>
            <a href="viniles.php">Viniles</a>
            <a href="cds.php">CD'S</a>
            <a href="generos.php">Géneros</a>
            <a href="ofertas.php">Ofertas</a>
            <a href="colecciones.php">Colecciones</a>
        </nav>
        
        <div class="user-controls">
            <input type="text" placeholder="Buscar álbum, artista...">
            
            <div class="icons">
                <a href="cuenta.php" class="btn-icon">Mi cuenta</a>
                <a href="carrito.php" class="btn-icon">Carrito</a>
            </div>
        </div>
    </div>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rocky Records - Géneros</title>
    <link rel="stylesheet" href="generos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</header>
</head>
<body>

    <header class="navbar">
        <div class="logo">Rocky Records</div>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="viniles.php">Viniles</a>
            <a href="cds.php">CD's</a>
            <a href="generos.php" class="active">Géneros</a>
            <a href="colecciones.php">Colecciones</a>
        </nav>
        <div class="nav-icons">
            <i class="fas fa-search"></i>
            <i class="fas fa-user"></i>
            <i class="fas fa-shopping-cart"></i>
        </div>
    </header>

    <main class="generos-container">
        <h1 class="main-title">GÉNEROS</h1>

        <div class="generos-grid">
            <?php 
            // Este ciclo "foreach" recorre el arreglo y dibuja cada tarjeta automáticamente.
            // Cuando uses la base de datos, este mismo ciclo dibujará lo que tengas guardado en phpMyAdmin.
            foreach ($generos_ejemplo as $genero): 
            ?>
                <div class="genero-card" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)), url('<?php echo $genero['imagen']; ?>');">
                    <div class="genero-content">
                        <h2><?php echo $genero['nombre']; ?></h2>
                        <a href="cds.php?genero=<?php echo $genero['slug']; ?>" class="btn-ver-todos">
                            VER TODOS <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>