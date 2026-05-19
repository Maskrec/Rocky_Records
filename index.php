<?php include 'db.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rocky Records - La musica suena mejor en fisico</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>
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
        $stmt = $pdo->query("SELECT * FROM productos ORDER BY Fecha_agregado DESC");
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
                <form action="agregar_carrito.php" method="POST" style="margin:0;">
                    <?php 
                    $id_val = isset($row['id']) ? $row['id'] : (isset($row['ID']) ? $row['ID'] : (isset($row['id_producto']) ? $row['id_producto'] : ''));
                    ?>
                    <input type="hidden" name="id_producto" value="<?php echo $id_val; ?>">
                    <input type="hidden" name="cantidad" value="1">
                    <button type="submit" class="btn-agregar">agregar al carrito</button>
                </form>
            </div>
        <?php } ?>
    </main>
</body>
<?php include 'footer.php'; ?>
</html>n>
</body>
<?php include 'footer.php'; ?>
</html>