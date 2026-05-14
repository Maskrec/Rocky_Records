<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de administrador - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h2> Agregar nuevo album la tienda </h2>
    <form action="guardar_producto.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="Titulo de lalbum" required><br>
        <input type="text" name="artista" placeholder="Nombre del artista" required><br>

        <select name="formato">
            <option value="Vinilo">Vinilo</option>
            <option value="CD">CD</option>
        </select><br>

        <input type="text" name="genero" placeholder="Genero del album"><br>
        <label>Fecha de Publicación:</label>
        <input type="date" name="Fecha_Publicacion"><br>
        <input type="number" name="precio" step="0.01" placeholder="Precio del album" required><br>
        <input type="number" name="precio_oferta" step="0.01" placeholder="Precio de la oferta -opcional"><br>
        <input type="number" name="stock" placeholder="Cantidad en stock" required><br>
        <textarea name="descripcion" placeholder="Descripcion del album" rows="4"></textarea><br>

        <label>imagen de portada del album:</label>
        <input type="file" name="imagen" accept="image/*"required><br>

        <label>audio Demo mp3:</label>
        <input type="file" name="audio" accept="audio/mp3*" required><br>

        <button type="submit">Subir a la tienda</button>
    </form>
</body>
</html>