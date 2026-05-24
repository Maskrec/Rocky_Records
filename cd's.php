<?php
// El código PHP para conectarte a la base de datos irá aquí arriba más adelante.
// Por ahora, simularemos que ya estamos conectados.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groove Records - CD's</title>
    <link rel="stylesheet" href="cd's.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="navbar">
        <div class="logo">Groove Records</div>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="viniles.php">Viniles</a>
            <a href="cds.php" class="active">CD's</a>
            <a href="generos.php">Géneros</a>
            <a href="colecciones.php">Colecciones</a>
        </nav>
        <div class="nav-icons">
            <i class="fas fa-search"></i>
            <i class="fas fa-user"></i>
            <i class="fas fa-shopping-cart"></i>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-content">
            <h1>CD'S</h1>
            <p>Descubre nuestra colección de música en CDs para revivir la nostalgia de tu música.</p>
            <a href="#catalogo" class="btn-explorar">EXPLORA CATÁLOGO</a>
        </div>
    </section>

    <main class="main-container" id="catalogo">
        
        <aside class="filters-sidebar">
            <form action="cds.php" method="GET">
                
                <div class="filter-group">
                    <label for="artista">Artista</label>
                    <select name="artista" id="artista">
                        <option value="">Todos</option>
                        <option value="lana-del-rey">Lana Del Rey</option>
                        <option value="pink-floyd">Pink Floyd</option>
                        <option value="the-weeknd">The Weeknd</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="genero">Género</label>
                    <select name="genero" id="genero">
                        <option value="">Todos</option>
                        <option value="rock">Rock</option>
                        <option value="indie">Indie</option>
                        <option value="jazz">Jazz</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Estado</label>
                    <div class="radio-group">
                        <label><input type="radio" name="estado" value="nuevo"> Nuevo</label>
                        <label><input type="radio" name="estado" value="usado"> Usado</label>
                    </div>
                </div>

                <div class="filter-group">
                    <label for="precio">Precio</label>
                    <input type="range" name="precio" id="precio" min="100" max="1000" step="50">
                    <div class="price-labels">
                        <span>$100 MXN</span>
                        <span>$1000 MXN</span>
                    </div>
                </div>

                <button type="submit" class="btn-filtrar">Aplicar Filtros</button>
            </form>
        </aside>

        <section class="products-grid">
            
            <div class="product-card">
                <div class="card-img">
                    <img src="img/daft-punk.jpg" alt="Daft Punk Discovery">
                </div>
                <div class="card-info">
                    <p class="artist-name">Daft Punk</p>
                    <h3 class="album-title">Discovery</h3>
                    <span class="format-badge">CD</span>
                    <div class="card-footer">
                        <span class="price">$370 MXN</span>
                        <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i></button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="card-img">
                    <img src="img/nirvana.jpg" alt="Nirvana Nevermind">
                </div>
                <div class="card-info">
                    <p class="artist-name">Nirvana</p>
                    <h3 class="album-title">Nevermind</h3>
                    <span class="format-badge">CD</span>
                    <div class="card-footer">
                        <span class="price">$350 MXN</span>
                        <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i></button>
                    </div>
                </div>
            </div>

            </section>
    </main>

    </body>
</html>