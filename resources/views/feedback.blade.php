<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dar feedback</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="container">

    <div class="cabecera">
        <h1>Dar feedback</h1>
        <p>Revisa la información del paciente de {{ $alumno->name ?? 'el alumno' }} y escribe tu valoración</p>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:20px; align-items:start;">

        <!-- COLUMNA IZQUIERDA: información del paciente -->
        <div>

            <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:20px;">
                <h2>Datos del paciente</h2>
                <p><strong>Nombre:</strong> {{ $paciente->nombre }}</p>
                <p><strong>Edad:</strong> {{ $paciente->edad }}</p>
                <p><strong>NHC:</strong> {{ $paciente->nhc }}</p>
                <p><strong>Teléfono:</strong> {{ $paciente->telefono ?? '-' }}</p>
                <p><strong>Alergias:</strong> {{ $paciente->alergias ?? '-' }}</p>
                <p><strong>Motivo de consulta:</strong> {{ $paciente->motivo_consulta ?? '-' }}</p>
            </div>

            <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:20px;">
                <h2>Clasificación del triaje</h2>
                @if($triaje)
                    <p><strong>Categoría:</strong>
                        <span class="badge {{ strtolower($triaje?->categoria ?? 'gris') }}">
                            {{ $triaje?->categoria ?? 'Sin clasificar' }}
                        </span>
                    </p>
                    <p><strong>Hora triaje:</strong> {{ $triaje?->hora_triaje ?? '-' }}</p>
                    <p><strong>Flujo:</strong> {{ $triaje?->flujo ?? '-' }}</p>
                @else
                    <p class="mensaje error">Sin triaje registrado.</p>
                @endif
            </div>

            <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                <h2>Atención médica</h2>
                @if($atencion)
                    <p><strong>Anamnesis:</strong> {{ $atencion->anamnesis ?? '-' }}</p>
                    <p><strong>Diagnóstico principal:</strong> {{ $atencion->diagnostico_principal ?? '-' }}</p>
                    <p><strong>Diagnósticos secundarios:</strong> {{ $atencion->diagnosticos_secundarios ?? '-' }}</p>
                    <p><strong>Tratamiento:</strong> {{ $atencion->tratamiento ?? '-' }}</p>
                    <p><strong>Plan de seguimiento:</strong> {{ $atencion->plan_seguimiento ?? '-' }}</p>
                @else
                    <p class="mensaje error">Sin atención registrada.</p>
                @endif
            </div>

        </div>

        <!-- COLUMNA DERECHA: formulario feedback -->
        <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); height:fit-content;">

            <h2>Tu feedback</h2>
            <p style="color:#666; margin-bottom:15px;">Escribe tu valoración sobre cómo el alumno ha resuelto este caso.</p>

            <form method="POST" action="/seguimiento/feedback">
                @csrf
                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

                <label for="feedback">Feedback</label>
                <textarea name="feedback" id="feedback" rows="12"
                    placeholder="Escribe aquí tu feedback...">{{ $atencion->feedback ?? '' }}</textarea>

                <div class="acciones" style="margin-top:20px;">
                    <a href="javascript:history.back()" class="btn volver">Volver</a>
                    <button type="submit" class="btn guardar">Enviar feedback</button>
                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>