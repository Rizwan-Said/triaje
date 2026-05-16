<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis feedbacks</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="container">

    <div class="cabecera">
        <h1>Mis feedbacks</h1>
        <p>Valoraciones de tu profesora sobre tus pacientes</p>
    </div>

    @if($pacientes->isEmpty())
        <p class="mensaje error">Todavía no tienes feedbacks de tu profesora.</p>
    @else
        <table class="tabla">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>NHC</th>
                    <th>Motivo de consulta</th>
                    <th>Ver feedback</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pacientes as $p)
                <tr>
                    <td>{{ $p->nombre }}</td>
                    <td>{{ $p->nhc }}</td>
                    <td>{{ $p->motivo_consulta ?? '-' }}</td>
                    <td>
                        <a href="/mis-feedbacks/{{ $p->id }}" class="btn guardar" style="font-size:11px; padding:4px 8px;">Ver feedback</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="/" class="btn volver" style="margin-top:20px; display:inline-block;">Volver</a>

</div>

</body>
</html>