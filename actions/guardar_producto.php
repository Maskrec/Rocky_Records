<?php
include "../includes/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    die("Error de autorizacion: Solo administradores pueden realizar esta accion.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_GET['action'] ?? 'add';
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    $titulo = $_POST['titulo'];
    $artista = $_POST['artista'];
    $formato = $_POST['formato'];
    $genero = $_POST['genero'];
    $fecha_publicacion = !empty($_POST['Fecha_Publicacion']) ? $_POST['Fecha_Publicacion'] : NULL;
    $precio = $_POST['precio'];
    $precio_oferta = !empty($_POST['precio_oferta']) ? $_POST['precio_oferta'] : NULL;
    $stock = $_POST['stock'] ?? 0;
    $descripcion = $_POST['descripcion'] ?? '';
    $detalles = $_POST['detalles'] ?? '';

    if ($action === 'edit' && $id > 0) {
        try {
            $stmt_curr = $pdo->prepare("SELECT imagen_url, demo_url FROM productos WHERE id = ?");
            $stmt_curr->execute([$id]);
            $current_product = $stmt_curr->fetch();

            if (!$current_product) {
                die("Error: El producto a editar no existe.");
            }

            $img_nombre = $current_product['imagen_url'];
            $audio_nombre = $current_product['demo_url'];

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK && $_FILES['imagen']['size'] > 0) {
                $new_img_name = time() . "_" . $_FILES['imagen']['name'];
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], '../uploads/portadas/' . $new_img_name)) {
                    if ($img_nombre && file_exists('../uploads/portadas/' . $img_nombre)) {
                        @unlink('../uploads/portadas/' . $img_nombre);
                    }
                    $img_nombre = $new_img_name;
                }
            }

            if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK && $_FILES['audio']['size'] > 0) {
                $new_audio_name = time() . "_" . $_FILES['audio']['name'];
                if (move_uploaded_file($_FILES['audio']['tmp_name'], '../uploads/demos/' . $new_audio_name)) {
                    if ($audio_nombre && file_exists('../uploads/demos/' . $audio_nombre)) {
                        @unlink('../uploads/demos/' . $audio_nombre);
                    }
                    $audio_nombre = $new_audio_name;
                }
            }

            $sql = "UPDATE productos SET titulo = ?, artista = ?, formato = ?, genero = ?, Fecha_Publicacion = ?, precio = ?, precio_oferta = ?, stock = ?, imagen_url = ?, demo_url = ?, descripcion = ?, detalles = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titulo, $artista, $formato, $genero, $fecha_publicacion, $precio, $precio_oferta, $stock, $img_nombre, $audio_nombre, $descripcion, $detalles, $id]);

            header("Location: ../admin.php?info=" . urlencode("Producto actualizado con exito."));
            exit;
        } catch (Exception $e) {
            die("Error al actualizar producto: " . $e->getMessage());
        }
    } else {
        try {
            $img_nombre = "";
            $audio_nombre = "";

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $img_nombre = time() . "_" . $_FILES['imagen']['name'];
                move_uploaded_file($_FILES['imagen']['tmp_name'], '../uploads/portadas/' . $img_nombre);
            }

            if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                $audio_nombre = time() . "_" . $_FILES['audio']['name'];
                move_uploaded_file($_FILES['audio']['tmp_name'], '../uploads/demos/' . $audio_nombre);
            }

            $sql = "INSERT INTO productos (titulo, artista, formato, genero, Fecha_Publicacion, precio, precio_oferta, stock, imagen_url, demo_url, descripcion, detalles) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titulo, $artista, $formato, $genero, $fecha_publicacion, $precio, $precio_oferta, $stock, $img_nombre, $audio_nombre, $descripcion, $detalles]);

            header("Location: ../admin.php?info=" . urlencode("Producto agregado con exito."));
            exit;
        } catch (Exception $e) {
            die("Error al guardar producto: " . $e->getMessage());
        }
    }
}

header('Location: ../admin.php');
exit;
?>
