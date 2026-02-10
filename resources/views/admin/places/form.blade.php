@if (session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3">
        {{ session('status') }}
    </div>
@endif

<div>
    <x-input-label for="name" :value="__('Nombre')" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $place->name ?? '') }}" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

@php
    $gpsParts = isset($place) && !empty($place->gps) ? explode(',', $place->gps, 2) : [];
    $gpsLat = $gpsParts[0] ?? '';
    $gpsLng = $gpsParts[1] ?? '';
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="latitude" :value="__('Latitud')" />
        <x-text-input id="latitude" name="latitude" type="text" class="mt-1 block w-full" placeholder="39.5696" value="{{ old('latitude', $gpsLat) }}" required />
        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="longitude" :value="__('Longitud')" />
        <x-text-input id="longitude" name="longitude" type="text" class="mt-1 block w-full" placeholder="2.6502" value="{{ old('longitude', $gpsLng) }}" required />
        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
    </div>
</div>

<div>
    <x-input-label for="place_type_id" :value="__('Tipo de lugar')" />
    <select id="place_type_id" name="place_type_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="">Selecciona un tipo</option>
        @foreach ($types as $type)
            <option value="{{ $type->id }}" @selected(old('place_type_id', $place->place_type_id ?? '') == $type->id)>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('place_type_id')" class="mt-2" />
</div>
