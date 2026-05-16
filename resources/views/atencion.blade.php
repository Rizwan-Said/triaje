<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Atención - Sistema de Triaje</title>

    @vite(['resources/css/app.css', 'resources/css/atencion.css', 'resources/js/app.js', 'resources/css/mts.css'])
</head>

<body>
    <x-mts />
    <div class="contenedor">

        <div class="cabecera">
            <h1>Atención médica</h1>
            <p>Registro de anamnesis, diagnóstico y tratamiento</p>
        </div>

        <div class="tarjeta-paciente">
            <div class="info-paciente">
                <h2>Datos del paciente</h2>
                <p><strong>Nombre:</strong> {{ $paciente->nombre }}</p>
                <p><strong>Edad:</strong>
                    {{ $paciente->fecha_nacimiento
                        ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age . ' años'
                        : '-' }}
                </p>
                <p><strong>NHC:</strong> {{ $paciente->nhc }}</p>
                <p><strong>Motivo de consulta:</strong> {{ $paciente->motivo_consulta }}</p>
                <p><strong>Hora triaje:</strong> {{ $triaje?->hora_triaje ?? '-' }}</p>
            </div>

            <div class="alerta-alergias">
                <h3>Alergias</h3>
                @if($paciente->alergias)
                    <p>{{ $paciente->alergias }}</p>
                @else
                    <p>Sin alergias conocidas</p>
                @endif
            </div>
        </div>

        <form action="/atencion" method="POST" class="formulario">
            @csrf

            <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

            <section class="bloque">
                <h3>Anamnesis</h3>
                <textarea name="anamnesis" rows="6">{{ $atencion->anamnesis ?? '' }}</textarea>
            </section>

            <section class="bloque">
                <h3>Diagnóstico principal</h3>
                <input type="text" name="diagnostico_principal" value="{{ $atencion->diagnostico_principal ?? '' }}">

                <label>Diagnósticos secundarios</label>
                <textarea name="diagnosticos_secundarios"
                    rows="4">{{ $atencion->diagnosticos_secundarios ?? '' }}</textarea>
            </section>

            <section class="bloque">
                <h3>Tratamiento</h3>

                <textarea name="tratamiento" rows="5">{{ $atencion->tratamiento ?? '' }}</textarea>

                <h3>Plan de seguimiento</h3>

                <textarea name="plan_seguimiento" rows="4">{{ $atencion->plan_seguimiento ?? '' }}</textarea>
            </section>

            <div class="acciones">
                <a href="{{ route('seguimiento.paciente', $paciente->id) }}" class="btn volver">
                    Volver
                </a>
                <button type="submit" class="btn guardar">Finalizar consulta</button>
            </div>

        </form>

    </div>

</body>

</html>