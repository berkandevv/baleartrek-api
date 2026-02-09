<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrekStoreRequest;
use App\Http\Requests\TrekUpdateRequest;
use App\Http\Resources\TrekResource;
use App\Models\Municipality;
use App\Models\Island;
use App\Models\Trek;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrekController extends Controller
{
    /**
     * Listado de "treks" (publico).
     * Permite filtrar por isla (illa o island_id).
     */
    public function index(Request $request)
    {
        // Filtro opcional por isla
        $illa = $request->query('illa');
        $filter = $illa ?? $request->query('island_id');

        // Relaciones necesarias para el listado
        $relations = [
            'municipality.island',
            'meetings'
        ];

        // Construimos la consulta con el filtro opcional
        $treks = Trek::query()
            ->when($filter, function ($query) use ($filter) {
                $query->whereHas('municipality.island', function ($subQuery) use ($filter) {
                    if (is_numeric($filter)) {
                        $subQuery->where('id', $filter);
                    } else {
                        $subQuery->where('name', '=', $filter);
                    }
                });
            })
            ->with($relations)
            ->orderBy('regnumber')
            ->get();

        if ($treks->isEmpty()) {
            return response()->json([
                'message' => 'No se han encontrado treks para la isla indicada.',
            ]);
        }

        // Devolvemos coleccion de recursos
        return TrekResource::collection($treks);
    }

    /**
     * Mostrar un "trek" concreto con toda su informacion relacionada.
     */
    public function show(Trek $trek): TrekResource
    {
        // Relaciones necesarias para el detalle
        $relations = [
            'municipality.island',
            'municipality.zone',
            'interestingPlaces.placeType',
            'meetings.user',
            'meetings.users',
            'meetings.comments.user',
        ];

        // Cargamos relaciones en el "trek"
        $trek->load($relations);

        return new TrekResource($trek);
    }

    /**
     * Crear un nuevo "trek" (admin).
     * Puede incluir meetings y comentarios asociados.
     */
    public function store(TrekStoreRequest $request): TrekResource
    {
        // Usamos transaccion para mantener consistencia
        $trek = DB::transaction(function () use ($request) {
            // Datos ya validados por el Request
            $data = $request->validated();

            // Resolucion del municipio por id o por nombre
            if (array_key_exists('municipality_id', $data)) {
                $municipalityId = (int) $data['municipality_id'];
            } else {
                abort_if(! array_key_exists('municipality', $data), 422, 'Debes indicar un municipio válido.');

                $municipality = Municipality::query()
                    ->where('name', '=', $data['municipality'])
                    ->first();

                abort_if(! $municipality, 422, 'Municipio no encontrado!');

                $municipalityId = $municipality->id;
            }

            // Creamos el "trek" principal
            $trek = Trek::create([
                'regnumber' => mb_strtoupper($data['regNumber']),
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'imageUrl' => $data['imageUrl'] ?? null,
                'status' => $data['status'] ?? 'n',
                'municipality_id' => $municipalityId,
            ]);

            // Creamos meetings (y sus comentarios) si vienen en la petición
            if (! empty($data['meetings'])) {
                foreach ($data['meetings'] as $meetingData) {
                    $guide = User::query()
                        ->where('dni', '=', mb_strtoupper($meetingData['DNI']))
                        ->first();

                    abort_if(! $guide, 422, "No existe usuario con DNI {$meetingData['DNI']}");

                    $meeting = $trek->meetings()->create([
                        'user_id' => $guide->id,
                        'day' => $meetingData['day'],
                        'hour' => $meetingData['time'],
                        'appDateIni' => $meetingData['day'],
                        'appDateEnd' => $meetingData['day'],
                    ]);

                    // Comentarios opcionales asociados a la meeting
                    if (empty($meetingData['comments'])) {
                        continue;
                    }

                    foreach ($meetingData['comments'] as $commentData) {
                        $author = User::query()
                            ->where('dni', '=', mb_strtoupper($commentData['DNI']))
                            ->first();

                        abort_if(! $author, 422, "No existe usuario con DNI {$commentData['DNI']}");

                        $meeting->comments()->create([
                            'user_id' => $author->id,
                            'comment' => $commentData['comment'],
                            'score' => (int) $commentData['score'],
                        ]);
                    }
                }
            }

            // Relaciones necesarias para devolver el "trek" completo
            $relations = [
                'municipality.island',
                'municipality.zone',
                'interestingPlaces.placeType',
                'meetings.user',
                'meetings.users',
                'meetings.comments.user',
                'meetings.comments.images',
            ];

            return $trek->load($relations);
        });

        // Devolvemos el "trek" creada como recurso
        return new TrekResource($trek);
    }
}
