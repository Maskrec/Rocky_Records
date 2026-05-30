<?php
include 'includes/db.php';

// Validar que el usuario sea
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$mensaje_info = $_GET['info'] ?? '';
$mensaje_error = $_GET['error'] ?? '';

// Logica de eliminacion
if (isset($_GET['eliminar_id'])) {
    try {
        $eliminar_id = (int) $_GET['eliminar_id'];

        //Eliminar referencias del producto en el carrito primero
        $stmt_del_cart = $pdo->prepare("DELETE FROM carrito WHERE producto_id = ?");
        $stmt_del_cart->execute([$eliminar_id]);


        $stmt_files = $pdo->prepare("SELECT imagen_url, demo_url FROM productos WHERE id = ?");
        $stmt_files->execute([$eliminar_id]);
        $files = $stmt_files->fetch();
        if ($files) {
            if ($files['imagen_url'] && file_exists('uploads/portadas/' . $files['imagen_url'])) {
                @unlink('uploads/portadas/' . $files['imagen_url']);
            }
            if ($files['demo_url'] && file_exists('uploads/demos/' . $files['demo_url'])) {
                @unlink('uploads/demos/' . $files['demo_url']);
            }
        }

        // Eliminar el producto de la base de datos
        $stmt_del = $pdo->prepare("DELETE FROM productos WHERE id = ?");
        $stmt_del->execute([$eliminar_id]);

        $mensaje_info = "Producto eliminado con exito.";
    } catch (Exception $e) {
        $mensaje_error = "Error al eliminar producto: " . $e->getMessage();
    }
}


$prod_edit = null;
$es_edicion = false;
if (isset($_GET['editar_id'])) {
    $editar_id = (int) $_GET['editar_id'];
    $stmt_edit = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt_edit->execute([$editar_id]);
    $prod_edit = $stmt_edit->fetch();
    if ($prod_edit) {
        $es_edicion = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de administrador - Rocky Records</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .contenedor-admin {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background-color: var(--color-tarjeta);
            background-image: url('css/textura-papel.jpg');
            border: 1px solid var(--color-borde);
            border-radius: 6px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .form-admin {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-grupo {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-grupo-full {
            grid-column: 1 / -1;
        }

        .form-grupo label {
            font-size: 12px;
            font-weight: 700;
            color: var(--texto-atenuado);
            text-transform: uppercase;
        }

        .form-grupo input[type="text"],
        .form-grupo input[type="number"],
        .form-grupo input[type="date"],
        .form-grupo select,
        .form-grupo textarea {
            padding: 10px;
            border: 1px solid var(--color-borde);
            border-radius: 4px;
            font-family: var(--fuente-sans);
            font-size: 14px;
            background-color: #fff;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-grupo input:focus,
        .form-grupo select:focus,
        .form-grupo textarea:focus {
            border-color: var(--color-naranja);
        }

        .form-grupo input[type="file"] {
            font-size: 13px;
            padding: 5px 0;
        }

        .alert {
            padding: 10px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-info {
            background-color: #edfdfd;
            color: #03543f;
            border: 1px solid #bcf0da;
        }

        .alert-error {
            background-color: #fde8e8;
            color: #9b1c1c;
            border: 1px solid #f8b4b4;
        }

        /* Tabla de Productos */
        .tabla-productos-admin {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            background-color: var(--color-tarjeta);
            background-image: url('css/textura-papel.jpg');
            border: 1px solid var(--color-borde);
            border-radius: 4px;
        }

        .tabla-productos-admin th,
        .tabla-productos-admin td {
            padding: 10px 12px;
            border: 1px solid var(--color-borde);
            text-align: left;
            font-size: 13px;
        }

        .tabla-productos-admin th {
            background-color: #eae1d4;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .tabla-productos-admin img {
            width: 50px;
            height: auto;
            display: block;
            box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.15);
        }

        .btn-accion-admin {
            display: inline-block;
            padding: 6px 12px;
            font-size: 10px;
            font-weight: 700;
            text-decoration: none;
            border-radius: 3px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: transform 0.2s;
        }

        .btn-accion-admin:hover {
            transform: scale(1.05);
        }

        .btn-editar-admin {
            background-color: #44403c;
            margin-right: 5px;
        }

        .btn-eliminar-admin {
            background-color: #b91c1c;
        }

        @media (max-width: 768px) {
            .form-admin {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-seccion">
        <div class="contenedor-admin">

            <?php if ($es_edicion): ?>
                <h2
                    style="font-family: var(--fuente-serif); font-size: 24px; border-bottom: 2px solid var(--color-borde); padding-bottom: 10px; margin-bottom: 25px; text-transform: uppercase; color: var(--color-naranja);">
                    Editar Album: "<?php echo htmlspecialchars($prod_edit['titulo']); ?>"</h2>
            <?php else: ?>
                <h2
                    style="font-family: var(--fuente-serif); font-size: 24px; border-bottom: 2px solid var(--color-borde); padding-bottom: 10px; margin-bottom: 25px; text-transform: uppercase;">
                    Agregar nuevo album a la tienda</h2>
            <?php endif; ?>

            <?php if ($mensaje_info !== ''): ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($mensaje_info); ?></div>
            <?php endif; ?>

            <?php if ($mensaje_error !== ''): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($mensaje_error); ?></div>
            <?php endif; ?>

            <form
                action="actions/guardar_producto.php<?php echo $es_edicion ? '?action=edit&id=' . $prod_edit['id'] : '?action=add'; ?>"
                method="POST" enctype="multipart/form-data" class="form-admin">
                <div class="form-grupo">
                    <label for="titulo">Titulo del album</label>
                    <input type="text" id="titulo" name="titulo"
                        value="<?php echo $es_edicion ? htmlspecialchars($prod_edit['titulo']) : ''; ?>"
                        placeholder="Ej: Abbey Road" required>
                </div>

                <div class="form-grupo">
                    <label for="artista">Nombre del artista / banda</label>
                    <input type="text" id="artista" name="artista"
                        value="<?php echo $es_edicion ? htmlspecialchars($prod_edit['artista']) : ''; ?>"
                        placeholder="Ej: The Beatles" required>
                </div>

                <div class="form-grupo">
                    <label for="formato">Formato del soporte</label>
                    <select id="formato" name="formato" required>
                        <option value="Vinil" <?php echo ($es_edicion && $prod_edit['formato'] === 'Vinil') ? 'selected' : ''; ?>>Vinilo</option>
                        <option value="CD" <?php echo ($es_edicion && $prod_edit['formato'] === 'CD') ? 'selected' : ''; ?>>CD</option>
                    </select>
                </div>

                <div class="form-grupo">
                    <label for="genero">Genero musical</label>
                    <select id="genero" name="genero" required>
                        <option value="">Selecciona un genero...</option>
                        <?php
                        try {
                            $stmt_genres = $pdo->query("SELECT Nombre_Genero FROM genero_musical ORDER BY Nombre_Genero ASC");
                            while ($g = $stmt_genres->fetch()) {
                                $selected = ($es_edicion && $prod_edit['genero'] === $g['Nombre_Genero']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($g['Nombre_Genero']) . '" ' . $selected . '>' . htmlspecialchars($g['Nombre_Genero']) . '</option>';
                            }
                        } catch (Exception $e) {
                            echo '<option value="">Error al cargar generos</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-grupo">
                    <label for="Fecha_Publicacion">Fecha de Publicacion</label>
                    <input type="date" id="Fecha_Publicacion" name="Fecha_Publicacion"
                        value="<?php echo $es_edicion ? htmlspecialchars($prod_edit['Fecha_Publicacion'] ?? '') : ''; ?>">
                </div>

                <div class="form-grupo">
                    <label for="precio">Precio (MXN)</label>
                    <input type="number" id="precio" name="precio" step="0.01"
                        value="<?php echo $es_edicion ? htmlspecialchars($prod_edit['precio']) : ''; ?>"
                        placeholder="Ej: 499.00" required>
                </div>

                <div class="form-grupo">
                    <label for="precio_oferta">Precio de oferta (Opcional - MXN)</label>
                    <input type="number" id="precio_oferta" name="precio_oferta" step="0.01"
                        value="<?php echo $es_edicion ? htmlspecialchars($prod_edit['precio_oferta'] ?? '') : ''; ?>"
                        placeholder="Ej: 399.00">
                </div>

                <div class="form-grupo">
                    <label for="stock">Cantidad en Stock</label>
                    <input type="number" id="stock" name="stock"
                        value="<?php echo $es_edicion ? htmlspecialchars($prod_edit['stock']) : ''; ?>"
                        placeholder="Ej: 10" required>
                </div>

                <div class="form-grupo form-grupo-full">
                    <label for="descripcion">Descripcion del album</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Escribe detalles del album..."
                        rows="4"><?php echo $es_edicion ? htmlspecialchars($prod_edit['descripcion'] ?? '') : ''; ?></textarea>
                </div>

                <div class="form-grupo form-grupo-full">
                    <label for="detalles">Canciones del album (Una cancion por linea)</label>
                    <textarea id="detalles" name="detalles" placeholder="Ej:&#10;1. Song One&#10;2. Song Two&#10;3. Song Three"
                        rows="6"><?php echo $es_edicion ? htmlspecialchars($prod_edit['detalles'] ?? '') : ''; ?></textarea>
                </div>

                <div class="form-grupo form-grupo-full"
                    style="border-top: 1px solid var(--color-borde); padding-top: 15px; margin-top: 5px;">
                    <label for="imagen">Imagen de portada
                        <?php echo $es_edicion ? '(Opcional - deja vacio para mantener actual)' : ''; ?></label>
                    <input type="file" id="imagen" name="imagen" accept="image/*" <?php echo $es_edicion ? '' : 'required'; ?>>
                    <?php if ($es_edicion && $prod_edit['imagen_url']): ?>
                        <span style="font-size: 11px; color: var(--texto-atenuado);">Actual:
                            <?php echo htmlspecialchars($prod_edit['imagen_url']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-grupo form-grupo-full"
                    style="border-bottom: 1px solid var(--color-borde); padding-bottom: 15px; margin-bottom: 10px;">
                    <label for="audio">Audio Demo MP3
                        <?php echo $es_edicion ? '(Opcional - deja vacio para mantener actual)' : ''; ?></label>
                    <input type="file" id="audio" name="audio" accept="audio/mpeg" <?php echo $es_edicion ? '' : 'required'; ?>>
                    <?php if ($es_edicion && $prod_edit['demo_url']): ?>
                        <span style="font-size: 11px; color: var(--texto-atenuado);">Actual:
                            <?php echo htmlspecialchars($prod_edit['demo_url']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-grupo form-grupo-full" style="flex-direction: row; gap: 10px;">
                    <button type="submit" class="boton-explorar"
                        style="flex-grow: 1; justify-content: center; font-size: 13px;">
                        <?php echo $es_edicion ? 'GUARDAR CAMBIOS ➔' : 'SUBIR A LA TIENDA ➔'; ?>
                    </button>
                    <?php if ($es_edicion): ?>
                        <a href="admin.php" class="boton-explorar"
                            style="background-color: #78716c; text-decoration: none; justify-content: center; font-size: 13px;">CANCELAR</a>
                    <?php endif; ?>
                </div>
            </form>


            <h2
                style="font-family: var(--fuente-serif); font-size: 20px; border-bottom: 2px solid var(--color-borde); padding-bottom: 10px; margin-top: 50px; margin-bottom: 20px; text-transform: uppercase;">
                Inventario de Productos</h2>

            <div style="overflow-x: auto;">
                <table class="tabla-productos-admin">
                    <thead>
                        <tr>
                            <th>Portada</th>
                            <th>Titulo</th>
                            <th>Artista</th>
                            <th>Formato</th>
                            <th>Genero</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $stmt_prod = $pdo->query("SELECT * FROM productos ORDER BY fecha_agregado DESC");
                            $productos = $stmt_prod->fetchAll();

                            if (count($productos) > 0) {
                                foreach ($productos as $p) {
                                    $precio_display = $p['precio_oferta'] ? '<span style="color: var(--color-naranja); font-weight:700;">$' . number_format($p['precio_oferta'], 2) . '</span> <del style="font-size:11px; color:var(--texto-atenuado);">' . number_format($p['precio'], 2) . '</del>' : '$' . number_format($p['precio'], 2);

                                    echo '<tr>';
                                    echo '<td><img src="uploads/portadas/' . htmlspecialchars($p['imagen_url']) . '" alt="Cover"></td>';
                                    echo '<td style="font-weight:700;">' . htmlspecialchars($p['titulo']) . '</td>';
                                    echo '<td>' . htmlspecialchars($p['artista']) . '</td>';
                                    echo '<td style="font-weight:700; font-size:11px;">' . htmlspecialchars($p['formato']) . '</td>';
                                    echo '<td>' . htmlspecialchars($p['genero']) . '</td>';
                                    echo '<td>' . $precio_display . '</td>';
                                    echo '<td style="font-weight:700;">' . htmlspecialchars($p['stock']) . '</td>';
                                    echo '<td style="text-align: center; white-space: nowrap;">';
                                    echo '<a href="admin.php?editar_id=' . $p['id'] . '" class="btn-accion-admin btn-editar-admin">Editar</a>';
                                    echo '<a href="admin.php?eliminar_id=' . $p['id'] . '" class="btn-accion-admin btn-eliminar-admin" onclick="return confirm(\'¿Seguro que deseas eliminar este producto?\');">Eliminar</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8" style="text-align: center;">No hay productos en inventario.</td></tr>';
                            }
                        } catch (Exception $e) {
                            echo '<tr><td colspan="8" style="text-align: center; color: red;">Error al cargar inventario: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>