<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../includes/conexion.php';

$mensaje=''; $error='';
if($_SERVER['REQUEST_METHOD']==='POST') {
    $nombre      = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio      = floatval($_POST['precio']);
    $precio_of   = $_POST['precio_oferta'] ? floatval($_POST['precio_oferta']) : 'NULL';
    $categoria   = $conn->real_escape_string($_POST['categoria']);
    $stock       = intval($_POST['stock']);
    $sql = "INSERT INTO productos (nombre,descripcion,precio,precio_oferta,categoria,stock) VALUES ('$nombre','$descripcion',$precio,".($precio_of==='NULL'?'NULL':$precio_of).",'$categoria',$stock)";
    if($conn->query($sql)) $mensaje="✅ Producto agregado correctamente";
    else $error="❌ Error: ".$conn->error;
}
$categorias = $conn->query("SELECT * FROM categorias WHERE activo=1");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto - ECONOMAX Admin</title>
    <link rel="stylesheet" href="includes/admin_styles.css">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<main class="contenido-admin">
    <a href="productos.php" class="btn-volver">← Volver</a>
    <div class="form-card">
        <h2>➕ Nuevo producto</h2>
        <p class="sub">Completa el formulario para agregar un medicamento</p>
        <?php if($mensaje): ?><div class="alerta alerta-ok"><?= $mensaje ?></div><?php endif; ?>
        <?php if($error):   ?><div class="alerta alerta-err"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="campo"><label>Nombre *</label><input type="text" name="nombre" placeholder="Ej: Acetaminofén 500mg x10" required></div>
            <div class="campo"><label>Descripción</label><textarea name="descripcion" placeholder="Descripción del producto..."></textarea></div>
            <div class="grid-2">
                <div class="campo"><label>Precio normal (COP) *</label><input type="number" name="precio" placeholder="5000" min="0" required></div>
                <div class="campo"><label>Precio oferta (opcional)</label><input type="number" name="precio_oferta" placeholder="3500" min="0"></div>
            </div>
            <div class="grid-2">
                <div class="campo">
                    <label>Categoría *</label>
                    <select name="categoria" required>
                        <option value="">Seleccionar...</option>
                        <?php while($cat=$categorias->fetch_assoc()): ?>
                            <option value="<?= $cat['nombre'] ?>"><?= $cat['icono'] ?> <?= $cat['nombre'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="campo"><label>Stock *</label><input type="number" name="stock" placeholder="50" min="0" required></div>
            </div>
            <button type="submit" class="btn-guardar">💾 Guardar producto</button>
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