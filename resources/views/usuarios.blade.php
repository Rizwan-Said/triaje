<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<div class="container">

    <div class="cabecera">
        <h1>Usuarios registrados</h1>
    </div>

    <table class="tabla">

        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Restablecer Contraseña</th>
            </tr>
        </thead>

        <tbody>

            @foreach($usuarios as $u)

                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->rol }}</td>
                    <td>
                    {{-- boton para resetear contraseña --}}
                        <form action="/reset-password/{{ $u->id }}" method="POST">
                            @csrf
                            <button class="btn volver">
                                Resetear contraseña
                            </button>
                        </form>
                    </td>
                </tr>

            @endforeach

        </tbody>

    </table>

    <div class="acciones">
        <a href="/panel" class="btn volver">Volver</a>
    </div>

</div>

</body>

</html>