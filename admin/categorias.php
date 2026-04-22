<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../includes/conexion.php';

$mensaje = '';
$error   = '';

// Agregar categoría
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $icono  = $conn->real_escape_string($_POST['icono']);
    
    if($conn->query("INSERT INTO categorias (nombre, icono) VALUES ('$nombre', '$icono')")) {
        $mensaje = "✅ Categoría agregada correctamente";
    } else {
        $error = "❌ Error al agregar: " . $conn->error;
    }
}

// Eliminar categoría
if(isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("UPDATE categorias SET activo = 0 WHERE id = $id");
    header("Location: categorias.php?msg=eliminado");
    exit;
}

// Editar categoría
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id     = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $icono  = $conn->real_escape_string($_POST['icono']);
    
    if($conn->query("UPDATE categorias SET nombre='$nombre', icono='$icono' WHERE id=$id")) {
        $mensaje = "✅ Categoría actualizada correctamente";
    } else {
        $error = "❌ Error al actualizar: " . $conn->error;
    }
}

$categorias = $conn->query("SELECT * FROM categorias WHERE activo = 1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías - ECONOMAX Admin</title>
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
        .contenido-admin { flex:1; padding:30px; }
        .pagina-cats { display:grid; grid-template-columns:1fr 360px; gap:24px; max-width:1100px; }
        .tabla-card { background:white; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden; }
        .tabla-card-header { background:var(--azul); color:white; padding:16px 20px; font-size:16px; font-weight:600; }
        table { width:100%; border-collapse:collapse; }
        thead { background:#f8fafc; }
        th { padding:12px 16px; text-align:left; font-size:13px; font-weight:600; color:#555; border-bottom:1px solid #f0f0f0; }
        td { padding:12px 16px; font-size:14px; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#fafafa; }
        .icono-preview { font-size:28px; text-align:center; }
        .form-card { background:white; border-radius:12px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.06); height:fit-content; position:sticky; top:20px; }
        .form-card h3 { color:var(--azul); margin-bottom:6px; font-size:17px; }
        .form-card p.sub { color:#888; font-size:13px; margin-bottom:20px; }
        .campo { margin-bottom:16px; }
        .campo label { display:block; font-size:13px; font-weight:600; color:#555; margin-bottom:6px; }
        .campo input { width:100%; padding:10px 14px; border:1px solid #ddd; border-radius:8px; font-size:14px; outline:none; transition:border 0.2s; box-sizing:border-box; }
        .campo input:focus { border-color:var(--verde); }
        .iconos-rapidos { display:flex; flex-wrap:wrap; gap:8px; margin-top:8px; }
        .icono-btn { font-size:22px; padding:6px; border-radius:8px; cursor:pointer; border:1px solid #eee; background:white; transition:all 0.2s; }
        .icono-btn:hover { background:#e8f5ee; border-color:var(--verde); transform:scale(1.1); }
        .btn-guardar { width:100%; background:var(--verde); color:white; border:none; padding:12px; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer; transition:background 0.2s; margin-top:4px; }
        .btn-guardar:hover { background:var(--verde-oscuro); }
        .btn-editar-cat { color:var(--azul); font-size:13px; font-weight:600; padding:5px 10px; border-radius:6px; background:#e8f0fb; border:none; cursor:pointer; transition:all 0.2s; }
        .btn-editar-cat:hover { background:var(--azul); color:white; }
        .btn-eliminar-cat { color:#cc0000; text-decoration:none; font-size:13px; font-weight:600; padding:5px 10px; border-radius:6px; background:#fff0f0; margin-left:4px; transition:all 0.2s; display:inline-block; }
        .btn-eliminar-cat:hover { background:#cc0000; color:white; }
        .alerta { padding:12px 16px; border-radius:8px; margin-bottom:20px; font-weight:600; font-size:14px; }
        .alerta-ok  { background:#e8f5ee; color:var(--verde-oscuro); border:1px solid #c3e6cb; }
        .alerta-err { background:#fff0f0; color:#cc0000; border:1px solid #ffcccc; }
        .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
        .top-bar h2 { color:var(--azul); font-size:22px; }
        .sin-cats { text-align:center; padding:40px; color:#888; font-size:14px; }
        .divider { height:1px; background:#f0f0f0; margin:16px 0; }
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
        <a href="index.php" class="menu-item"><span>📊</span> Dashboard</a>
        <p class="menu-separador">Catálogo</p>
        <a href="productos.php" class="menu-item"><span>💊</span> Productos</a>
        <a href="nuevo_producto.php" class="menu-item"><span>➕</span> Agregar producto</a>
        <a href="categorias.php" class="menu-item activo"><span>📂</span> Categorías</a>
        <p class="menu-separador">Más</p>
        <a href="../index.php" target="_blank" class="menu-item"><span>🌐</span> Ver tienda</a>
        <a href="logout.php" class="menu-item"><span>🚪</span> Cerrar sesión</a>
    </nav>
</aside>

<!-- CONTENIDO -->
<main class="contenido-admin">
    <div class="top-bar">
        <h2>📂 Categorías</h2>
    </div>

    <?php if($mensaje): ?>
        <div class="alerta alerta-ok"><?= $mensaje ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alerta alerta-err"><?= $error ?></div>
    <?php endif; ?>
    <?php if(isset($_GET['msg'])): ?>
        <div class="alerta alerta-ok">✅ Categoría eliminada correctamente</div>
    <?php endif; ?>

    <div class="pagina-cats">

        <!-- TABLA DE CATEGORÍAS -->
        <div>
            <div class="tabla-card">
                <div class="tabla-card-header">📂 Categorías activas</div>
                <?php if($categorias->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Ícono</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($cat = $categorias->fetch_assoc()): ?>
                    <tr>
                        <td class="icono-preview"><?= $cat['icono'] ?></td>
                        <td style="font-weight:600;"><?= htmlspecialchars($cat['nombre']) ?></td>
                        <td>
                            <button class="btn-editar-cat"
                                onclick="cargarEditar(<?= $cat['id'] ?>, '<?= addslashes($cat['nombre']) ?>', '<?= $cat['icono'] ?>')">
                                ✏️ Editar
                            </button>
                            <a href="categorias.php?eliminar=<?= $cat['id'] ?>"
                               class="btn-eliminar-cat"
                               onclick="return confirm('¿Eliminar esta categoría?')">
                               🗑️ Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="sin-cats">
                    <p style="font-size:40px;">📂</p>
                    <p style="margin-top:8px;">No hay categorías todavía</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- FORMULARIO -->
        <div class="form-card">
            <h3 id="form-titulo">➕ Nueva categoría</h3>
            <p class="sub" id="form-sub">Agrega una nueva categoría al catálogo</p>

            <form method="POST" id="form-cat">
                <input type="hidden" name="id" id="campo-id" value="">

                <div class="campo">
                    <label>Nombre de la categoría *</label>
                    <input type="text" name="nombre" id="campo-nombre"
                           placeholder="Ej: Dermatología" required>
                </div>

                <div class="campo">
                    <label>Ícono (emoji) *</label>
                    <input type="text" name="icono" id="campo-icono"
                           placeholder="Ej: 🧴" required maxlength="10">
                    <div class="iconos-rapidos">
                        <?php
                        $iconos = ['💊','🌿','🧴','👶','🩺','🏷️','❤️','🦷','👁️','💉','🩹','🧬','🫁','🧠','💪','🩻','🍼','🧪','🌡️','⚕️'];
                        foreach($iconos as $ic):
                        ?>
                        <button type="button" class="icono-btn"
                                onclick="document.getElementById('campo-icono').value='<?= $ic ?>'"
                                title="<?= $ic ?>"><?= $ic ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="divider"></div>

                <button type="submit" name="agregar" id="btn-submit" class="btn-guardar">
                    ➕ Agregar categoría
                </button>
                <button type="button" id="btn-cancelar"
                        onclick="resetForm()"
                        style="display:none; width:100%; margin-top:8px; background:none; border:1px solid #ddd; color:#888; padding:10px; border-radius:8px; cursor:pointer; font-size:14px;">
                    ✕ Cancelar edición
                </button>
            </form>
        </div>
    </div>
</main>

<script>
function cargarEditar(id, nombre, icono) {
    document.getElementById('campo-id').value    = id;
    document.getElementById('campo-nombre').value = nombre;
    document.getElementById('campo-icono').value  = icono;
    document.getElementById('form-titulo').textContent = '✏️ Editar categoría';
    document.getElementById('form-sub').textContent    = 'Modifica los datos de la categoría';
    document.getElementById('btn-submit').name         = 'editar';
    document.getElementById('btn-submit').textContent  = '💾 Guardar cambios';
    document.getElementById('btn-cancelar').style.display = 'block';
    document.querySelector('.form-card').scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('form-cat').reset();
    document.getElementById('campo-id').value          = '';
    document.getElementById('form-titulo').textContent  = '➕ Nueva categoría';
    document.getElementById('form-sub').textContent     = 'Agrega una nueva categoría al catálogo';
    document.getElementById('btn-submit').name          = 'agregar';
    document.getElementById('btn-submit').textContent   = '➕ Agregar categoría';
    document.getElementById('btn-cancelar').style.display = 'none';
}
</script>

</body>
</html>