<?php 
include 'includes/conexion.php';

$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$busqueda  = isset($_GET['buscar'])    ? $_GET['buscar']    : '';

$sql = "SELECT * FROM productos WHERE activo = 1";
if($categoria) $sql .= " AND categoria = '" . $conn->real_escape_string($categoria) . "'";
if($busqueda)  $sql .= " AND nombre LIKE '%" . $conn->real_escape_string($busqueda) . "%'";
$sql .= " ORDER BY id DESC";

$productos  = $conn->query($sql);
$categorias = $conn->query("SELECT * FROM categorias WHERE activo = 1");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - ECONOMAX 24 Horas</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        .pagina-productos {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 24px;
        }
        .sidebar {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            height: fit-content;
            position: sticky;
            top: 80px;
        }
        .sidebar h3 {
            color: var(--azul);
            font-size: 15px;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--verde);
        }
        .filtro-cat {
            display: block;
            padding: 9px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--texto);
            font-size: 14px;
            margin-bottom: 4px;
            transition: all 0.2s;
        }
        .filtro-cat:hover, .filtro-cat.activo {
            background-color: #e8f5ee;
            color: var(--verde);
            font-weight: 600;
        }
        .filtro-cat span {
            margin-right: 8px;
        }
        .area-productos h2 {
            font-size: 20px;
            color: var(--azul);
            margin-bottom: 6px;
        }
        .resultado-info {
            font-size: 13px;
            color: #888;
            margin-bottom: 16px;
        }
        .sin-resultados {
            background: white;
            border-radius: 12px;
            padding: 60px;
            text-align: center;
            color: #888;
        }
        .sin-resultados p:first-child {
            font-size: 48px;
            margin-bottom: 12px;
        }
        .barra-busqueda-prod {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .barra-busqueda-prod input {
            flex: 1;
            padding: 10px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }
        .barra-busqueda-prod input:focus {
            border-color: var(--verde);
        }
        .barra-busqueda-prod button {
            background: var(--verde);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        .barra-busqueda-prod button:hover {
            background: var(--verde-oscuro);
        }
    </style>
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
            <input type="text" id="buscador-header" placeholder="Buscar medicamentos..." value="<?= htmlspecialchars($busqueda) ?>">
            <button onclick="buscarDesdeHeader()">🔍 Buscar</button>
        </div>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="productos.php">Productos</a>
            <a href="carrito.php" class="btn-carrito">🛒 Carrito</a>
        </nav>
    </div>
</header>

<div class="pagina-productos">

    <!-- SIDEBAR FILTROS -->
    <aside class="sidebar">
        <h3>Categorías</h3>
        <a href="productos.php" class="filtro-cat <?= !$categoria ? 'activo' : '' ?>">
            <span>🏠</span> Todos
        </a>
        <?php 
        $categorias->data_seek(0);
        while($cat = $categorias->fetch_assoc()): ?>
        <a href="productos.php?categoria=<?= urlencode($cat['nombre']) ?>" 
           class="filtro-cat <?= $categoria === $cat['nombre'] ? 'activo' : '' ?>">
            <span><?= $cat['icono'] ?></span> <?= $cat['nombre'] ?>
        </a>
        <?php endwhile; ?>
    </aside>

    <!-- ÁREA DE PRODUCTOS -->
    <div class="area-productos">

        <!-- Barra de búsqueda -->
        <form method="GET" action="productos.php" class="barra-busqueda-prod">
            <?php if($categoria): ?>
                <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
            <?php endif; ?>
            <input type="text" name="buscar" placeholder="Buscar producto..." value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit">🔍 Buscar</button>
        </form>

        <h2>
            <?= $categoria ? '💊 ' . htmlspecialchars($categoria) : 'Todos los productos' ?>
            <?= $busqueda ? ' — "' . htmlspecialchars($busqueda) . '"' : '' ?>
        </h2>
        <p class="resultado-info"><?= $productos->num_rows ?> producto(s) encontrado(s)</p>

        <?php if($productos->num_rows > 0): ?>
        <div class="grid-productos">
            <?php while($p = $productos->fetch_assoc()): ?>
            <div class="tarjeta-producto">
                <div class="producto-imagen">💊</div>
                <div class="producto-info">
                    <p class="producto-nombre"><?= htmlspecialchars($p['nombre']) ?></p>
                    <p style="font-size:12px; color:#888; margin-bottom:6px;"><?= htmlspecialchars($p['categoria']) ?></p>
                    <div>
                        <span class="producto-precio">
                            $<?= number_format($p['precio_oferta'] ?? $p['precio'], 0, ',', '.') ?>
                        </span>
                        <?php if($p['precio_oferta']): ?>
                            <span class="producto-precio-original">$<?= number_format($p['precio'], 0, ',', '.') ?></span>
                            <span class="badge-oferta">OFERTA</span>
                        <?php endif; ?>
                    </div>
                    <p style="font-size:12px; color:#aaa; margin-top:4px;">Stock: <?= $p['stock'] ?> unidades</p>
                </div>
                <button class="btn-agregar" onclick="agregarCarrito(<?= $p['id'] ?>, '<?= addslashes($p['nombre']) ?>', <?= $p['precio_oferta'] ?? $p['precio'] ?>)">
                    🛒 Agregar al carrito
                </button>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="sin-resultados">
            <p>🔍</p>
            <p style="font-size:18px; font-weight:600; color:#555;">No encontramos productos</p>
            <p style="margin-top:8px;">Intenta con otro término o categoría</p>
            <a href="productos.php" style="display:inline-block; margin-top:16px; background:var(--verde); color:white; padding:10px 24px; border-radius:25px; text-decoration:none;">Ver todos</a>
        </div>
        <?php endif; ?>
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
        </div>
        <div class="footer-col">
            <h4>Contacto</h4>
            <p>📞 300 123 4567</p>
            <p>📍 Sincelejo, Sucre</p>
            <p>🕐 Abierto 24 horas</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2025 ECONOMAX 24 Horas — Todos los derechos reservados</p>
    </div>
</footer>

<script>
function buscarDesdeHeader() {
    const texto = document.getElementById('buscador-header').value;
    window.location.href = 'productos.php?buscar=' + encodeURIComponent(texto);
}

document.getElementById('buscador-header').addEventListener('keypress', function(e) {
    if(e.key === 'Enter') buscarDesdeHeader();
});

function agregarCarrito(id, nombre, precio) {
    let carrito = JSON.parse(localStorage.getItem('carrito_economax') || '[]');
    const existe = carrito.find(p => p.id === id);
    if(existe) {
        existe.cantidad++;
    } else {
        carrito.push({ id, nombre, precio, cantidad: 1 });
    }
    localStorage.setItem('carrito_economax', JSON.stringify(carrito));
    
    const btn = event.target;
    btn.textContent = '✅ Agregado';
    btn.style.background = 'var(--verde)';
    setTimeout(() => {
        btn.textContent = '🛒 Agregar al carrito';
        btn.style.background = '';
    }, 1500);
}
</script>

</body>
</html>