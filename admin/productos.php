<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../includes/conexion.php';

if(isset($_GET['eliminar'])) {
    $conn->query("UPDATE productos SET activo=0 WHERE id=".intval($_GET['eliminar']));
    header("Location: productos.php?msg=eliminado"); exit;
}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['actualizar_stock'])) {
    $conn->query("UPDATE productos SET stock=".intval($_POST['stock'])." WHERE id=".intval($_POST['id']));
    header("Location: productos.php?msg=stock"); exit;
}
$productos = $conn->query("SELECT * FROM productos WHERE activo=1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - ECONOMAX Admin</title>
    <link rel="stylesheet" href="includes/admin_styles.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<main class="contenido-admin">
    <div class="top-bar">
        <h2>💊 Productos</h2>
        <a href="nuevo_producto.php" class="btn-nuevo">➕ Agregar producto</a>
    </div>
    <?php if(isset($_GET['msg'])): ?>
        <div class="alerta alerta-ok"><?= $_GET['msg']==='eliminado' ? '✅ Producto eliminado' : '✅ Stock actualizado' ?></div>
    <?php endif; ?>
    <div class="tabla-card">
        <?php if($productos->num_rows > 0): ?>
        <table>
            <thead><tr><th>#</th><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php while($p=$productos->fetch_assoc()): ?>
            <tr>
                <td style="color:#aaa;font-size:13px;"><?= $p['id'] ?></td>
                <td>
                    <p style="font-weight:600;"><?= htmlspecialchars($p['nombre']) ?></p>
                    <?php if($p['precio_oferta']): ?><span class="badge-oferta">🏷️ Oferta</span><?php endif; ?>
                </td>
                <td><span class="badge-cat"><?= htmlspecialchars($p['categoria']) ?></span></td>
                <td>
                    <span class="precio-normal">$<?= number_format($p['precio_oferta']??$p['precio'],0,',','.') ?></span>
                    <?php if($p['precio_oferta']): ?><br><span style="font-size:12px;color:#aaa;text-decoration:line-through;">$<?= number_format($p['precio'],0,',','.') ?></span><?php endif; ?>
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
                    <a href="productos.php?eliminar=<?= $p['id'] ?>" class="btn-eliminar-tbl" onclick="return confirm('¿Eliminar?')">🗑️</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="sin-productos"><p style="font-size:40px;">💊</p><p style="margin-top:12px;font-weight:600;">No hay productos</p></div>
        <?php endif; ?>
    </div>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('abierto');document.getElementById('overlay').classList.toggle('visible');document.getElementById('btn-hamburguesa').classList.toggle('abierto');}
function cerrarSidebar(){document.getElementById('sidebar').classList.remove('abierto');document.getElementById('overlay').classList.remove('visible');document.getElementById('btn-hamburguesa').classList.remove('abierto');}
document.addEventListener('keydown',e=>{if(e.key==='Escape')cerrarSidebar();});
</script>
</body>
</html>