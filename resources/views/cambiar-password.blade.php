<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<div class="container">

    <div class="cabecera">
        <h1>Cambiar contraseña</h1>
        <p>Debe cambiar su contraseña antes de continuar</p>
    </div>

    @if(session('error'))
        <p class="mensaje error">{{ session('error') }}</p>
    @endif

    <form method="POST" action="/cambiar-password">

        @csrf

        <input type="password"
               name="password"
               placeholder="Nueva contraseña"
               required>

        <input type="password"
               name="password2"
               placeholder="Repetir contraseña"
               required>

        <button type="submit" class="btn guardar">
            Cambiar contraseña
        </button>

    </form>

</div>

</body>
</html>