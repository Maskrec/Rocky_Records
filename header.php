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
            <form action="buscar.php" method="GET" style="display: flex; align-items: center; margin: 0;">
                <input type="text" name="q" placeholder="Buscar álbum, artista..." required>
                <button type="submit" style="display: none;"></button>
            </form>
            
            <div class="icons">
                <a href="cuenta.php" class="btn-icon">Mi cuenta</a>
                <a href="carrito.php" class="btn-icon">Carrito</a>
            </div>
        </div>
    </div>
</header>