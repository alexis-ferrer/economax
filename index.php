<?php include 'includes/conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECONOMAX 24 Horas - Tu farmacia de confianza</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<!-- HEADER -->
<header>
    <div class="header-contenido">
        <a href="index.php" class="logo">
            <div class="logo-badge">E24</div>
            <div class="logo-texto">
                <p>ECONOMAX</p>
                <p>24 Horas • Siempre contigo</p>
            </div>
        </a>
        <div class="buscador">
            <input type="text" placeholder="Buscar medicamentos...">
            <button>🔍 Buscar</button>
        </div>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="productos.php">Productos</a>
            <a href="carrito.php" class="btn-carrito">🛒 Carrito</a>
        </nav>
    </div>
</header>

<!-- BANNER -->
<section class="banner">
    <div class="banner-contenido">
        <div>
            <p style="color: #4CAF82; font-size:13px; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Farmacia de confianza</p>
            <h1>Tu salud es<br><span>nuestra prioridad</span></h1>
            <p>Medicamentos, vitaminas y productos de salud con entrega a domicilio. Abiertos las 24 horas.</p>
            <div class="banner-botones">
                <a href="productos.php" class="btn-principal">Ver productos</a>
                <a href="https://wa.me/573225912029" class="btn-secundario" target="_blank">📱 Pedir por WhatsApp</a>
            </div>
        </div>
        <div class="banner-icono">💊</div>
    </div>
</section>

<!-- CATEGORÍAS -->
<div class="seccion">
    <h2 class="seccion-titulo">Categorías</h2>
    <div class="grid-categorias">
        <?php
        $cats = $conn->query("SELECT * FROM categorias WHERE activo = 1");
        while($cat = $cats->fetch_assoc()):
        ?>
        <a href="productos.php?categoria=<?= urlencode($cat['nombre']) ?>" class="tarjeta-categoria">
            <div class="icono"><?= $cat['icono'] ?></div>
            <p><?= $cat['nombre'] ?></p>
        </a>
        <?php endwhile; ?>
    </div>
</div>

<!-- PRODUCTOS DESTACADOS -->
<div class="seccion">
    <h2 class="seccion-titulo">Productos destacados</h2>
    <div class="grid-productos">
        <?php
        $prods = $conn->query("SELECT * FROM productos WHERE activo = 1 LIMIT 6");
        while($p = $prods->fetch_assoc()):
        ?>
        <div class="tarjeta-producto">
            <div class="producto-imagen">💊</div>
            <div class="producto-info">
                <p class="producto-nombre"><?= $p['nombre'] ?></p>
                <div>
                    <span class="producto-precio">$<?= number_format($p['precio_oferta'] ?? $p['precio'], 0, ',', '.') ?></span>
                    <?php if($p['precio_oferta']): ?>
                        <span class="producto-precio-original">$<?= number_format($p['precio'], 0, ',', '.') ?></span>
                        <span class="badge-oferta">OFERTA</span>
                    <?php endif; ?>
                </div>
            </div>
            <button class="btn-agregar">🛒 Agregar al carrito</button>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <div class="footer-contenido">
        <div class="footer-col">
            <h4>ECONOMAX 24 Horas</h4>
            <p>Tu farmacia de confianza. Abiertos las 24 horas, los 7 días de la semana.</p>
        </div>
        <div class="footer-col">
            <h4>Enlaces</h4>
            <a href="index.php">Inicio</a>
            <a href="productos.php">Productos</a>
            <a href="ofertas.php">Ofertas</a>
        </div>
        <div class="footer-col">
            <h4>Contacto</h4>
            <p>📞 322 591 2029</p>
            <p>📍 Morroa, Sucre</p>
            <p>🕐 Abierto 24 horas</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2025 ECONOMAX 24 Horas — Todos los derechos reservados</p>
    </div>
</footer>

</body>
</html>