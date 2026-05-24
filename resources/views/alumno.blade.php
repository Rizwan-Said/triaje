<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio - Triaje</title>

    @vite(['resources/css/app.css', 'resources/css/mts.css', 'resources/js/app.js'])
</head>

<body>
    <x-mts />

    <main class="contenedor dashboard">

        <div class="cabecera-dashboard">

            <div>
                <h1>Bienvenido, {{ session('usuario_nombre')}}</h1>
                <p>Selecciona una opción del sistema</p>
            </div>

            <form method="POST" action="/logout">
                @csrf
                <button class="btn logout">Cerrar sesión</button>
            </form>
        </div>


        <div class="menu">

            <a href="/admision" class="card">
                <h3>Registrar paciente</h3>
                <p>Alta de pacientes en urgencias</p>
            </a>

            <a href="/mis-feedbacks" class="card">
                <h3>Mis feedbacks</h3>
                <p>Consulta las valoraciones de tu profesora</p>
            </a>

        </div>

        @if(isset($pacientes))

            <div class="panel-pacientes">

                <h2>Tus pacientes</h2>

                <div class="busqueda">
                    <form method="GET" action="/">
                        <label for="buscar" class="sr-only">Buscar paciente</label>
                        <input type="text" id="buscar" name="buscar" placeholder="Buscar por nombre o NHC" value="{{ request('buscar') }}">
                        <button type="submit" class="btn buscar-btn">Buscar</button>
                        <a href="/" class="btn volver">Limpiar</a>
                    </form>
                </div>

                <table class="tabla">

                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>NHC</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Fecha llegada</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>

        @if($pacientes->count())

            @foreach($pacientes as $p)
                <tr>
                    <td>{{ $p->nombre }}</td>
                    <td>{{ $p->nhc }}</td>
                    <td>
                        @php
                            $categoria = strtolower(trim($p->categoria ?? 'gris'));
                        @endphp
                        <span class="badge {{ $categoria }}">
                            {{ $p->categoria ?? 'Sin triaje' }}
                        </span>
                    </td>
                    <td>{{ $p->estado }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($p->fecha_llegada)->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <a href="{{ route('pacientes.show', $p->id) }}" class="btn volver">
                            Ver
                        </a>
                    </td>
                </tr>
            @endforeach

        @else
            <tr>
                <td colspan="6" style="text-align:center; padding:20px;">
                    No se encontraron pacientes
                </td>
            </tr>
            @endif
                    </tbody>
                </table>
            </div>

        @endif
    </main>
</body>

</html>