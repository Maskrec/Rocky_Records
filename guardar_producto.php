<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $artista = $_POST['artista'];
    $formato = $_POST['formato'];
    $genero = $_POST['genero'];
    $fecha_publicacion = $_POST['Fecha_Publicacion'] ?? NULL;
    $precio = $_POST['precio'];
    $precio_oferta = !empty($_POST['precio_oferta']) ? $_POST['precio_oferta'] : NULL;
    $stock = $_POST['stock'] ?? 0;
    $descripcion = $_POST['descripcion'] ?? '';

    //archivos
    $img_nombre = time() . "_" . $_FILES['imagen']['name'];
    $audio_nombre = time() . "_" . $_FILES['audio']['name'];

    move_uploaded_file($_FILES['imagen']['tmp_name'], 'uploads/portadas/' . $img_nombre);
    move_uploaded_file($_FILES['audio']['tmp_name'], 'uploads/demos/' . $audio_nombre);

    $sql = "INSERT INTO productos (titulo, artista, formato, genero, Fecha_Publicacion, precio, precio_oferta, stock, imagen_url, demo_url, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $artista, $formato, $genero, $fecha_publicacion, $precio, $precio_oferta, $stock, $img_nombre, $audio_nombre, $descripcion]);

    echo "Album guardado con éxito en la tienda";
    echo "<br><a href='admin.php'>Volver al panel de administrador</a>";

}
?>
