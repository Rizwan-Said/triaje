<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Triaje - Sistema de Triaje</title>

    @vite(['resources/css/app.css', 'resources/css/triaje.css', 'resources/js/app.js', 'resources/css/mts.css'])

</head>

<body>
    <x-mts />

    <div class="contenedor">

        <div class="cabecera">
            <h1>Evaluación de Triaje</h1>
            <p>Clasifique al paciente según la gravedad de su condición</p>
        </div>

        {{-- Mensajes --}}
        @if(session('ok'))
            <p class="mensaje exito">Triaje guardado correctamente.</p>
        @endif

        @if(session('error'))
            <p class="mensaje error">No se pudo guardar el triaje.</p>
        @endif

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

        <form action="/triaje" method="POST" class="formulario">
            @csrf

            <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                <section class="bloque">
                <h3>Constantes vitales</h3>

                <div class="grid-dos">  {{-- recibe datos de triaje de la base de datos --}}
                    <input type="number" name="tension_sistolica" placeholder="TA Sistólica" value="{{ $triaje->tension_sistolica ?? '' }}"> 
                    <input type="number" name="tension_diastolica" placeholder="TA Diastólica" value="{{ $triaje->tension_diastolica ?? '' }}">
                    <input type="number" name="frecuencia_cardiaca" placeholder="Frecuencia cardíaca" value="{{ $triaje->frecuencia_cardiaca ?? '' }}">
                    <input type="number" name="frecuencia_respiratoria" placeholder="Frecuencia respiratoria" value="{{ $triaje->frecuencia_respiratoria ?? '' }}">
                    <input type="number" step="0.1" name="temperatura" placeholder="Temperatura" value="{{ $triaje->temperatura ?? '' }}">
                    <input type="number" name="saturacion_oxigeno" placeholder="Sat O2" value="{{ $triaje->saturacion_oxigeno ?? '' }}">
                    <input type="number" name="glasgow" min="3" max="15" placeholder="Glasgow" value="{{ $triaje->glasgow ?? '' }}">
                    <input type="number" name="eva" min="0" max="10" placeholder="Dolor EVA" value="{{ $triaje->eva ?? '' }}">
                    <input type="number" name="glucemia" placeholder="Glucemia" value="{{ $triaje->glucemia ?? '' }}">
                    <input type="number" step="0.01" name="peso" placeholder="Peso" value="{{ $triaje->peso ?? '' }}">
                    <input type="number" step="0.01" name="talla" placeholder="Talla" value="{{ $triaje->talla ?? '' }}">
                </div>
                </section>

                <section class="bloque">
                <h3>Observaciones clínicas</h3>
                <label>Vómitos</label>
                <select name="vomitos">
                    <option value="Sí" {{ ($triaje->vomitos ?? '') == 'Sí' ? 'selected' : '' }}> Sí</option>
                    <option value="No" {{ ($triaje->vomitos ?? '') == 'No' ? 'selected' : '' }}> No</option>
                </select>

                <label>Deposiciones</label>
                <input type="text"
                    name="deposiciones"
                    value="{{ $triaje->deposiciones ?? '' }}">

                <label>Diuresis</label>
                <input type="text"
                    name="diuresis"
                    value="{{ $triaje->diuresis ?? '' }}">

                <label>Motivo de consulta</label>
                <textarea name="motivo_consulta">{{ $triaje->motivo_consulta ?? '' }}</textarea>
                <label>Observaciones</label>
                <textarea name="observaciones">{{ $triaje->observaciones ?? '' }}</textarea>

                </section>

            <section class="bloque">
            <h3>Clasificación</h3>
            <label>Categoría</label>
            <select name="categoria" required>
                <option value="">Seleccione categoría</option>
                <option value="Rojo" {{ ($triaje->categoria ?? '') == 'Rojo' ? 'selected' : '' }}>Rojo</option>
                <option value="Naranja" {{ ($triaje->categoria ?? '') == 'Naranja' ? 'selected' : '' }}>Naranja</option>
                <option value="Amarillo" {{ ($triaje->categoria ?? '') == 'Amarillo' ? 'selected' : '' }}>Amarillo</option>
                <option value="Verde" {{ ($triaje->categoria ?? '') == 'Verde' ? 'selected' : '' }}>Verde</option>
                <option value="Azul" {{ ($triaje->categoria ?? '') == 'Azul' ? 'selected' : '' }}>Azul</option>
            </select>

                <label>Flujo</label>
                <select name="flujo" required>
                <option value="RCP" {{ ($triaje->flujo ?? '') == 'RCP' ? 'selected' : '' }}>Box RCP</option>
                <option value="Nivel I" {{ ($triaje->flujo ?? '') == 'Nivel I' ? 'selected' : '' }}>Nivel I</option>
                <option value="Nivel II" {{ ($triaje->flujo ?? '') == 'Nivel II' ? 'selected' : '' }}>Nivel II</option>
                <option value="Traumatologia" {{ ($triaje->flujo ?? '') == 'Traumatologia' ? 'selected' : '' }}>Traumatología</option>
                <option value="Obstetrica" {{ ($triaje->flujo ?? '') == 'Obstetrica' ? 'selected' : '' }}>Obstétrica</option>
                <option value="Pediatria" {{ ($triaje->flujo ?? '') == 'Pediatria' ? 'selected' : '' }}>Pediatría</option>
                <option value="Psiquiatria" {{ ($triaje->flujo ?? '') == 'Psiquiatria' ? 'selected' : '' }}>Psiquiatría</option>
                </select>
            </section>

            <div class="acciones">
                <a href="{{ route('seguimiento.paciente', $paciente->id) }}" class="btn volver">
                    Volver
                </a>
                <button type="submit" class="btn guardar">Confirmar triaje</button>
            </div>

        </form>

    </div>

</body>

</html>