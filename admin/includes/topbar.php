<header class="topbar-admin">
    <button class="hamburguesa" id="btn-hamburguesa" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <div class="topbar-logo">
        <div class="logo-badge-small">E24</div>
        <span>ECONOMAX Admin</span>
    </div>
    <div class="topbar-der">
        <span class="admin-nombre">👤 <?= $_SESSION['admin'] ?? 'Admin' ?></span>
        <a href="logout.php" class="btn-logout-top">Salir 🚪</a>
    </div>
</header>