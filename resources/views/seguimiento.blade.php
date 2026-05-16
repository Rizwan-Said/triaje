<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de seguimiento</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="container">

        <div class="cabecera">
            <h1>Panel de seguimiento</h1>
        </div>

        {{-- Desplegable de usuarios --}}
        <form method="GET" action="/seguimiento">
            <label for="usuario_id">Selecciona un usuario</label>
            <select name="usuario_id" id="usuario_id" onchange="this.form.submit()">
                <option value="">-- Selecciona un usuario --</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}" {{ $usuarioSeleccionado == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->email }})
                        {{ $u->id == session('usuario_id') ? '(Tú)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Tabla de pacientes --}}
        @if($usuarioSeleccionado)

            @if($pacientes->isEmpty())
                <p class="mensaje error">Este usuario no tiene pacientes registrados.</p>
            @else
                <p class="mensaje exito">Pincha en un paciente para ver su información.</p>

                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>NHC</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Fecha de llegada</th>
                            <th>Feedback</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pacientes as $p)
                            <tr style="cursor:pointer">
                                <td>{{ $p->nombre }}</td>
                                <td>{{ $p->nhc }}</td>
                                <td>
                                    <span class="badge {{ strtolower($p->categoria ?? 'gris') }}">
                                        {{ $p->categoria ?? 'Sin triaje' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="estado {{ $p->estado == 'Atendido' ? 'ok' : 'pendiente' }}">
                                        {{ $p->estado }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($p->fecha_llegada)->format('d/m/Y H:i') }}
                                </td>

                                <td>{{ $p->feedback !== null ? 'Sí' : 'No' }} </td>

                                <td>
                                    <a href="/seguimiento/paciente/{{ $p->id }}" class="btn volver"> Más información</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        @endif

        <div class="botones-final">
            <a href="/panel" class="btn volver">Volver</a>
        </div>

    </div>

</body>

</html>