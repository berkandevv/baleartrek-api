<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Island;
use App\Models\Municipality;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MunicipalityController extends Controller
{
    // Listado de municipios con búsqueda básica
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));

        $municipalities = Municipality::query()
            ->with(['island', 'zone'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.municipalities.index', [
            'municipalities' => $municipalities,
            'search' => $search,
        ]);
    }

    // Formulario de creación de municipio
    public function create()
    {
        $zones = Zone::query()->orderBy('name')->get();
        $islands = Island::query()->orderBy('name')->get();

        return view('admin.municipalities.create', [
            'municipality' => new Municipality(),
            'zones' => $zones,
            'islands' => $islands,
        ]);
    }

    // Guarda un municipio nuevo
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:municipalities,name'],
            'zone_id' => ['required', 'exists:zones,id'],
            'island_id' => ['required', 'exists:islands,id'],
        ]);

        Municipality::create([
            'name' => $data['name'],
            'zone_id' => (int) $data['zone_id'],
            'island_id' => (int) $data['island_id'],
        ]);

        return redirect()
            ->route('admin.municipalities.index')
            ->with('status', 'Municipio creado.');
    }

    // Vista de detalle de municipio
    public function show(Municipality $adminMunicipality)
    {
        $municipality = $adminMunicipality->load([
            'zone',
            'island',
            'treks' => fn ($query) => $query
                ->withCount('meetings')
                ->orderBy('regnumber'),
        ]);

        return view('admin.municipalities.show', [
            'municipality' => $municipality,
        ]);
    }

    // Formulario de edición de municipio
    public function edit(Municipality $adminMunicipality)
    {
        $zones = Zone::query()->orderBy('name')->get();
        $islands = Island::query()->orderBy('name')->get();

        return view('admin.municipalities.edit', [
            'municipality' => $adminMunicipality->load(['zone', 'island']),
            'zones' => $zones,
            'islands' => $islands,
        ]);
    }

    // Actualiza un municipio existente
    public function update(Request $request, Municipality $adminMunicipality)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('municipalities', 'name')->ignore($adminMunicipality->id),
            ],
            'zone_id' => ['required', 'exists:zones,id'],
            'island_id' => ['required', 'exists:islands,id'],
        ]);

        $adminMunicipality->update([
            'name' => $data['name'],
            'zone_id' => (int) $data['zone_id'],
            'island_id' => (int) $data['island_id'],
        ]);

        return redirect()
            ->route('admin.municipalities.edit', $adminMunicipality)
            ->with('status', 'Municipio actualizado.');
    }

}
