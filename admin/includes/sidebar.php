<?php $pagina_actual = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar-admin" id="sidebar">
    <nav class="sidebar-menu">
        <p class="menu-separador">Principal</p>
        <a href="index.php" class="menu-item <?= $pagina_actual === 'index.php' ? 'activo' : '' ?>">
            <span>📊</span> Dashboard
            <?php
            $alertas_count = $conn->query("SELECT COUNT(*) as t FROM productos WHERE activo = 1 AND stock <= 10")->fetch_assoc()['t'];
            if($alertas_count > 0): ?>
                <span class="badge-alerta"><?= $alertas_count ?></span>
            <?php endif; ?>
        </a>
        <p class="menu-separador">Catálogo</p>
        <a href="productos.php" class="menu-item <?= $pagina_actual === 'productos.php' ? 'activo' : '' ?>">
            <span>💊</span> Productos
        </a>
        <a href="nuevo_producto.php" class="menu-item <?= $pagina_actual === 'nuevo_producto.php' ? 'activo' : '' ?>">
            <span>➕</span> Agregar producto
        </a>
        <a href="categorias.php" class="menu-item <?= $pagina_actual === 'categorias.php' ? 'activo' : '' ?>">
            <span>📂</span> Categorías
        </a>
        <p class="menu-separador">Más</p>
        <a href="../index.php" class="menu-item" target="_blank"><span>🌐</span> Ver tienda</a>
        <a href="logout.php" class="menu-item"><span>🚪</span> Cerrar sesión</a>
    </nav>
</aside>
<div class="overlay-sidebar" id="overlay" onclick="cerrarSidebar()"></div>