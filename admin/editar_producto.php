<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../includes/conexion.php';

$id      = intval($_GET['id'] ?? 0);
$producto = $conn->query("SELECT * FROM productos WHERE id = $id")->fetch_assoc();
if(!$producto) { header("Location: productos.php"); exit; }

$mensaje = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio      = floatval($_POST['precio']);
    $precio_of   = $_POST['precio_oferta'] ? floatval($_POST['precio_oferta']) : 'NULL';
    $categoria   = $conn->real_escape_string($_POST['categoria']);
    $stock       = intval($_POST['stock']);

    $sql = "UPDATE productos SET
                nombre='$nombre', descripcion='$descripcion',
                precio=$precio, precio_oferta=" . ($precio_of === 'NULL' ? 'NULL' : $precio_of) . ",
                categoria='$categoria', stock=$stock
            WHERE id=$id";

    if($conn->query($sql)) {
        $mensaje = "✅ Producto actualizado correctamente";
        $producto = $conn->query("SELECT * FROM productos WHERE id = $id")->fetch_assoc();
    }
}

$categorias = $conn->query("SELECT * FROM categorias WHERE activo = 1");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto - ECONOMAX Admin</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        body { display:flex; min-height:100vh; background:#f0f4f8; }
        .sidebar-admin { width:240px; background:var(--azul-oscuro); color:white; flex-shrink:0; min-height:100vh; }
        .sidebar-logo { background:var(--azul); padding:20px; text-align:center; border-bottom:1px solid rgba(255,255,255,0.1); }
        .sidebar-logo p:first-child { font-size:20px; font-weight:bold; }
        .sidebar-logo p:last-child { font-size:11px; color:#a8d4f5; }
        .sidebar-menu { padding:16px 0; }
        .menu-item { display:flex; align-items:center; gap:10px; padding:12px 20px; color:#a8d4f5; text-decoration:none; font-size:14px; transition:all 0.2s; border-left:3px solid transparent; }
        .menu-item:hover, .menu-item.activo { background:rgba(255,255,255,0.1); color:white; border-left-color:var(--verde-claro); }
        .menu-item span { font-size:18px; }
        .menu-separador { font-size:11px; color:rgba(255,255,255,0.3); padding:16px 20px 6px; text-transform:uppercase; letter-spacing:1px; }
        .contenido-admin { flex:1; padding:30px; }
        .form-card { background:white; border-radius:12px; padding:28px; max-width:620px; box-shadow:0 2px 8px rgba(0,0,0,0.06); }
        .form-card h2 { color:var(--azul); margin-bottom:6px; font-size:20px; }
        .form-card p.sub { color:#888; font-size:13px; margin-bottom:24px; }
        .campo { margin-bottom:18px; }
        .campo label { display:block; font-size:13px; font-weight:600; color:#555; margin-bottom:6px; }
        .campo input, .campo select, .campo textarea { width:100%; padding:10px 14px; border:1px solid #ddd; border-radius:8px; font-size:14px; outline:none; transition:border 0.2s; box-sizing:border-box; font-family:inherit; }
        .campo input:focus, .campo select:focus, .campo textarea:focus { border-color:var(--verde); }
        .campo textarea { resize:vertical; min-height:80px; }
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .btn-guardar { background:var(--verde); color:white; border:none; padding:12px 28px; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer; transition:background 0.2s; }
        .btn-guardar:hover { background:var(--verde-oscuro); }
        .btn-volver { color:var(--azul); text-decoration:none; font-size:14px; display:inline-block; margin-bottom:20px; }
        .alerta-ok { background:#e8f5ee; color:var(--verde-oscuro); padding:12px 16px; border-radius:8px; margin-bottom:20px; font-weight:600; border:1px solid #c3e6cb; }
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
    <a href="productos.php" class="btn-volver">← Volver a productos</a>
    <div class="form-card">
        <h2>✏️ Editar producto</h2>
        <p class="sub">Modifica los datos del producto</p>

        <?php if($mensaje): ?>
            <div class="alerta-ok"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="campo">
                <label>Nombre del producto *</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            </div>
            <div class="campo">
                <label>Descripción</label>
                <textarea name="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
            </div>
            <div class="grid-2">
                <div class="campo">
                    <label>Precio normal (COP) *</label>
                    <input type="number" name="precio" value="<?= $producto['precio'] ?>" min="0" required>
                </div>
                <div class="campo">
                    <label>Precio oferta (opcional)</label>
                    <input type="number" name="precio_oferta" value="<?= $producto['precio_oferta'] ?>" min="0">
                </div>
            </div>
            <div class="grid-2">
                <div class="campo">
                    <label>Categoría *</label>
                    <select name="categoria" required>
                        <?php while($cat = $categorias->fetch_assoc()): ?>
                            <option value="<?= $cat['nombre'] ?>" <?= $producto['categoria'] === $cat['nombre'] ? 'selected' : '' ?>>
                                <?= $cat['icono'] ?> <?= $cat['nombre'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="campo">
                    <label>Stock (unidades) *</label>
                    <input type="number" name="stock" value="<?= $producto['stock'] ?>" min="0" required>
                </div>
            </div>
            <button type="submit" class="btn-guardar">💾 Guardar cambios</button>
        </form>
    </div>
</main>
</body>
</html>