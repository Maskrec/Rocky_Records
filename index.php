<?php include 'db.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rocky Records - La musica suena mejor en fisico</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="main-header">
       <?php include 'header.php'; ?>
    </header>
    <section class="hero">
       
        <div class="hero-img">
            <img src="img/tocadiscos.png" alt="Toca discos">
        </div>
         <div class="hero-content">
            <h2>La musica suena mejor en fisico</h2>
            <p>Descubre nuestra apmplia seccion de coleccion Vinils, CD.s y coleccionables</p>
            <a href="#catalogo" class="btn-explorar">Explorar</a>
        </div>
    </section>


    <main class="catalogo">
        <?php
        $stmt = $pdo->query("SELECT * FROM productos ORDER BY Fecha_Publicacion DESC");
        while ($row = $stmt->fetch()) {
            $precio_final = $row['precio_oferta'] ? $row['precio_oferta'] : $row['precio'];
        ?>
            <div class="producto-card">
                <img src="uploads/portadas/<?php echo $row['imagen_url']; ?>" alt="Portada">
                <h3><?php echo $row['titulo']; ?></h3>
                <p><?php echo $row['artista']; ?> - <strong><?php echo $row['formato']; ?></strong></p>
                
                <div class="reproductor">
                    <audio controls controlsList="nodownload">
                        <source src="uploads/demos/<?php echo $row['demo_url']; ?>" type="audio/mpeg">
                    </audio>
                </div>

                <p class="precio">$<?php echo $precio_final; ?> MXN</p>
                <button class="btn-agregar">agregar al carrito</button>
            </div>
        <?php } ?>
    </main>
</body>
<?php include 'footer.php'; ?>
</html>