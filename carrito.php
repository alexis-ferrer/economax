<?php include 'includes/conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - ECONOMAX 24 Horas</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        .pagina-carrito {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
        }
        .carrito-lista {
            background: white;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
        }
        .carrito-header {
            background: var(--azul);
            color: white;
            padding: 16px 20px;
            font-size: 17px;
            font-weight: 600;
        }
        .carrito-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }
        .carrito-item:hover { background: #fafafa; }
        .item-icono {
            font-size: 40px;
            background: #f0f8ff;
            padding: 10px;
            border-radius: 10px;
        }
        .item-info { flex: 1; }
        .item-nombre {
            font-weight: 600;
            font-size: 15px;
            color: var(--texto);
            margin-bottom: 4px;
        }
        .item-precio {
            color: var(--verde);
            font-weight: 700;
            font-size: 15px;
        }
        .item-controles {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-cantidad {
            width: 32px;
            height: 32px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-cantidad:hover { background: var(--verde); color: white; border-color: var(--verde); }
        .cantidad-num {
            font-weight: 700;
            font-size: 16px;
            min-width: 24px;
            text-align: center;
        }
        .btn-eliminar {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #ccc;
            transition: color 0.2s;
            padding: 4px;
        }
        .btn-eliminar:hover { color: #ff4444; }
        .carrito-vacio {
            padding: 60px 20px;
            text-align: center;
            color: #888;
        }
        .carrito-vacio p:first-child { font-size: 64px; margin-bottom: 16px; }
        .resumen {
            background: white;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            height: fit-content;
            position: sticky;
            top: 80px;
            overflow: hidden;
        }
        .resumen-header {
            background: var(--verde);
            color: white;
            padding: 16px 20px;
            font-size: 17px;
            font-weight: 600;
        }
        .resumen-body { padding: 20px; }
        .resumen-fila {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 10px;
            color: #555;
        }
        .resumen-fila.total {
            font-size: 18px;
            font-weight: 700;
            color: var(--texto);
            border-top: 2px solid #f0f0f0;
            padding-top: 12px;
            margin-top: 12px;
        }
        .resumen-fila.total span:last-child { color: var(--verde); }
        .btn-whatsapp {
            display: block;
            width: 100%;
            background: #25D366;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 16px;
            text-align: center;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-whatsapp:hover { background: #1da851; }
        .btn-vaciar {
            display: block;
            width: 100%;
            background: none;
            border: 1px solid #ddd;
            color: #888;
            padding: 10px;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.2s;
        }
        .btn-vaciar:hover { border-color: #ff4444; color: #ff4444; }
        .seguir-comprando {
            display: inline-block;
            margin-top: 16px;
            color: var(--azul);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        .seguir-comprando:hover { text-decoration: underline; }
        @media(max-width: 700px) {
            .pagina-carrito { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header>
    <div class="header-contenido">
        <a href="index.php" class="logo">
            <div class="logo-badge">E24</div>
            <div class="logo-texto">
                <p>ECONOMAX</p>
                <p>24 Horas • Siempre contigo</p>
            </div>
        </a>
        <div class="buscador">
            <input type="text" id="buscador-header" placeholder="Buscar medicamentos...">
            <button onclick="window.location.href='productos.php?buscar='+document.getElementById('buscador-header').value">🔍 Buscar</button>
        </div>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="productos.php">Productos</a>
            <a href="carrito.php" class="btn-carrito">🛒 Carrito <span id="badge-header">0</span></a>
        </nav>
    </div>
</header>

<!-- TÍTULO -->
<div style="max-width:1000px; margin:30px auto 0; padding:0 20px;">
    <h1 style="color:var(--azul); font-size:26px;">🛒 Mi carrito</h1>
    <p style="color:#888; font-size:14px; margin-top:4px;">Revisa tus productos antes de hacer el pedido</p>
</div>

<div class="pagina-carrito">

    <!-- LISTA DE PRODUCTOS -->
    <div class="carrito-lista">
        <div class="carrito-header">Productos en el carrito</div>
        <div id="lista-items">
            <div class="carrito-vacio">
                <p>🛒</p>
                <p style="font-size:18px; font-weight:600; color:#555;">Tu carrito está vacío</p>
                <p style="margin-top:8px; font-size:14px;">Agrega productos desde el catálogo</p>
                <a href="productos.php" style="display:inline-block; margin-top:16px; background:var(--verde); color:white; padding:10px 24px; border-radius:25px; text-decoration:none; font-weight:600;">Ver productos</a>
            </div>
        </div>
    </div>

    <!-- RESUMEN -->
    <div class="resumen">
        <div class="resumen-header">Resumen del pedido</div>
        <div class="resumen-body">
            <div class="resumen-fila">
                <span>Subtotal</span>
                <span id="subtotal">$0</span>
            </div>
            <div class="resumen-fila">
                <span>Domicilio</span>
                <span style="color:var(--verde);">A convenir</span>
            </div>
            <div class="resumen-fila total">
                <span>Total</span>
                <span id="total">$0</span>
            </div>
            <a id="btn-whatsapp" href="#" class="btn-whatsapp" target="_blank">
                📱 Pedir por WhatsApp
            </a>
            <button class="btn-vaciar" onclick="vaciarCarrito()">🗑️ Vaciar carrito</button>
            <a href="productos.php" class="seguir-comprando">← Seguir comprando</a>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer style="margin-top:60px;">
    <div class="footer-contenido">
        <div class="footer-col">
            <h4>ECONOMAX 24 Horas</h4>
            <p>Tu farmacia de confianza. Abiertos las 24 horas, los 7 días de la semana.</p>
        </div>
        <div class="footer-col">
            <h4>Contacto</h4>
            <p>📞 300 123 4567</p>
            <p>📍 Sincelejo, Sucre</p>
            <p>🕐 Abierto 24 horas</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2025 ECONOMAX 24 Horas — Todos los derechos reservados</p>
    </div>
</footer>

<script>
const TELEFONO_WHATSAPP = "573225912029"; // Cambia por tu número real

function getCarrito() {
    return JSON.parse(localStorage.getItem('carrito_economax') || '[]');
}

function saveCarrito(carrito) {
    localStorage.setItem('carrito_economax', JSON.stringify(carrito));
}

function formatPrecio(n) {
    return '$' + n.toLocaleString('es-CO');
}

function renderCarrito() {
    const carrito = getCarrito();
    const lista   = document.getElementById('lista-items');

    if(carrito.length === 0) {
        lista.innerHTML = `
            <div class="carrito-vacio">
                <p>🛒</p>
                <p style="font-size:18px;font-weight:600;color:#555;">Tu carrito está vacío</p>
                <p style="margin-top:8px;font-size:14px;">Agrega productos desde el catálogo</p>
                <a href="productos.php" style="display:inline-block;margin-top:16px;background:var(--verde);color:white;padding:10px 24px;border-radius:25px;text-decoration:none;font-weight:600;">Ver productos</a>
            </div>`;
        document.getElementById('subtotal').textContent = '$0';
        document.getElementById('total').textContent    = '$0';
        document.getElementById('btn-whatsapp').href    = '#';
        document.getElementById('badge-header').textContent = '0';
        return;
    }

    let html   = '';
    let total  = 0;
    let totalItems = 0;

    carrito.forEach(p => {
        const subtotal = p.precio * p.cantidad;
        total += subtotal;
        totalItems += p.cantidad;
        html += `
        <div class="carrito-item">
            <div class="item-icono">💊</div>
            <div class="item-info">
                <p class="item-nombre">${p.nombre}</p>
                <p class="item-precio">${formatPrecio(p.precio)} <span style="color:#aaa;font-size:13px;font-weight:400;">c/u</span></p>
                <p style="color:#888;font-size:13px;">Subtotal: ${formatPrecio(subtotal)}</p>
            </div>
            <div class="item-controles">
                <button class="btn-cantidad" onclick="cambiarCantidad(${p.id}, -1)">−</button>
                <span class="cantidad-num">${p.cantidad}</span>
                <button class="btn-cantidad" onclick="cambiarCantidad(${p.id}, 1)">+</button>
            </div>
            <button class="btn-eliminar" onclick="eliminarItem(${p.id})" title="Eliminar">✕</button>
        </div>`;
    });

    lista.innerHTML = html;
    document.getElementById('subtotal').textContent = formatPrecio(total);
    document.getElementById('total').textContent    = formatPrecio(total);
    document.getElementById('badge-header').textContent = totalItems;

    // Armar mensaje de WhatsApp
    let mensaje = "🏥 *Pedido ECONOMAX 24 Horas*\n\n";
    carrito.forEach(p => {
        mensaje += `• ${p.nombre} x${p.cantidad} = ${formatPrecio(p.precio * p.cantidad)}\n`;
    });
    mensaje += `\n*Total: ${formatPrecio(total)}*\n\nPor favor confirmar disponibilidad y domicilio. ¡Gracias!`;

    document.getElementById('btn-whatsapp').href =
        `https://wa.me/${TELEFONO_WHATSAPP}?text=${encodeURIComponent(mensaje)}`;
}

function cambiarCantidad(id, delta) {
    let carrito = getCarrito();
    const item  = carrito.find(p => p.id === id);
    if(!item) return;
    item.cantidad += delta;
    if(item.cantidad <= 0) carrito = carrito.filter(p => p.id !== id);
    saveCarrito(carrito);
    renderCarrito();
}

function eliminarItem(id) {
    let carrito = getCarrito().filter(p => p.id !== id);
    saveCarrito(carrito);
    renderCarrito();
}

function vaciarCarrito() {
    if(confirm('¿Seguro que quieres vaciar el carrito?')) {
        saveCarrito([]);
        renderCarrito();
    }
}

renderCarrito();
</script>

</body>
</html>