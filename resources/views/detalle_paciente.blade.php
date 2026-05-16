<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del paciente</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/mts.css'])
</head>

<body>
    <x-mts />

    <div class="container">

        @php
            $esPropio = $paciente->alumno_id == session('usuario_id');
        @endphp

        <div class="cabecera">
            <h1>Detalle del paciente</h1>
            <p>Información completa registrada por {{ $alumno->name ?? 'el alumno' }}</p>
        </div>

        {{-- Datos del paciente --}}
        <div class="tarjeta-paciente">
            <div class="info-paciente">
                <h2>Datos del paciente</h2>
                @if($esPropio)
                    <a href="/admision/{{ $paciente->id }}" class="btn volver">
                        Modificar datos del paciente
                    </a>
                @endif
                <p><strong>Nombre:</strong> {{ $paciente->nombre }}</p>
                <p><strong>Edad:</strong>
                    {{ $paciente->fecha_nacimiento
                        ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age . ' años'
                        : '-' }}
                </p>
                <p><strong>NHC:</strong> {{ $paciente->nhc }}</p>
                <p><strong>Motivo de consulta:</strong> {{ $paciente->motivo_consulta }}</p>
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

        {{-- Clasificación triaje --}}
        <div
            style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:20px;">
            <h2>Clasificación del triaje</h2>

            @if($esPropio)
                <a href="/triaje/{{ $paciente->id }}" class="btn volver">
                    Modificar triaje
                </a>
            @endif

            @if($triaje)
                <p><strong>Categoría:</strong>
                    <span class="badge {{ strtolower($triaje?->categoria ?? 'gris') }}">
                        {{ $triaje?->categoria ?? 'Sin clasificar' }}
                    </span>
                </p>
                <p><strong>Hora triaje:</strong> {{ $triaje?->hora_triaje ?? '-' }}</p>
                <p><strong>Flujo:</strong> {{ $triaje?->flujo ?? '-' }}</p>

                <hr style="margin:20px 0;">

                <div class="grid-dos">
                    <p><strong>TA Sistólica:</strong> {{ $triaje?->tension_sistolica ?? '-' }} </p>
                    <p><strong>TA Diastólica:</strong> {{ $triaje?->tension_diastolica ?? '-' }}</p>
                    <p><strong>Frecuencia cardíaca:</strong> {{ $triaje?->frecuencia_cardiaca ?? '-' }}</p>
                    <p><strong>Frecuencia respiratoria:</strong>{{ $triaje?->frecuencia_respiratoria ?? '-' }} </p>
                    <p><strong>Temperatura:</strong> {{ $triaje?->temperatura ?? '-' }} </p>
                    <p><strong>Sat O2:</strong> {{ $triaje?->saturacion_oxigeno ?? '-' }} </p>
                    <p><strong>Glasgow:</strong> {{ $triaje?->glasgow ?? '-' }} </p>
                    <p><strong>Dolor EVA:</strong> {{ $triaje?->eva ?? '-' }} </p>
                    <p><strong>Glucemia:</strong> {{ $triaje?->glucemia ?? '-' }} </p>
                    <p><strong>Peso:</strong> {{ $triaje?->peso ?? '-' }} </p>
                    <p><strong>Talla:</strong>{{ $triaje?->talla ?? '-' }} </p>
                </div>

                <hr style="margin:20px 0;">

                <h3>Observaciones clínicas</h3>
                <p><strong>Vómitos:</strong> {{ $triaje?->vomitos ? 'Sí' : 'No' }} </p>

                <p> <strong>Deposiciones:</strong>
                    {{ $triaje?->deposiciones ? 'Sí' : 'No' }} </p>

                <p>
                    <strong>Diuresis:</strong>
                    {{ $triaje?->diuresis ? 'Sí' : 'No' }}
                </p>

                <p><strong>Motivo consulta:</strong>
                    {{ $triaje?->motivo_consulta ?? '-' }}
                </p>
                <p><strong>Observaciones:</strong>
                    {{ $triaje?->observaciones ?? '-' }}
                </p>
            @else
                <p class="mensaje error">Sin triaje registrado.</p>
            @endif
        </div>

        {{-- Atención médica --}}
        <div
            style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:20px;">
            <h2>Atención médica</h2>
            @if($esPropio)
                @if($triaje && $triaje->categoria && $triaje->flujo)

                    <a href="/atencion/{{ $paciente->id }}" class="btn volver">
                        Modificar atención
                    </a>

                @else

                    <span class="btn bloqueado">
                        Primero debe realizarse el triaje
                    </span>

                @endif
            @endif

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

        <div class="acciones">
            <a href="/panel" class="btn volver">Volver</a>
            @if(session()->has('usuario_id') && session('rol') == 'profesor' && !$esPropio)
                <a href="/seguimiento/feedback/{{ $paciente->id }}" class="btn guardar">
                    Dar feedback
                </a>
            @endif
        </div>

    </div>

</body>

</html>