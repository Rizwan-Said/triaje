<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Admisión - Triaje</title>

    @vite(['resources/css/app.css', 'resources/css/admision.css', 'resources/js/app.js'])
</head>

<body>

    <div class="container">
        <div class="cabecera">
            <h1>Admisión de Pacientes</h1>
            <p>Registro de nuevos pacientes en el sistema</p>
        </div>

        {{-- Mensajes --}}
        @if(session('ok'))
            <p class="mensaje exito">Paciente registrado correctamente</p>
        @endif

        @if(session('error'))
            <p class="mensaje error">Error al registrar paciente</p>
        @endif

        <form action="{{ isset($paciente) ? '/admision/update/' . $paciente->id : '/admision' }}" method="POST">
            @csrf
            <section class="bloque">
                <div class="grupo">
                    <label>NHC</label>
                    <input type="text" name="nhc" required value="{{ $paciente->nhc ?? '' }}">
                </div>

                <div class="grupo">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required value="{{ $paciente->nombre ?? '' }}">
                </div>


                <div>
                <label>Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento"
                        value="{{ $paciente->fecha_nacimiento ?? '' }}">
                </div>

                <div class="grupo">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="{{ $paciente->telefono ?? '' }}">
                </div>

                <div class="grupo">
                    <label>Alergias</label>
                    <textarea name="alergias">{{ $paciente->alergias ?? '' }}</textarea>
                </div>

                <div class="grupo">
                    <label>Motivo de consulta</label>
                    <textarea name="motivo_consulta">{{ $paciente->motivo_consulta ?? '' }}</textarea>
                </div>
            </section>
            <div class="acciones">
                <a class="btn volver" href="/">Volver</a>

                <button type="submit" class="btn guardar">
                    {{ isset($paciente) ? 'Actualizar paciente' : 'Registrar paciente' }}
                </button>
            </div>

        </form>


    </div>

</body>

</html>