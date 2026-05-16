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

                <div class="grid-dos">
                    <input type="number" name="tension_sistolica" placeholder="TA Sistólica">
                    <input type="number" name="tension_diastolica" placeholder="TA Diastólica">
                    <input type="number" name="frecuencia_cardiaca" placeholder="Frecuencia cardíaca">
                    <input type="number" name="frecuencia_respiratoria" placeholder="Frecuencia respiratoria">
                    <input type="number" step="0.1" name="temperatura" placeholder="Temperatura">
                    <input type="number" name="saturacion_oxigeno" placeholder="Sat O2">
                    <input type="number" name="glasgow" min="3" max="15" placeholder="Glasgow">
                    <input type="number" name="eva" min="0" max="10" placeholder="Dolor EVA">
                    <input type="number" name="glucemia" placeholder="Glucemia">
                    <input type="number" step="0.01" name="peso" placeholder="Peso">
                    <input type="number" step="0.01" name="talla" placeholder="Talla">

                </div>
            </section>

            <section class="bloque">
                <h3>Observaciones clínicas</h3>
                <label>Vómitos</label>
                <select name="vomitos">
                    <option value="Sí">Sí</option>
                    <option value="No">No</option>
                </select>
                <label>Deposiciones</label>
                <input type="text" name="deposiciones">
                <label>Diuresis</label>
                <input type="text" name="diuresis">

                <label>Motivo de consulta</label>
                <textarea name="motivo_consulta"></textarea>

                <label>Observaciones</label>
                <textarea name="observaciones"></textarea>
            </section>

            <section class="bloque">
                <h3>Clasificación</h3>

                <label>Categoría</label>
                <select name="categoria" required>
                    <option value="">Seleccione categoría</option>
                    <option value="Rojo">Rojo</option>
                    <option value="Naranja">Naranja</option>
                    <option value="Amarillo">Amarillo</option>
                    <option value="Verde">Verde</option>
                    <option value="Azul">Azul</option>
                </select>

                <label>Flujo</label>
                <select name="flujo" required>
                    <option value="">Seleccione flujo</option>
                    <option value="RCP">Box RCP</option>
                    <option value="Nivel I">Nivel I</option>
                    <option value="Nivel II">Nivel II</option>
                    <option value="Traumatologia">Traumatología</option>
                    <option value="Obstetrica">Obstétrica</option>
                    <option value="Pediatria">Pediatría</option>
                    <option value="Psiquiatria">Psiquiatría</option>
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