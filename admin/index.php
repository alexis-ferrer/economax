<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
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
    <link rel="stylesheet" href="includes/admin_styles.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main class="contenido-admin">
    <p class="admin-titulo">Dashboard</p>
    <p class="admin-subtitulo">Bienvenido al panel de ECONOMAX 24 Horas</p>

    <div class="grid-stats">
        <div class="stat-card info">
            <div class="stat-icono bg-info">💊</div>
            <div><p class="stat-num info"><?= $total_productos ?></p><p class="stat-label">Productos activos</p></div>
        </div>
        <div class="stat-card info">
            <div class="stat-icono bg-info">📂</div>
            <div><p class="stat-num info"><?= $total_cats ?></p><p class="stat-label">Categorías</p></div>
        </div>
        <div class="stat-card <?= $stock_bajo > 0 ? 'advertencia' : 'ok' ?>">
            <div class="stat-icono <?= $stock_bajo > 0 ? 'bg-advertencia' : 'bg-ok' ?>">⚠️</div>
            <div><p class="stat-num <?= $stock_bajo > 0 ? 'advertencia' : 'ok' ?>"><?= $stock_bajo ?></p><p class="stat-label">Stock bajo (≤10)</p></div>
        </div>
        <div class="stat-card <?= $sin_stock > 0 ? 'peligro' : 'ok' ?>">
            <div class="stat-icono <?= $sin_stock > 0 ? 'bg-peligro' : 'bg-ok' ?>">🚫</div>
            <div><p class="stat-num <?= $sin_stock > 0 ? 'peligro' : 'ok' ?>"><?= $sin_stock ?></p><p class="stat-label">Sin stock</p></div>
        </div>
    </div>

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
        <?php $alertas->data_seek(0); if($alertas->num_rows > 0): ?>
        <div style="overflow-x:auto;">
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
                if($p['stock'] == 0)      { $estado='agotado';     $etiqueta='🚫 Agotado'; $pct=0; }
                elseif($p['stock'] <= 5)  { $estado='critico';     $etiqueta='🔴 Crítico'; $pct=($p['stock']/10)*100; }
                else                      { $estado='advertencia'; $etiqueta='🟡 Bajo';    $pct=($p['stock']/10)*100; }
            ?>
            <tr>
                <td><p style="font-weight:600;"><?= htmlspecialchars($p['nombre']) ?></p><p style="font-size:12px;color:#aaa;">ID #<?= $p['id'] ?></p></td>
                <td><span class="badge-cat"><?= htmlspecialchars($p['categoria']) ?></span></td>
                <td>
                    <p style="font-weight:700;color:<?= $p['stock']==0?'#ff4444':($p['stock']<=5?'#ff4444':'#ff9800') ?>;"><?= $p['stock'] ?> unidades</p>
                    <div class="barra-stock"><div class="barra-fill <?= $estado ?>" style="width:<?= min($pct,100) ?>%"></div></div>
                </td>
                <td><span class="stock-chip <?= $estado ?>"><?= $etiqueta ?></span></td>
                <td><a href="editar_producto.php?id=<?= $p['id'] ?>" class="btn-reponer">📦 Reponer</a></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <?php else: ?>
        <div class="sin-alertas">
            <p style="font-size:40px;">✅</p>
            <p style="font-size:16px;font-weight:600;color:#555;margin-top:10px;">Todo el inventario está bien</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="acciones-rapidas">
        <h3>Acciones rápidas</h3>
        <div class="grid-acciones">
            <a href="nuevo_producto.php" class="accion-btn"><span>➕</span> Agregar producto</a>
            <a href="productos.php"      class="accion-btn"><span>📋</span> Ver productos</a>
            <a href="categorias.php"     class="accion-btn"><span>📂</span> Categorías</a>
            <a href="../index.php" target="_blank" class="accion-btn"><span>🌐</span> Ver tienda</a>
        </div>
    </div>
</main>

<?php if(($sin_stock + $stock_bajo) > 0): ?>
<div class="toast" id="toast-alerta">
    <span style="font-size:18px;">🔔</span>
    <span><?php if($sin_stock>0) echo $sin_stock.' agotado(s) — '; if($stock_bajo>0) echo $stock_bajo.' con stock bajo'; ?></span>
    <button class="toast-cerrar" onclick="this.parentElement.remove()">✕</button>
</div>
<script>setTimeout(()=>{ const t=document.getElementById('toast-alerta'); if(t){t.style.opacity='0';t.style.transition='opacity 0.5s';setTimeout(()=>t.remove(),500);} },6000);</script>
<?php endif; ?>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('abierto');
    document.getElementById('overlay').classList.toggle('visible');
    document.getElementById('btn-hamburguesa').classList.toggle('abierto');
}
function cerrarSidebar() {
    document.getElementById('sidebar').classList.remove('abierto');
    document.getElementById('overlay').classList.remove('visible');
    document.getElementById('btn-hamburguesa').classList.remove('abierto');
}
document.addEventListener('keydown', e => { if(e.key==='Escape') cerrarSidebar(); });
</script>
</body>
</html>