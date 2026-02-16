<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InterestingPlace;
use App\Models\Municipality;
use App\Models\Trek;
use App\Services\TrekImageService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrekController extends Controller
{
    public function __construct(private TrekImageService $trekImageService)
    {
    }

    // Listado de excursiones con búsqueda básica
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));

        $treks = Trek::query()
            ->with('municipality.island')
            ->withCount('interestingPlaces')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('regnumber', 'like', "%{$search}%");
                });
            })
            ->orderBy('regnumber')
            ->paginate(20)
            ->withQueryString();

        return view('admin.treks.index', [
            'treks' => $treks,
            'search' => $search,
        ]);
    }

    // Formulario de creación de excursión
    public function create()
    {
        $municipalities = Municipality::query()
            ->with('island')
            ->orderBy('name')
            ->get();

        $interestingPlaces = InterestingPlace::query()
            ->with('placeType')
            ->orderBy('name')
            ->get();

        return view('admin.treks.create', [
            'trek' => new Trek(),
            'municipalities' => $municipalities,
            'interestingPlaces' => $interestingPlaces,
            'selectedPlaces' => [],
        ]);
    }

    // Guarda una excursión nueva
    public function store(Request $request)
    {
        $data = $request->validate([
            'regnumber' => ['required', 'string', 'max:255', 'unique:treks,regnumber'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['nullable', Rule::in(['y', 'n'])],
            'municipality_id' => ['required', 'exists:municipalities,id'],
            'places' => ['array'],
            'places.*.selected' => ['nullable', 'in:1'],
            'places.*.order' => ['nullable', 'integer', 'min:0'],
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $this->trekImageService->storeUploadedImage($request->file('image'));
        }

        $trek = Trek::create([
            'regnumber' => mb_strtoupper($data['regnumber']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'imageUrl' => $imageUrl,
            'status' => $data['status'] ?? 'y',
            'municipality_id' => (int) $data['municipality_id'],
        ]);

        $syncData = $this->buildPlaceSync($request);
        if ($syncData !== []) {
            $trek->interestingPlaces()->sync($syncData);
        }

        return redirect()
            ->route('admin.treks.edit', $trek)
            ->with('status', 'Excursión creada');
    }

    // Vista de detalle de excursión
    public function show(Trek $adminTrek)
    {
        $trek = $adminTrek->load([
            'municipality.zone',
            'municipality.island',
            'interestingPlaces' => fn ($query) => $query->with('placeType')->orderBy('interesting_place_trek.order'),
            'meetings' => fn ($query) => $query
                ->with('user')
                ->withCount('comments')
                ->orderByDesc('day')
                ->orderByDesc('hour'),
        ]);

        return view('admin.treks.show', [
            'trek' => $trek,
        ]);
    }

    // Formulario de edición de excursión
    public function edit(Trek $adminTrek)
    {
        $adminTrek->load(['municipality.island', 'interestingPlaces']);

        $municipalities = Municipality::query()
            ->with('island')
            ->orderBy('name')
            ->get();

        $interestingPlaces = InterestingPlace::query()
            ->with('placeType')
            ->orderBy('name')
            ->get();

        $selectedPlaces = $adminTrek
            ->interestingPlaces
            ->pluck('pivot.order', 'id')
            ->toArray();

        return view('admin.treks.edit', [
            'trek' => $adminTrek,
            'municipalities' => $municipalities,
            'interestingPlaces' => $interestingPlaces,
            'selectedPlaces' => $selectedPlaces,
        ]);
    }

    // Actualiza una excursión existente
    public function update(Request $request, Trek $adminTrek)
    {
        $data = $request->validate([
            'regnumber' => [
                'required',
                'string',
                'max:255',
                Rule::unique('treks', 'regnumber')->ignore($adminTrek->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['nullable', Rule::in(['y', 'n'])],
            'municipality_id' => ['required', 'exists:municipalities,id'],
            'places' => ['array'],
            'places.*.selected' => ['nullable', 'in:1'],
            'places.*.order' => ['nullable', 'integer', 'min:0'],
        ]);

        $imageUrl = $adminTrek->imageUrl;
        if ($request->hasFile('image')) {
            $imageUrl = $this->trekImageService->storeUploadedImage($request->file('image'), $adminTrek->imageUrl);
        }

        $adminTrek->update([
            'regnumber' => mb_strtoupper($data['regnumber']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'imageUrl' => $imageUrl,
            'status' => $data['status'] ?? $adminTrek->status,
            'municipality_id' => (int) $data['municipality_id'],
        ]);

        $syncData = $this->buildPlaceSync($request);
        $adminTrek->interestingPlaces()->sync($syncData);

        return redirect()
            ->route('admin.treks.edit', $adminTrek)
            ->with('status', 'Excursión actualizada');
    }

    // Dar de baja desde el listado
    public function deactivate(Trek $adminTrek)
    {
        try {
            $adminTrek->update([
                'status' => 'n',
            ]);
        } catch (QueryException $e) {
            return redirect()
                ->route('admin.treks.index')
                ->with('error', 'No se pudo desactivar la excursión');
        }

        return redirect()
            ->route('admin.treks.index')
            ->with('status', 'Excursión desactivada');
    }

    // Dar de alta desde el listado
    public function activate(Trek $adminTrek)
    {
        try {
            $adminTrek->update([
                'status' => 'y',
            ]);
        } catch (QueryException $e) {
            return redirect()
                ->route('admin.treks.index')
                ->with('error', 'No se pudo activar la excursión');
        }

        return redirect()
            ->route('admin.treks.index')
            ->with('status', 'Excursión activada');
    }

    // Elimina una excursión
    // Construye el array de sync para lugares remarcables con orden
    private function buildPlaceSync(Request $request): array
    {
        $places = $request->input('places', []);
        $syncData = [];

        foreach ($places as $placeId => $payload) {
            if (! isset($payload['selected']) || $payload['selected'] !== '1') {
                continue;
            }

            $order = isset($payload['order']) ? (int) $payload['order'] : 0;
            $syncData[$placeId] = ['order' => $order];
        }

        return $syncData;
    }

}
