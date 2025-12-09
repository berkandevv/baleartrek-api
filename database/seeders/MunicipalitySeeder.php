<?php

namespace Database\Seeders;

use App\Models\Island;
use App\Models\Municipality;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cargar JSON
        $jsonData = File::get(env('JSON_PATH') . 'municipalities.json');
        $data = json_decode($jsonData, true);

        // Obtener todas las islas y zonas
        $islands = Island::pluck('id', 'name');
        $zones   = Zone::pluck('id', 'name');

        foreach ($data['municipis']['municipi'] as $municipality) {

            // Conseguir IDs por nombre
            $islandId = $islands[$municipality['Illa']];
            $zoneId   = $zones[$municipality['Zona']];

            // Crear municipio si no existe
            Municipality::firstOrCreate(
                ['name' => $municipality['Nom']],
                [
                    'island_id' => $islandId,
                    'zone_id'   => $zoneId,
                ]
            );
        }
    }
}
