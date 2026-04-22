<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../includes/conexion.php';

$total_productos = $conn->query("SELECT COUNT(*) as t FROM productos")->fetch_assoc()['t'];
$total_usuarios  = $conn->query("SELECT COUNT(*) as t FROM usuarios")->fetch_assoc()['t'];
$total_cats      = $conn->query("SELECT COUNT(*) as t FROM categorias")->fetch_assoc()['t'];
$sin_stock       = $conn->query("SELECT COUNT(*) as t FROM productos WHERE stock = 0")->fetch_assoc()['t'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - ECONOMAX</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        body { display: flex; min-height: 100vh; background: #f0f4f8; }
        .sidebar-admin {
            width: 240px;
            background: var(--azul-oscuro);
            color: white;
            padding: 0;
            flex-shrink: 0;
            min-height: 100vh;
        }
        .sidebar-logo {
            background: var(--azul);
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-logo p:first-child {
            font-size: 22px;
            font-weight: bold;
        }
        .sidebar-logo p:last-child {
            font-size: 11px;
            color: #a8d4f5;
            margin-top: 2px;
        }
        .sidebar-menu { padding: 16px 0; }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: #a8d4f5;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .menu-item:hover, .menu-item.activo {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--verde-claro);
        }
        .menu-item span { font-size: 18px; }
        .menu-separador {
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            padding: 16px 20px 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .contenido-admin {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        .admin-titulo {
            font-size: 24px;
            font-weight: bold;
            color: var(--azul);
            margin-bottom: 6px;
        }
        .admin-subtitulo {
            color: #888;
            font-size: 14px;
            margin-bottom: 28px;
        }
        .grid-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .stat-icono {
            font-size: 36px;
            background: #f0f8ff;
            padding: 12px;
            border-radius: 12px;
        }
        .stat-num {
            font-size: 28px;
            font-weight: bold;
            color: var(--azul);
            line-height: 1;
        }
        .stat-label {
            font-size: 13px;
            color: #888;
            margin-top: 4px;
        }
        .acciones-rapidas {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .acciones-rapidas h3 {
            color: var(--azul);
            margin-bottom: 16px;
            font-size: 16px;
            border-left: 4px solid var(--verde);
            padding-left: 10px;
        }
        .grid-acciones {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
        }
        .accion-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 20px;
            background: #f8fafc;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            text-decoration: none;
            color: var(--texto);
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            text-align: center;
        }
        .accion-btn:hover {
            background: #e8f5ee;
            border-color: var(--verde);
            color: var(--verde);
        }
        .accion-btn span { font-size: 28px; }
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
        <a href="index.php" class="menu-item activo"><span>📊</span> Dashboard</a>
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

    <div class="grid-stats">
        <div class="stat-card">
            <div class="stat-icono">💊</div>
            <div>
                <p class="stat-num"><?= $total_productos ?></p>
                <p class="stat-label">Productos</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icono">📂</div>
            <div>
                <p class="stat-num"><?= $total_cats ?></p>
                <p class="stat-label">Categorías</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icono">👥</div>
            <div>
                <p class="stat-num"><?= $total_usuarios ?></p>
                <p class="stat-label">Usuarios</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icono" style="background:#fff0f0;">⚠️</div>
            <div>
                <p class="stat-num" style="color:#ff4444;"><?= $sin_stock ?></p>
                <p class="stat-label">Sin stock</p>
            </div>
        </div>
    </div>

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
</body>
</html>