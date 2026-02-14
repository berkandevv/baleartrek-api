<x-flash-status class="mb-4" />
<div>
    <x-input-label for="name" value="Nombre" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $place->name ?? '') }}" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="latitude" value="Latitud" />
        <x-text-input id="latitude" name="latitude" type="text" class="mt-1 block w-full" placeholder="39.5696" value="{{ old('latitude', $place->latitude ?? '') }}" required />
        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="longitude" value="Longitud" />
        <x-text-input id="longitude" name="longitude" type="text" class="mt-1 block w-full" placeholder="2.6502" value="{{ old('longitude', $place->longitude ?? '') }}" required />
        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
    </div>
</div>

<div>
    <x-input-label for="place_type_id" value="Tipo de lugar" />
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
