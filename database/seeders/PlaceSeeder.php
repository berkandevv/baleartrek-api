<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Trek;
use App\Models\PlaceType;
use App\Models\InterestingPlace;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cargar JSON
        $jsonData = File::get(env('JSON_PATH') . 'places.json');
        $data = json_decode($jsonData, true);

        // Obtener treks por regNumber
        $treksPorNumero = Trek::pluck('id', 'regnumber');

        // Obtener place_types por nombre
        $placeTypesPorNombre = PlaceType::pluck('id', 'name');

        foreach ($data as $trekData) {

            // Trek ID según regNumber
            $regNumber = $trekData['regNumber'];

            // ID del trek en BD
            $trekId = $treksPorNumero->get($regNumber);

            $trek = Trek::find($trekId);

            // Orden inicial para el pivote
            $order = 1;

            foreach ($trekData['places_of_interest'] as $placeData) {

                // 1) PLACE TYPE
                $typeName = $placeData['type'];

                // Si el tipo no existe en la colección, lo creamos y lo añadimos
                if (!$placeTypesPorNombre->has($typeName)) {
                    $newType = PlaceType::create([
                        'name' => $typeName,
                    ]);

                    // Añadirlo a la colección para siguientes iteraciones
                    $placeTypesPorNombre->put($typeName, $newType->id);
                }

                $placeTypeId = $placeTypesPorNombre->get($typeName);

                // 2) INTERESTING PLACE
                $place = InterestingPlace::firstOrCreate(
                    [
                        'gps'           => $placeData['gpsPos'],
                        'name'          => $placeData['name'],
                        'place_type_id' => $placeTypeId
                    ]
                );

                // 3) PIVOTE trek <-> place con campo order
                $trek->interestingPlaces()->attach([$place->id => ['order' => $order]]);

                $order++;
            }
        }
    }
}
