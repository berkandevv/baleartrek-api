<x-flash-status class="mb-4" />
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="trek_id" value="Excursión" />
        <select id="trek_id" name="trek_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Selecciona una excursión</option>
            @foreach ($treks as $trek)
                <option value="{{ $trek->id }}" @selected(old('trek_id', $meeting->trek_id ?? '') == $trek->id)>
                    {{ $trek->regnumber }} - {{ $trek->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('trek_id')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="user_id" value="Guía principal" />
        <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Selecciona un guía</option>
            @foreach ($guides as $guide)
                <option value="{{ $guide->id }}" @selected(old('user_id', $meeting->user_id ?? '') == $guide->id)>
                    {{ $guide->lastname }} {{ $guide->name }} ({{ $guide->email }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
    </div>
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="day" value="Día del encuentro" />
        <x-text-input id="day" name="day" type="date" class="mt-1 block w-full" value="{{ old('day', isset($meeting->day) ? $meeting->day->format('Y-m-d') : '') }}" required />
        <x-input-error :messages="$errors->get('day')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="hour" value="Hora" />
        @php
            $hourValue = old('hour', $meeting->hour ?? '');
            if ($hourValue) {
                $hourValue = \Carbon\Carbon::parse($hourValue)->format('H:i');
            }
        @endphp
        <x-text-input id="hour" name="hour" type="time" class="mt-1 block w-full" value="{{ $hourValue }}" required />
        <x-input-error :messages="$errors->get('hour')" class="mt-2" />
    </div>
</div>

@php
    $dayValue = old('day', $meeting->day ?? null);
    $appDateIniValue = $dayValue ? \Carbon\Carbon::parse($dayValue)->subMonthNoOverflow()->format('d/m/Y') : null;
    $appDateEndValue = $dayValue ? \Carbon\Carbon::parse($dayValue)->subWeek()->format('d/m/Y') : null;
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <div class="text-xs uppercase text-gray-500">Inicio inscripción</div>
        <div id="appDateIniPreview" class="mt-1 font-medium text-gray-900">{{ $appDateIniValue ?? '-' }}</div>
        <div class="text-sm text-gray-500">Se calcula como 1 mes antes del encuentro.</div>
    </div>
    <div>
        <div class="text-xs uppercase text-gray-500">Fin inscripción</div>
        <div id="appDateEndPreview" class="mt-1 font-medium text-gray-900">{{ $appDateEndValue ?? '-' }}</div>
        <div class="text-sm text-gray-500">Se calcula como 1 semana antes del encuentro.</div>
    </div>
</div>

<script>
    (function () {
        const dayInput = document.getElementById('day');
        const iniEl = document.getElementById('appDateIniPreview');
        const endEl = document.getElementById('appDateEndPreview');

        if (!dayInput || !iniEl || !endEl) return;

        const formatDate = (date) => {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        };

        const updatePreview = () => {
            if (!dayInput.value) {
                iniEl.textContent = '-';
                endEl.textContent = '-';
                return;
            }

            const base = new Date(dayInput.value + 'T00:00:00');
            if (isNaN(base.getTime())) {
                iniEl.textContent = '-';
                endEl.textContent = '-';
                return;
            }

            const ini = new Date(base);
            ini.setMonth(ini.getMonth() - 1);

            // Ajuste para evitar desbordes de fin de mes
            if (ini.getDate() !== base.getDate()) {
                ini.setDate(0);
            }

            const end = new Date(base);
            end.setDate(end.getDate() - 7);

            iniEl.textContent = formatDate(ini);
            endEl.textContent = formatDate(end);
        };

        dayInput.addEventListener('input', updatePreview);
        updatePreview();
    })();
</script>
