<?php
if (!isset($pdo)) {
    include_once 'db.php';
}

$cant_items_carrito = 0;
if (isset($pdo)) {
    try {
        $stmt_cart = $pdo->prepare("SELECT SUM(cantidad) AS total FROM carrito WHERE session_id = ?");
        $stmt_cart->execute([session_id()]);
        $cart_res = $stmt_cart->fetch();
        if ($cart_res && $cart_res['total'] !== null) {
            $cant_items_carrito = $cart_res['total'];
        }
    } catch (Exception $e) {
        // Silenciar error en caso de que la tabla o sesión no exista
    }
}
?>
<!-- Fuentes de Google -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Satisfy&display=swap" rel="stylesheet">

<header class="cabecera-principal">
    <div class="contenedor-cabecera">
        <!-- Área del Logotipo -->
        <a href="index.php" class="enlace-logo">
            <div class="logo-vinilo">
                <img src="img/svg/logo-vinilo.svg" alt="Logo Rocky Records" class="logo-vinilo-img">
            </div>
            <div class="texto-logo">
                <span class="titulo-logo"> Rocky</span>
                <span class="subtitulo-logo">RECORDS</span>
                <span class="lema-logo">VINILOS & CDS</span>
            </div>
        </a>
        
       
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <nav class="navegacion-principal">
            <a href="index.php" class="enlace-navegacion <?php echo ($current_page == 'index.php') ? 'activo' : ''; ?>">INICIO</a>
            <a href="viniles.php" class="enlace-navegacion <?php echo ($current_page == 'viniles.php') ? 'activo' : ''; ?>">VINILES</a>
            <a href="cds.php" class="enlace-navegacion <?php echo ($current_page == 'cds.php') ? 'activo' : ''; ?>">CD'S</a>
            <a href="index.php#nuevos-lanzamientos" class="enlace-navegacion">NUEVOS LANZAMIENTOS</a>
            <a href="generos.php" class="enlace-navegacion <?php echo ($current_page == 'generos.php') ? 'activo' : ''; ?>">GÉNEROS</a>
            <a href="index.php#ofertas" class="enlace-navegacion">OFERTAS</a>
            <a href="colecciones.php" class="enlace-navegacion <?php echo ($current_page == 'colecciones.php') ? 'activo' : ''; ?>">COLECCIONES</a>
        </nav>
        
       
        <div class="controles-usuario">
        
            <form action="buscar.php" method="GET" class="formulario-busqueda">
                <input type="text" name="q" placeholder="Buscar artista, álbum, género..." required>
                <button type="submit" class="boton-busqueda">
                    <img src="img/svg/icono-lupa.svg" alt="Buscar" class="icono-lupa">
                </button>
            </form>
            
            <div class="enlaces-accion-cabecera">
                <!-- Mi Cuenta -->
                <a href="cuenta.php" class="elemento-accion <?php echo ($current_page == 'cuenta.php') ? 'activo' : ''; ?>">
                    <img src="img/svg/icono-usuario.svg" alt="Usuario" class="icono-accion">
                    <span class="etiqueta-accion">MI CUENTA</span>
                </a>
                
                <!-- Carrito -->
                <a href="carrito.php" class="elemento-accion boton-item-carrito <?php echo ($current_page == 'carrito.php') ? 'activo' : ''; ?>">
                    <div class="contenedor-icono-carrito">
                        <img src="img/svg/icono-carrito.svg" alt="Carrito" class="icono-accion">
                        <?php if ($cant_items_carrito > 0): ?>
                            <span class="insignia-carrito"><?php echo $cant_items_carrito; ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="etiqueta-accion">CARRITO</span>
                </a>
            </div>
        </div>
    </div>
</header>