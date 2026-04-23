<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../includes/conexion.php';

$id      = intval($_GET['id'] ?? 0);
$producto = $conn->query("SELECT * FROM productos WHERE id=$id")->fetch_assoc();
if(!$producto) { header("Location: productos.php"); exit; }

$mensaje='';
if($_SERVER['REQUEST_METHOD']==='POST') {
    $nombre      = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio      = floatval($_POST['precio']);
    $precio_of   = $_POST['precio_oferta'] ? floatval($_POST['precio_oferta']) : 'NULL';
    $categoria   = $conn->real_escape_string($_POST['categoria']);
    $stock       = intval($_POST['stock']);
    $conn->query("UPDATE productos SET nombre='$nombre',descripcion='$descripcion',precio=$precio,precio_oferta=".($precio_of==='NULL'?'NULL':$precio_of).",categoria='$categoria',stock=$stock WHERE id=$id");
    $mensaje="✅ Producto actualizado correctamente";
    $producto = $conn->query("SELECT * FROM productos WHERE id=$id")->fetch_assoc();
}
$categorias = $conn->query("SELECT * FROM categorias WHERE activo=1");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - ECONOMAX Admin</title>
    <link rel="stylesheet" href="includes/admin_styles.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<main class="contenido-admin">
    <a href="productos.php" class="btn-volver">← Volver a productos</a>
    <div class="form-card">
        <h2>✏️ Editar producto</h2>
        <p class="sub">Modifica los datos del producto</p>
        <?php if($mensaje): ?><div class="alerta alerta-ok"><?= $mensaje ?></div><?php endif; ?>
        <form method="POST">
            <div class="campo"><label>Nombre *</label><input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required></div>
            <div class="campo"><label>Descripción</label><textarea name="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></textarea></div>
            <div class="grid-2">
                <div class="campo"><label>Precio normal *</label><input type="number" name="precio" value="<?= $producto['precio'] ?>" min="0" required></div>
                <div class="campo"><label>Precio oferta</label><input type="number" name="precio_oferta" value="<?= $producto['precio_oferta'] ?>" min="0"></div>
            </div>
            <div class="grid-2">
                <div class="campo">
                    <label>Categoría *</label>
                    <select name="categoria" required>
                        <?php while($cat=$categorias->fetch_assoc()): ?>
                            <option value="<?= $cat['nombre'] ?>" <?= $producto['categoria']===$cat['nombre']?'selected':'' ?>><?= $cat['icono'] ?> <?= $cat['nombre'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="campo"><label>Stock *</label><input type="number" name="stock" value="<?= $producto['stock'] ?>" min="0" required></div>
            </div>
            <button type="submit" class="btn-guardar">💾 Guardar cambios</button>
        </form>
    </div>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('abierto');document.getElementById('overlay').classList.toggle('visible');document.getElementById('btn-hamburguesa').classList.toggle('abierto');}
function cerrarSidebar(){document.getElementById('sidebar').classList.remove('abierto');document.getElementById('overlay').classList.remove('visible');document.getElementById('btn-hamburguesa').classList.remove('abierto');}
document.addEventListener('keydown',e=>{if(e.key==='Escape')cerrarSidebar();});
</script>
</body>
</html>