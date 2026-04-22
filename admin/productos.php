<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../includes/conexion.php';

// Eliminar producto
if(isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("UPDATE productos SET activo = 0 WHERE id = $id");
    header("Location: productos.php?msg=eliminado");
    exit;
}

// Cambiar stock
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_stock'])) {
    $id    = intval($_POST['id']);
    $stock = intval($_POST['stock']);
    $conn->query("UPDATE productos SET stock = $stock WHERE id = $id");
    header("Location: productos.php?msg=stock");
    exit;
}

$productos = $conn->query("SELECT * FROM productos WHERE activo = 1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - ECONOMAX Admin</title>
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
        .contenido-admin { flex:1; padding:30px; overflow-x:auto; }
        .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
        .top-bar h2 { color:var(--azul); font-size:22px; }
        .btn-nuevo { background:var(--verde); color:white; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600; font-size:14px; transition:background 0.2s; }
        .btn-nuevo:hover { background:var(--verde-oscuro); }
        .tabla-card { background:white; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        thead { background:var(--azul); color:white; }
        th { padding:13px 16px; text-align:left; font-size:13px; font-weight:600; }
        td { padding:12px 16px; font-size:14px; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#fafafa; }
        .badge-cat { background:#e8f5ee; color:var(--verde-oscuro); font-size:11px; padding:3px 10px; border-radius:20px; font-weight:600; }
        .badge-oferta { background:#fff0f0; color:#cc0000; font-size:11px; padding:3px 10px; border-radius:20px; font-weight:600; }
        .stock-form { display:flex; align-items:center; gap:6px; }
        .stock-form input { width:60px; padding:5px 8px; border:1px solid #ddd; border-radius:6px; font-size:13px; text-align:center; }
        .stock-form button { background:var(--azul); color:white; border:none; padding:5px 10px; border-radius:6px; cursor:pointer; font-size:12px; }
        .stock-form button:hover { background:var(--azul-oscuro); }
        .btn-editar { color:var(--azul); text-decoration:none; font-size:13px; font-weight:600; padding:5px 10px; border-radius:6px; background:#e8f0fb; transition:all 0.2s; }
        .btn-editar:hover { background:var(--azul); color:white; }
        .btn-eliminar-tbl { color:#cc0000; text-decoration:none; font-size:13px; font-weight:600; padding:5px 10px; border-radius:6px; background:#fff0f0; transition:all 0.2s; margin-left:4px; }
        .btn-eliminar-tbl:hover { background:#cc0000; color:white; }
        .alerta { padding:12px 16px; border-radius:8px; margin-bottom:20px; font-weight:600; font-size:14px; }
        .alerta-ok { background:#e8f5ee; color:var(--verde-oscuro); border:1px solid #c3e6cb; }
        .precio-normal { font-weight:700; color:var(--verde); }
        .sin-productos { text-align:center; padding:50px; color:#888; }
    </style>
</head>
<body>

<aside class="sidebar-admin">
    <div class="sidebar-logo">
        <p>⚙️ ECONOMAX</p>
        <p>Panel de administración</p>
    </div>
    <nav class="sidebar-menu">
        <p class="menu-separador">Principal</p>
        <a href="index.php" class="menu-item"><span>📊</span> Dashboard</a>
        <p class="menu-separador">Catálogo</p>
        <a href="productos.php" class="menu-item activo"><span>💊</span> Productos</a>
        <a href="nuevo_producto.php" class="menu-item"><span>➕</span> Agregar producto</a>
        <a href="categorias.php" class="menu-item"><span>📂</span> Categorías</a>
        <p class="menu-separador">Más</p>
        <a href="../index.php" target="_blank" class="menu-item"><span>🌐</span> Ver tienda</a>
        <a href="logout.php" class="menu-item"><span>🚪</span> Cerrar sesión</a>
    </nav>
</aside>

<main class="contenido-admin">
    <div class="top-bar">
        <h2>💊 Productos</h2>
        <a href="nuevo_producto.php" class="btn-nuevo">➕ Agregar producto</a>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alerta alerta-ok">
            <?= $_GET['msg'] === 'eliminado' ? '✅ Producto eliminado correctamente' : '✅ Stock actualizado correctamente' ?>
        </div>
    <?php endif; ?>

    <div class="tabla-card">
        <?php if($productos->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($p = $productos->fetch_assoc()): ?>
            <tr>
                <td style="color:#aaa; font-size:13px;"><?= $p['id'] ?></td>
                <td>
                    <p style="font-weight:600; margin-bottom:2px;"><?= htmlspecialchars($p['nombre']) ?></p>
                    <?php if($p['precio_oferta']): ?>
                        <span class="badge-oferta">🏷️ En oferta</span>
                    <?php endif; ?>
                </td>
                <td><span class="badge-cat"><?= htmlspecialchars($p['categoria']) ?></span></td>
                <td>
                    <span class="precio-normal">$<?= number_format($p['precio_oferta'] ?? $p['precio'], 0, ',', '.') ?></span>
                    <?php if($p['precio_oferta']): ?>
                        <br><span style="font-size:12px;color:#aaa;text-decoration:line-through;">$<?= number_format($p['precio'], 0, ',', '.') ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" class="stock-form">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <input type="number" name="stock" value="<?= $p['stock'] ?>" min="0">
                        <button type="submit" name="actualizar_stock">✓</button>
                    </form>
                </td>
                <td>
                    <a href="editar_producto.php?id=<?= $p['id'] ?>" class="btn-editar">✏️ Editar</a>
                    <a href="productos.php?eliminar=<?= $p['id'] ?>" class="btn-eliminar-tbl"
                       onclick="return confirm('¿Eliminar este producto?')">🗑️ Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="sin-productos">
            <p style="font-size:48px;">💊</p>
            <p style="font-size:18px; font-weight:600; color:#555; margin-top:12px;">No hay productos todavía</p>
            <a href="nuevo_producto.php" style="display:inline-block;margin-top:16px;background:var(--verde);color:white;padding:10px 24px;border-radius:25px;text-decoration:none;font-weight:600;">➕ Agregar primero</a>
        </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>