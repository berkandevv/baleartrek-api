@if (session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3">
        {{ session('status') }}
    </div>
@endif

<div>
    <x-input-label for="name" value="Nombre" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $municipality->name ?? '') }}" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="zone_id" value="Zona" />
        <select id="zone_id" name="zone_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Selecciona una zona</option>
            @foreach ($zones as $zone)
                <option value="{{ $zone->id }}" @selected(old('zone_id', $municipality->zone_id ?? '') == $zone->id)>
                    {{ $zone->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('zone_id')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="island_id" value="Isla" />
        <select id="island_id" name="island_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Selecciona una isla</option>
            @foreach ($islands as $island)
                <option value="{{ $island->id }}" @selected(old('island_id', $municipality->island_id ?? '') == $island->id)>
                    {{ $island->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('island_id')" class="mt-2" />
    </div>
</div>
