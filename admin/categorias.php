<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../includes/conexion.php';

$mensaje=''; $error='';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['agregar'])) {
    $nombre=$conn->real_escape_string($_POST['nombre']); $icono=$conn->real_escape_string($_POST['icono']);
    if($conn->query("INSERT INTO categorias (nombre,icono) VALUES ('$nombre','$icono')")) $mensaje="✅ Categoría agregada";
    else $error="❌ Error: ".$conn->error;
}
if(isset($_GET['eliminar'])) { $conn->query("UPDATE categorias SET activo=0 WHERE id=".intval($_GET['eliminar'])); header("Location: categorias.php?msg=eliminado"); exit; }
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['editar'])) {
    $id=intval($_POST['id']); $nombre=$conn->real_escape_string($_POST['nombre']); $icono=$conn->real_escape_string($_POST['icono']);
    if($conn->query("UPDATE categorias SET nombre='$nombre',icono='$icono' WHERE id=$id")) $mensaje="✅ Categoría actualizada";
    else $error="❌ Error: ".$conn->error;
}
$categorias = $conn->query("SELECT * FROM categorias WHERE activo=1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - ECONOMAX Admin</title>
    <link rel="stylesheet" href="includes/admin_styles.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<main class="contenido-admin">
    <div class="top-bar"><h2>📂 Categorías</h2></div>
    <?php if($mensaje): ?><div class="alerta alerta-ok"><?= $mensaje ?></div><?php endif; ?>
    <?php if($error):   ?><div class="alerta alerta-err"><?= $error ?></div><?php endif; ?>
    <?php if(isset($_GET['msg'])): ?><div class="alerta alerta-ok">✅ Categoría eliminada</div><?php endif; ?>

    <div class="pagina-cats">
        <div class="tabla-card">
            <div class="tabla-card-header">📂 Categorías activas</div>
            <?php if($categorias->num_rows > 0): ?>
            <table>
                <thead><tr><th>Ícono</th><th>Nombre</th><th>Acciones</th></tr></thead>
                <tbody>
                <?php while($cat=$categorias->fetch_assoc()): ?>
                <tr>
                    <td class="icono-preview"><?= $cat['icono'] ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($cat['nombre']) ?></td>
                    <td>
                        <button class="btn-editar-cat" onclick="cargarEditar(<?= $cat['id'] ?>,'<?= addslashes($cat['nombre']) ?>','<?= $cat['icono'] ?>')">✏️ Editar</button>
                        <a href="categorias.php?eliminar=<?= $cat['id'] ?>" class="btn-eliminar-cat" onclick="return confirm('¿Eliminar?')">🗑️ Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?><div class="sin-cats"><p>No hay categorías</p></div><?php endif; ?>
        </div>

        <div class="form-card">
            <h2 id="form-titulo">➕ Nueva categoría</h2>
            <p class="sub" id="form-sub">Agrega una nueva categoría</p>
            <form method="POST" id="form-cat">
                <input type="hidden" name="id" id="campo-id">
                <div class="campo"><label>Nombre *</label><input type="text" name="nombre" id="campo-nombre" placeholder="Ej: Dermatología" required></div>
                <div class="campo">
                    <label>Ícono (emoji) *</label>
                    <input type="text" name="icono" id="campo-icono" placeholder="Ej: 🧴" required maxlength="10">
                    <div class="iconos-rapidos">
                        <?php foreach(['💊','🌿','🧴','👶','🩺','🏷️','❤️','🦷','👁️','💉','🩹','🧬','🫁','🧠','💪','🩻','🍼','🧪','🌡️','⚕️'] as $ic): ?>
                        <button type="button" class="icono-btn" onclick="document.getElementById('campo-icono').value='<?= $ic ?>'"><?= $ic ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="divider"></div>
                <button type="submit" name="agregar" id="btn-submit" class="btn-guardar">➕ Agregar</button>
                <button type="button" id="btn-cancelar" onclick="resetForm()" style="display:none;width:100%;margin-top:8px;background:none;border:1px solid #ddd;color:#888;padding:10px;border-radius:8px;cursor:pointer;font-size:14px;">✕ Cancelar</button>
            </form>
        </div>
    </div>
</main>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('abierto');document.getElementById('overlay').classList.toggle('visible');document.getElementById('btn-hamburguesa').classList.toggle('abierto');}
function cerrarSidebar(){document.getElementById('sidebar').classList.remove('abierto');document.getElementById('overlay').classList.remove('visible');document.getElementById('btn-hamburguesa').classList.remove('abierto');}
document.addEventListener('keydown',e=>{if(e.key==='Escape')cerrarSidebar();});
function cargarEditar(id,nombre,icono){
    document.getElementById('campo-id').value=id;
    document.getElementById('campo-nombre').value=nombre;
    document.getElementById('campo-icono').value=icono;
    document.getElementById('form-titulo').textContent='✏️ Editar categoría';
    document.getElementById('form-sub').textContent='Modifica los datos';
    document.getElementById('btn-submit').name='editar';
    document.getElementById('btn-submit').textContent='💾 Guardar cambios';
    document.getElementById('btn-cancelar').style.display='block';
    document.querySelector('.form-card').scrollIntoView({behavior:'smooth'});
}
function resetForm(){
    document.getElementById('form-cat').reset();
    document.getElementById('campo-id').value='';
    document.getElementById('form-titulo').textContent='➕ Nueva categoría';
    document.getElementById('form-sub').textContent='Agrega una nueva categoría';
    document.getElementById('btn-submit').name='agregar';
    document.getElementById('btn-submit').textContent='➕ Agregar';
    document.getElementById('btn-cancelar').style.display='none';
}
</script>
</body>
</html>