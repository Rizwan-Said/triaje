<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Feedback - {{ $paciente->nombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="container">

    <div class="cabecera">
        <h1>Feedback del paciente: {{ $paciente->nombre }}</h1>
        <p>Revisa tu trabajo y la valoración de tu profesora</p>
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
                <h2>Tu atención médica</h2>
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

        <!-- COLUMNA DERECHA: feedback de la profesora -->
        <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); height:fit-content;">
            <h2>Feedback de tu profesora</h2>
            @if($atencion && $atencion->feedback)
                <p style="line-height:1.7; color:#2c3e50;">{{ $atencion->feedback }}</p>
            @else
                <p class="mensaje error">Tu profesora aún no ha dejado feedback.</p>
            @endif
        </div>

    </div>

    <a href="/mis-feedbacks" class="btn volver" style="margin-top:20px; display:inline-block;">Volver</a>

</div>

</body>
</html>