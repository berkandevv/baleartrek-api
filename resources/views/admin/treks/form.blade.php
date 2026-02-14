<x-flash-status class="mb-4" />

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="regnumber" value="Código" />
        <x-text-input id="regnumber" name="regnumber" type="text" class="mt-1 block w-full" value="{{ old('regnumber', $trek->regnumber ?? '') }}" required />
        <x-input-error :messages="$errors->get('regnumber')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="name" value="Nombre" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $trek->name ?? '') }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
</div>

<div>
    <x-input-label for="municipality_id" value="Municipio" />
    <select id="municipality_id" name="municipality_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="">Selecciona un municipio</option>
        @foreach ($municipalities as $municipality)
            <option value="{{ $municipality->id }}" @selected(old('municipality_id', $trek->municipality_id ?? '') == $municipality->id)>
                {{ $municipality->name }} ({{ $municipality->island?->name ?? '-' }})
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('municipality_id')" class="mt-2" />
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="status" value="Estado" />
        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="y" @selected(old('status', $trek->status ?? 'y') === 'y')>Activa</option>
            <option value="n" @selected(old('status', $trek->status ?? 'y') === 'n')>Inactiva</option>
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="image" value="Imagen" />
        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" />
        @if (!empty($trek->imageUrl))
            @php
                $imageUrl = $trek->imageUrl;
                if (!\Illuminate\Support\Str::startsWith($imageUrl, ['http://', 'https://'])) {
                    $imageUrl = asset(ltrim($imageUrl, '/'));
                }
            @endphp
            <div class="mt-3 flex items-center gap-4">
                <img src="{{ $imageUrl }}" alt="Imagen actual" class="h-16 w-24 object-cover rounded-md border border-slate-200" />
                <div class="text-sm text-gray-600 break-all">
                    Actual: {{ $trek->imageUrl }}
                </div>
            </div>
        @endif
        <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>
</div>

<div>
    <x-input-label for="description" value="Descripción" />
    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $trek->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/47.3.0/ckeditor5.css" />
    @endpush

    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/47.3.0/ckeditor5.umd.js"></script>
        <script>
            (function initTrekDescriptionEditor() {
                const editorElement = document.querySelector('#description');
                if (!editorElement || typeof CKEDITOR === 'undefined') {
                    return;
                }

                if (editorElement.dataset.ckeditorInitialized === '1') {
                    return;
                }

                const { ClassicEditor, Essentials, Bold, Italic, Font, Paragraph, Undo } = CKEDITOR;

                ClassicEditor
                    .create(editorElement, {
                        licenseKey: @json(config('services.ckeditor.license_key') ?: 'GPL'),
                        plugins: [Essentials, Bold, Italic, Font, Paragraph, Undo],
                        toolbar: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|'
                        ]
                    })
                    .then(() => {
                        editorElement.dataset.ckeditorInitialized = '1';
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            })();
        </script>
    @endpush
@endonce

<div class="border-t pt-6">
    <div class="text-xs uppercase text-gray-500">Lugares remarcables</div>
    <p class="mt-1 text-sm text-gray-600">Marca los lugares y define el orden dentro de la excursión.</p>

    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
        @foreach ($interestingPlaces as $place)
            @php
                $placeOrder = old('places.' . $place->id . '.order', $selectedPlaces[$place->id] ?? 0);
                $placeSelected = old('places.' . $place->id . '.selected', array_key_exists($place->id, $selectedPlaces ?? []));
            @endphp
            <label class="flex items-center gap-3 border rounded-md p-3">
                <input type="checkbox" name="places[{{ $place->id }}][selected]" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked($placeSelected) />
                <div class="flex-1">
                    <div class="font-medium">{{ $place->name }}</div>
                    <div class="text-xs text-gray-500">{{ $place->placeType?->name }}</div>
                </div>
                <div class="w-24">
                    <x-input-label for="place-order-{{ $place->id }}" value="Orden" />
                    <x-text-input id="place-order-{{ $place->id }}" name="places[{{ $place->id }}][order]" type="number" min="0" class="mt-1 block w-full" value="{{ $placeOrder }}" />
                </div>
            </label>
        @endforeach
    </div>
</div>
