<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InterestingPlace;
use App\Models\Municipality;
use App\Models\Trek;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TrekController extends Controller
{
    // Listado de excursiones con búsqueda básica
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));

        $treks = Trek::query()
            ->with(['municipality.island', 'interestingPlaces'])
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
            'municipalities' => $municipalities,
            'interestingPlaces' => $interestingPlaces,
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
            $imageUrl = $this->storeTrekImage($request->file('image'));
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
            ->with('status', 'Excursión creada.');
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
            $imageUrl = $this->storeTrekImage($request->file('image'), $adminTrek->imageUrl);
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
            ->with('status', 'Excursión actualizada.');
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

    // Guarda la imagen físicamente en public/images/treks y devuelve la URL pública
    private function storeTrekImage($file, ?string $previousUrl = null): string
    {
        $directory = public_path('images/treks');
        File::ensureDirectoryExists($directory);

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::uuid()->toString() . '.' . $extension;

        if ($previousUrl && str_starts_with($previousUrl, '/images/treks/')) {
            $previousPath = public_path(ltrim($previousUrl, '/'));
            if (File::exists($previousPath)) {
                File::delete($previousPath);
            }
        }

        $file->move($directory, $filename);

        return '/images/treks/' . $filename;
    }
}
