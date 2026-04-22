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

<style>
/* ===== OFERTAS ===== */
.seccion-ofertas {
    background: #f0f4f8;
    padding: 40px 20px 10px;
}
.ofertas-contenedor {
    max-width: 1200px;
    margin: 0 auto;
}
.ofertas-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.ofertas-titulo-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.fuego {
    font-size: 24px;
    animation: fuego 0.6s ease-in-out infinite alternate;
}
@keyframes fuego {
    from { transform: scale(1) rotate(-5deg); }
    to   { transform: scale(1.2) rotate(5deg); }
}
.ofertas-titulo {
    font-size: 22px;
    font-weight: bold;
    color: #1A5FA8;
    margin: 0;
}
.badge-live {
    background: #ff4444;
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 20px;
    letter-spacing: 1px;
    animation: pulso 1.2s ease-in-out infinite;
}
@keyframes pulso {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.5; }
}
.ofertas-controles {
    display: flex;
    gap: 8px;
}
.ctrl-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid #1A5FA8;
    background: white;
    color: #1A5FA8;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
.ctrl-btn:hover {
    background: #1A5FA8;
    color: white;
}
.carrusel-track-wrap {
    overflow: hidden;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}
.carrusel-track {
    display: flex;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.oferta-slide {
    min-width: 100%;
    padding: 36px 40px;
    box-sizing: border-box;
}
.oferta-slide-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1120px;
    margin: 0 auto;
}
.oferta-izq { flex: 1; }
.oferta-descuento {
    display: inline-block;
    background: #FFD700;
    color: #333;
    font-size: 13px;
    font-weight: 800;
    padding: 4px 14px;
    border-radius: 20px;
    margin-bottom: 12px;
    letter-spacing: 0.5px;
}
.oferta-nombre {
    font-size: 30px;
    font-weight: 800;
    margin-bottom: 8px;
    line-height: 1.2;
}
.oferta-desc {
    font-size: 15px;
    margin-bottom: 20px;
    max-width: 480px;
}
.oferta-cta {
    display: inline-block;
    background: white;
    color: #1A5FA8;
    font-weight: 700;
    font-size: 14px;
    padding: 10px 24px;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.oferta-cta:hover {
    background: #FFD700;
    color: #333;
    transform: translateY(-2px);
}
.oferta-der {
    position: relative;
    width: 160px;
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.oferta-circulo {
    position: absolute;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
}
.oferta-icono {
    font-size: 80px;
    position: relative;
    z-index: 1;
    animation: flotar 2s ease-in-out infinite;
}
@keyframes flotar {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-10px); }
}
.carrusel-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 14px;
    margin-bottom: 10px;
}
.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: none;
    background: #ccc;
    cursor: pointer;
    transition: all 0.3s;
    padding: 0;
}
.dot.activo {
    background: #1A5FA8;
    width: 28px;
    border-radius: 5px;
}
@media(max-width: 600px) {
    .oferta-der { display: none; }
    .oferta-nombre { font-size: 22px; }
    .oferta-slide { padding: 24px 20px; }
}
</style>

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

<!-- CARRUSEL DE OFERTAS -->
<?php
$ofertas_query = $conn->query("SELECT * FROM ofertas WHERE activo = 1 ORDER BY id DESC");
$ofertas_arr   = [];
while($o = $ofertas_query->fetch_assoc()) $ofertas_arr[] = $o;
?>
<?php if(count($ofertas_arr) > 0): ?>
<section class="seccion-ofertas">
    <div class="ofertas-contenedor">
        <div class="ofertas-header">
            <div class="ofertas-titulo-wrap">
                <span class="fuego">🔥</span>
                <h2 class="ofertas-titulo">Ofertas del día</h2>
                <span class="badge-live">EN VIVO</span>
            </div>
            <div class="ofertas-controles">
                <button class="ctrl-btn" id="btn-prev">&#8592;</button>
                <button class="ctrl-btn" id="btn-next">&#8594;</button>
            </div>
        </div>

        <div class="carrusel-track-wrap">
            <div class="carrusel-track" id="carrusel-track">
                <?php foreach($ofertas_arr as $i => $o): ?>
                <div class="oferta-slide" style="background: <?= $o['color_fondo'] ?>;">
                    <div class="oferta-slide-inner">
                        <div class="oferta-izq">
                            <span class="oferta-descuento"><?= htmlspecialchars($o['descuento']) ?></span>
                            <h3 class="oferta-nombre" style="color:<?= $o['color_texto'] ?>">
                                <?= htmlspecialchars($o['titulo']) ?>
                            </h3>
                            <p class="oferta-desc" style="color:<?= $o['color_texto'] ?>; opacity:0.85;">
                                <?= htmlspecialchars($o['descripcion']) ?>
                            </p>
                            <a href="productos.php" class="oferta-cta">Ver oferta →</a>
                        </div>
                        <div class="oferta-der">
                            <div class="oferta-icono-wrap">
                                <span class="oferta-icono">💊</span>
                                <div class="oferta-circulo"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Puntos indicadores -->
        <div class="carrusel-dots" id="carrusel-dots">
            <?php foreach($ofertas_arr as $i => $o): ?>
                <button class="dot <?= $i === 0 ? 'activo' : '' ?>" onclick="irA(<?= $i ?>)"></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

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

<script>
const track  = document.getElementById('carrusel-track');
const dots   = document.querySelectorAll('.dot');
const total  = dots.length;
let actual   = 0;
let intervalo;

function irA(n) {
    actual = (n + total) % total;
    track.style.transform = `translateX(-${actual * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle('activo', i === actual));
}

function siguiente() { irA(actual + 1); }
function anterior()  { irA(actual - 1); }

document.getElementById('btn-next').addEventListener('click', () => { siguiente(); reiniciar(); });
document.getElementById('btn-prev').addEventListener('click', () => { anterior();  reiniciar(); });

function reiniciar() {
    clearInterval(intervalo);
    intervalo = setInterval(siguiente, 4000);
}

intervalo = setInterval(siguiente, 4000);
</script>

</body>
</html>