<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Control</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container">

        <div class="cabecera">
            <h1>Panel de Profesor</h1>
            <p>Bienvenido, {{ session('usuario_nombre')}}</p>
        </div>

        <h2>Gestión del sistema</h2>

        <div class="menu">

            <a href="/admision" class="card">
                <h3>Admisión</h3>
                <p>Registrar pacientes y consultar ingresos</p>
            </a>

            <a href="/seguimiento" class="card">
                <h3>Panel de seguimiento</h3>
                <p>Control general del servicio</p>
            </a>

            <a href="/registro" class="card">
                <h3>Registrar Usuarios</h3>
                <p>Dar de alta nuevos usuarios en el sistema</p>
            </a>
            
            <a href="/usuarios" class="card">
                <h3>Ver usuarios</h3>
                <p>Consultar todos los usuarios registrados</p>
            </a>

        </div>

        @if(isset($pacientes) && $pacientes->count())

            <div class="panel-pacientes">

                <h2>Tus pacientes</h2>

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

                    </tbody>

                </table>

            </div>

        @endif

        <div class="botones-final">
            <form method="POST" action="/logout">
                @csrf
                <button class="btn logout">Cerrar sesión</button>
            </form>

        </div>
    </div>
</body>

</html>