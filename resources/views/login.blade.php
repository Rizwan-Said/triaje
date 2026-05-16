<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Triaje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="cajaLogin">
        <h1>Sistema de Triaje</h1>
        <h2>Iniciar Sesión</h2>
        @if(session('error'))
            <p class="error">{{ session('error') }}</p>
        @endif
        <form method="POST" action="/login">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
        <div class="linkRegistro">
            ¿Eres alumno y no tienes cuenta?<br>
            Contacta con tu Profesor
        </div>
        <p style="margin-top: 30px; font-size: 0.9em; color: #95a5a6;">
            Solo la profesora puede crear cuentas.
        </p>
    </div>
</body>
</html>