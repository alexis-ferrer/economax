<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../includes/conexion.php';

$total_productos = $conn->query("SELECT COUNT(*) as t FROM productos WHERE activo = 1")->fetch_assoc()['t'];
$total_usuarios  = $conn->query("SELECT COUNT(*) as t FROM usuarios")->fetch_assoc()['t'];
$total_cats      = $conn->query("SELECT COUNT(*) as t FROM categorias WHERE activo = 1")->fetch_assoc()['t'];
$sin_stock       = $conn->query("SELECT COUNT(*) as t FROM productos WHERE activo = 1 AND stock = 0")->fetch_assoc()['t'];
$stock_bajo      = $conn->query("SELECT COUNT(*) as t FROM productos WHERE activo = 1 AND stock > 0 AND stock <= 10")->fetch_assoc()['t'];
$alertas         = $conn->query("SELECT * FROM productos WHERE activo = 1 AND stock <= 10 ORDER BY stock ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ECONOMAX Admin</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        body { display:flex; min-height:100vh; background:#f0f4f8; }
        .sidebar-admin { width:240px; background:var(--azul-oscuro); color:white; flex-shrink:0; min-height:100vh; }
        .sidebar-logo { background:var(--azul); padding:20px; text-align:center; border-bottom:1px solid rgba(255,255,255,0.1); }
        .sidebar-logo p:first-child { font-size:20px; font-weight:bold; }
        .sidebar-logo p:last-child { font-size:11px; color:#a8d4f5; margin-top:2px; }
        .sidebar-menu { padding:16px 0; }
        .menu-item { display:flex; align-items:center; gap:10px; padding:12px 20px; color:#a8d4f5; text-decoration:none; font-size:14px; transition:all 0.2s; border-left:3px solid transparent; }
        .menu-item:hover, .menu-item.activo { background:rgba(255,255,255,0.1); color:white; border-left-color:var(--verde-claro); }
        .menu-item span { font-size:18px; }
        .menu-separador { font-size:11px; color:rgba(255,255,255,0.3); padding:16px 20px 6px; text-transform:uppercase; letter-spacing:1px; }
        .badge-alerta { background:#ff4444; color:white; font-size:10px; font-weight:700; padding:2px 6px; border-radius:10px; margin-left:auto; }
        .contenido-admin { flex:1; padding:30px; overflow-y:auto; }
        .admin-titulo { font-size:24px; font-weight:bold; color:var(--azul); margin-bottom:4px; }
        .admin-subtitulo { color:#888; font-size:14px; margin-bottom:28px; }
        .grid-stats { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:28px; }
        .stat-card { background:white; border-radius:12px; padding:20px 24px; display:flex; align-items:center; gap:16px; box-shadow:0 2px 8px rgba(0,0,0,0.06); border-left:4px solid transparent; transition: transform 0.2s; }
        .stat-card:hover { transform:translateY(-2px); }
        .stat-card.peligro { border-left-color:#ff4444; }
        .stat-card.advertencia { border-left-color:#ff9800; }
        .stat-card.ok { border-left-color:var(--verde); }
        .stat-card.info { border-left-color:var(--azul); }
        .stat-icono { font-size:36px; padding:12px; border-radius:12px; }
        .stat-icono.bg-peligro { background:#fff0f0; }
        .stat-icono.bg-advertencia { background:#fff8e1; }
        .stat-icono.bg-ok { background:#e8f5ee; }
        .stat-icono.bg-info { background:#e8f0fb; }
        .stat-num { font-size:28px; font-weight:bold; line-height:1; }
        .stat-num.peligro { color:#ff4444; }
        .stat-num.advertencia { color:#ff9800; }
        .stat-num.ok { color:var(--verde); }
        .stat-num.info { color:var(--azul); }
        .stat-label { font-size:13px; color:#888; margin-top:4px; }

        /* ALERTAS DE STOCK */
        .panel-alertas { background:white; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.06); margin-bottom:24px; overflow:hidden; }
        .alertas-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid #f0f0f0; }
        .alertas-titulo { display:flex; align-items:center; gap:10px; font-size:16px; font-weight:700; color:var(--azul); }
        .alertas-titulo .campana { font-size:20px; animation: campana 1s ease-in-out infinite; }
        @keyframes campana {
            0%, 100% { transform: rotate(0deg); }
            20%       { transform: rotate(-15deg); }
            40%       { transform: rotate(15deg); }
            60%       { transform: rotate(-10deg); }
            80%       { transform: rotate(10deg); }
        }
        .contador-alertas { background:#ff4444; color:white; font-size:12px; font-weight:700; padding:3px 10px; border-radius:20px; }
        .tabla-alertas { width:100%; border-collapse:collapse; }
        .tabla-alertas th { padding:10px 20px; text-align:left; font-size:12px; font-weight:600; color:#888; background:#fafafa; border-bottom:1px solid #f0f0f0; text-transform:uppercase; letter-spacing:0.5px; }
        .tabla-alertas td { padding:14px 20px; font-size:14px; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
        .tabla-alertas tr:last-child td { border-bottom:none; }
        .tabla-alertas tr:hover td { background:#fffbf0; }
        .stock-chip { display:inline-flex; align-items:center; gap:6px; font-weight:700; font-size:14px; padding:4px 12px; border-radius:20px; }
        .stock-chip.agotado    { background:#fff0f0; color:#cc0000; }
        .stock-chip.critico    { background:#fff0f0; color:#ff4444; }
        .stock-chip.advertencia{ background:#fff8e1; color:#e65100; }
        .barra-stock { width:100%; height:8px; background:#f0f0f0; border-radius:4px; overflow:hidden; margin-top:4px; }
        .barra-fill { height:100%; border-radius:4px; transition:width 0.3s; }
        .barra-fill.agotado    { background:#ff4444; }
        .barra-fill.advertencia{ background:#ff9800; }
        .btn-reponer { background:var(--verde); color:white; border:none; padding:6px 14px; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; transition:background 0.2s; display:inline-block; }
        .btn-reponer:hover { background:var(--verde-oscuro); }
        .sin-alertas { text-align:center; padding:40px; color:#888; }

        /* ACCIONES RÁPIDAS */
        .acciones-rapidas { background:white; border-radius:12px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.06); }
        .acciones-rapidas h3 { color:var(--azul); margin-bottom:16px; font-size:16px; border-left:4px solid var(--verde); padding-left:10px; }
        .grid-acciones { display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:12px; }
        .accion-btn { display:flex; flex-direction:column; align-items:center; gap:8px; padding:20px; background:#f8fafc; border:1px solid #e0e0e0; border-radius:12px; text-decoration:none; color:var(--texto); font-size:13px; font-weight:600; transition:all 0.2s; text-align:center; }
        .accion-btn:hover { background:#e8f5ee; border-color:var(--verde); color:var(--verde); }
        .accion-btn span { font-size:28px; }

        /* TOAST NOTIFICACIÓN */
        .toast { position:fixed; top:24px; right:24px; background:#ff4444; color:white; padding:14px 20px; border-radius:12px; font-size:14px; font-weight:600; box-shadow:0 4px 16px rgba(255,68,68,0.4); z-index:999; display:flex; align-items:center; gap:10px; animation:slideIn 0.4s ease; max-width:340px; }
        @keyframes slideIn { from { transform:translateX(100px); opacity:0; } to { transform:translateX(0); opacity:1; } }
        .toast-cerrar { background:none; border:none; color:white; font-size:18px; cursor:pointer; margin-left:auto; padding:0 4px; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar-admin">
    <div class="sidebar-logo">
        <p>⚙️ ECONOMAX</p>
        <p>Panel de administración</p>
    </div>
    <nav class="sidebar-menu">
        <p class="menu-separador">Principal</p>
        <a href="index.php" class="menu-item activo"><span>📊</span> Dashboard
            <?php if(($sin_stock + $stock_bajo) > 0): ?>
                <span class="badge-alerta"><?= $sin_stock + $stock_bajo ?></span>
            <?php endif; ?>
        </a>
        <p class="menu-separador">Catálogo</p>
        <a href="productos.php" class="menu-item"><span>💊</span> Productos</a>
        <a href="nuevo_producto.php" class="menu-item"><span>➕</span> Agregar producto</a>
        <a href="categorias.php" class="menu-item"><span>📂</span> Categorías</a>
        <p class="menu-separador">Más</p>
        <a href="../index.php" class="menu-item" target="_blank"><span>🌐</span> Ver tienda</a>
        <a href="logout.php" class="menu-item"><span>🚪</span> Cerrar sesión</a>
    </nav>
</aside>

<!-- CONTENIDO -->
<main class="contenido-admin">
    <p class="admin-titulo">Dashboard</p>
    <p class="admin-subtitulo">Bienvenido al panel de ECONOMAX 24 Horas</p>

    <!-- ESTADÍSTICAS -->
    <div class="grid-stats">
        <div class="stat-card info">
            <div class="stat-icono bg-info">💊</div>
            <div>
                <p class="stat-num info"><?= $total_productos ?></p>
                <p class="stat-label">Productos activos</p>
            </div>
        </div>
        <div class="stat-card info">
            <div class="stat-icono bg-info">📂</div>
            <div>
                <p class="stat-num info"><?= $total_cats ?></p>
                <p class="stat-label">Categorías</p>
            </div>
        </div>
        <div class="stat-card <?= $stock_bajo > 0 ? 'advertencia' : 'ok' ?>">
            <div class="stat-icono <?= $stock_bajo > 0 ? 'bg-advertencia' : 'bg-ok' ?>">⚠️</div>
            <div>
                <p class="stat-num <?= $stock_bajo > 0 ? 'advertencia' : 'ok' ?>"><?= $stock_bajo ?></p>
                <p class="stat-label">Stock bajo (Menos de 10 ud.)</p>
            </div>
        </div>
        <div class="stat-card <?= $sin_stock > 0 ? 'peligro' : 'ok' ?>">
            <div class="stat-icono <?= $sin_stock > 0 ? 'bg-peligro' : 'bg-ok' ?>">🚫</div>
            <div>
                <p class="stat-num <?= $sin_stock > 0 ? 'peligro' : 'ok' ?>"><?= $sin_stock ?></p>
                <p class="stat-label">Sin stock</p>
            </div>
        </div>
    </div>

    <!-- PANEL DE ALERTAS -->
    <div class="panel-alertas">
        <div class="alertas-header">
            <div class="alertas-titulo">
                <?php if(($sin_stock + $stock_bajo) > 0): ?>
                    <span class="campana">🔔</span>
                <?php else: ?>
                    <span>✅</span>
                <?php endif; ?>
                Alertas de inventario
            </div>
            <?php if(($sin_stock + $stock_bajo) > 0): ?>
                <span class="contador-alertas"><?= $sin_stock + $stock_bajo ?> productos requieren atención</span>
            <?php endif; ?>
        </div>

        <?php
        $alertas->data_seek(0);
        if($alertas->num_rows > 0):
        ?>
        <table class="tabla-alertas">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Stock actual</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            <?php while($p = $alertas->fetch_assoc()):
                if($p['stock'] == 0) {
                    $estado     = 'agotado';
                    $etiqueta   = '🚫 Agotado';
                    $porcentaje = 0;
                } elseif($p['stock'] <= 5) {
                    $estado     = 'critico';
                    $etiqueta   = '🔴 Crítico';
                    $porcentaje = ($p['stock'] / 10) * 100;
                } else {
                    $estado     = 'advertencia';
                    $etiqueta   = '🟡 Bajo';
                    $porcentaje = ($p['stock'] / 10) * 100;
                }
            ?>
            <tr>
                <td>
                    <p style="font-weight:600; margin-bottom:2px;"><?= htmlspecialchars($p['nombre']) ?></p>
                    <p style="font-size:12px; color:#aaa;">ID #<?= $p['id'] ?></p>
                </td>
                <td>
                    <span style="background:#e8f0fb; color:var(--azul); font-size:12px; padding:3px 10px; border-radius:20px; font-weight:600;">
                        <?= htmlspecialchars($p['categoria']) ?>
                    </span>
                </td>
                <td>
                    <p style="font-weight:700; font-size:16px; color:<?= $p['stock'] == 0 ? '#ff4444' : ($p['stock'] <= 5 ? '#ff4444' : '#ff9800') ?>;">
                        <?= $p['stock'] ?> unidades
                    </p>
                    <div class="barra-stock">
                        <div class="barra-fill <?= $estado ?>" style="width:<?= min($porcentaje, 100) ?>%"></div>
                    </div>
                </td>
                <td>
                    <span class="stock-chip <?= $estado ?>"><?= $etiqueta ?></span>
                </td>
                <td>
                    <a href="editar_producto.php?id=<?= $p['id'] ?>" class="btn-reponer">
                        📦 Reponer stock
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="sin-alertas">
            <p style="font-size:48px;">✅</p>
            <p style="font-size:16px; font-weight:600; color:#555; margin-top:12px;">Todo el inventario está bien</p>
            <p style="font-size:13px; margin-top:6px;">Ningún producto tiene stock bajo o agotado</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- ACCIONES RÁPIDAS -->
    <div class="acciones-rapidas">
        <h3>Acciones rápidas</h3>
        <div class="grid-acciones">
            <a href="nuevo_producto.php" class="accion-btn"><span>➕</span> Agregar producto</a>
            <a href="productos.php" class="accion-btn"><span>📋</span> Ver productos</a>
            <a href="categorias.php" class="accion-btn"><span>📂</span> Categorías</a>
            <a href="../index.php" target="_blank" class="accion-btn"><span>🌐</span> Ver tienda</a>
        </div>
    </div>
</main>

<!-- TOAST NOTIFICACIÓN -->
<?php if(($sin_stock + $stock_bajo) > 0): ?>
<div class="toast" id="toast-alerta">
    <span style="font-size:20px;">🔔</span>
    <span>
        <?php if($sin_stock > 0): ?>
            <?= $sin_stock ?> producto(s) agotado(s)<br>
        <?php endif; ?>
        <?php if($stock_bajo > 0): ?>
            <?= $stock_bajo ?> producto(s) con stock bajo
        <?php endif; ?>
    </span>
    <button class="toast-cerrar" onclick="cerrarToast()">✕</button>
</div>
<script>
setTimeout(() => {
    const t = document.getElementById('toast-alerta');
    if(t) { t.style.animation = 'none'; t.style.opacity = '0'; t.style.transition = 'opacity 0.5s'; setTimeout(() => t.remove(), 500); }
}, 6000);
function cerrarToast() {
    const t = document.getElementById('toast-alerta');
    t.style.opacity = '0'; t.style.transition = 'opacity 0.3s';
    setTimeout(() => t.remove(), 300);
}
</script>
<?php endif; ?>

</body>
</html>