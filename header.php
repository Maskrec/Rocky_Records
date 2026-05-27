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
                <svg viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="48" fill="#151311" />
                    <!-- Surcos -->
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#2a2522" stroke-width="1.5" />
                    <circle cx="50" cy="50" r="32" fill="none" stroke="#2a2522" stroke-width="1.5" />
                    <circle cx="50" cy="50" r="24" fill="none" stroke="#2a2522" stroke-width="1" />
                    <!-- Etiqueta Central -->
                    <circle cx="50" cy="50" r="16" fill="#C2410C" />
                    <circle cx="50" cy="50" r="5" fill="#e6dfd5" />
                </svg>
            </div>
            <div class="texto-logo">
                <span class="titulo-logo"> Rocky</span>
                <span class="subtitulo-logo">RECORDS</span>
                <span class="lema-logo">VINILOS & CDS</span>
            </div>
        </a>
        
       
        <nav class="navegacion-principal">
            <a href="index.php" class="enlace-navegacion activo">INICIO</a>
            <a href="viniles.php" class="enlace-navegacion">VINILES</a>
            <a href="cds.php" class="enlace-navegacion">CD'S</a>
            <a href="index.php#nuevos-lanzamientos" class="enlace-navegacion">NUEVOS LANZAMIENTOS</a>
            <a href="index.php#generos" class="enlace-navegacion">GÉNEROS</a>
            <a href="index.php#ofertas" class="enlace-navegacion">OFERTAS</a>
            <a href="index.php#colecciones" class="enlace-navegacion">COLECCIONES</a>
        </nav>
        
        <!-- Controles de Usuario / Búsqueda / Carrito -->
        <div class="controles-usuario">
            <!-- Barra de búsqueda -->
            <form action="buscar.php" method="GET" class="formulario-busqueda">
                <input type="text" name="q" placeholder="Buscar artista, álbum, género..." required>
                <button type="submit" class="boton-busqueda">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>
            
            <!-- Menús de iconos -->
            <div class="enlaces-accion-cabecera">
                <!-- Mi Cuenta -->
                <a href="cuenta.php" class="elemento-accion">
                    <svg class="icono-accion" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="etiqueta-accion">MI CUENTA</span>
                </a>
                
                <!-- Carrito -->
                <a href="carrito.php" class="elemento-accion boton-item-carrito">
                    <div class="contenedor-icono-carrito">
                        <svg class="icono-accion" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="insignia-carrito"><?php echo $cant_items_carrito; ?></span>
                    </div>
                    <span class="etiqueta-accion">CARRITO</span>
                </a>
            </div>
        </div>
    </div>
</header>