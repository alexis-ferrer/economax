<?php
session_start();
include '../includes/conexion.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $usuario  = $conn->query("SELECT * FROM usuarios WHERE email='$email' AND rol='admin'")->fetch_assoc();

    if($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['admin'] = $usuario['nombre'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Correo o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - ECONOMAX</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--azul-oscuro), var(--azul));
        }
        .login-box {
            background: white;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-logo .badge {
            background: var(--verde);
            color: white;
            font-size: 28px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .login-logo p {
            color: #888;
            font-size: 14px;
        }
        .campo {
            margin-bottom: 16px;
        }
        .campo label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
        }
        .campo input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border 0.2s;
            box-sizing: border-box;
        }
        .campo input:focus { border-color: var(--verde); }
        .btn-login {
            width: 100%;
            background: var(--azul);
            color: white;
            border: none;
            padding: 13px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }
        .btn-login:hover { background: var(--azul-oscuro); }
        .error {
            background: #fff0f0;
            color: #cc0000;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
            border: 1px solid #ffcccc;
        }
    </style>
</head>
<body>
<div class="login-box">
    <div class="login-logo">
        <div class="badge">DROGUERIA ECONOMAX</div>
        <p>Panel de administración</p>
    </div>
    <?php if($error): ?>
        <div class="error">⚠️ <?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="campo">
            <label>Correo electrónico</label>
            <input type="email" name="email" placeholder="admin@economax.com" required>
        </div>
        <div class="campo">
            <label>Contraseña</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-login">Ingresar al panel</button>
    </form>
</div>
</body>
</html>