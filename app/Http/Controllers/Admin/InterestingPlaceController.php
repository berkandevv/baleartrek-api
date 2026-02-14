<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InterestingPlace;
use App\Models\PlaceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InterestingPlaceController extends Controller
{
    // Listado de lugares remarcables con búsqueda
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));

        $places = InterestingPlace::query()
            ->with('placeType')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('gps', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.places.index', [
            'places' => $places,
            'search' => $search,
        ]);
    }

    // Formulario de creación
    public function create()
    {
        $types = PlaceType::query()->orderBy('name')->get();

        return view('admin.places.create', [
            'place' => new InterestingPlace(),
            'types' => $types,
        ]);
    }

    // Guarda un lugar nuevo
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'place_type_id' => ['required', 'exists:place_types,id'],
        ]);

        $gps = number_format((float) $data['latitude'], 6, '.', '')
            . ',' . number_format((float) $data['longitude'], 6, '.', '');
        $exists = InterestingPlace::query()->where('gps', $gps)->exists();
        if ($exists) {
            return back()
                ->withErrors(['latitude' => 'Las coordenadas ya existen.'])
                ->withInput();
        }

        InterestingPlace::create([
            'name' => $data['name'],
            'gps' => $gps,
            'place_type_id' => (int) $data['place_type_id'],
        ]);

        return redirect()
            ->route('admin.places.index')
            ->with('status', 'Lugar creado.');
    }

    // Formulario de edición
    public function edit(InterestingPlace $adminPlace)
    {
        $types = PlaceType::query()->orderBy('name')->get();

        return view('admin.places.edit', [
            'place' => $adminPlace->load('placeType'),
            'types' => $types,
        ]);
    }

    // Actualiza un lugar existente
    public function update(Request $request, InterestingPlace $adminPlace)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'place_type_id' => ['required', 'exists:place_types,id'],
        ]);

        $gps = number_format((float) $data['latitude'], 6, '.', '')
            . ',' . number_format((float) $data['longitude'], 6, '.', '');
        $exists = InterestingPlace::query()
            ->where('gps', $gps)
            ->where('id', '!=', $adminPlace->id)
            ->exists();
        if ($exists) {
            return back()
                ->withErrors(['latitude' => 'Las coordenadas ya existen.'])
                ->withInput();
        }

        $adminPlace->update([
            'name' => $data['name'],
            'gps' => $gps,
            'place_type_id' => (int) $data['place_type_id'],
        ]);

        return redirect()
            ->route('admin.places.edit', $adminPlace)
            ->with('status', 'Lugar actualizado.');
    }

    // Elimina un lugar
    public function destroy(InterestingPlace $adminPlace)
    {
        $adminPlace->delete();

        return redirect()
            ->route('admin.places.index')
            ->with('status', 'Lugar eliminado.');
    }
}
