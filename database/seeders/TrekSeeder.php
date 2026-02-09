<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Trek;
use App\Models\Meeting;
use App\Models\Comment;
use App\Models\Municipality;
use App\Models\User;

class TrekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Cargar el JSON
        $jsonData = File::get(env('JSON_PATH') . 'treks.json');
        $data = json_decode($jsonData, true);

        // Obtener municipios por nombre
        $municipiosPorNombre = Municipality::pluck('id', 'name');
        
        // Obtener usuarios por DNI (para guías y autores de comentarios)
        $usuariosPorDni = User::pluck('id', 'dni');

        foreach ($data as $trekData) {

            $municipioNombre = $trekData['municipality'];
            $municipioId     = $municipiosPorNombre->get($municipioNombre);

            $trek = Trek::firstOrCreate(
                ['regnumber' => $trekData['regNumber']],
                [
                    'name'            => $trekData['name'],
                    'description'     => $trekData['description'] ?? null,
                    'imageUrl'        => $trekData['imageUrl'] ?? null,
                    'municipality_id' => $municipioId,
                ]
            );

            // 2) MEETINGS + COMMENTS
            foreach ($trekData['meetings'] as $meetingData) {

                // Guía de la reunión, buscado por DNI
                $dniGuia = strtoupper($meetingData['DNI']);
                $guiaId  = $usuariosPorDni->get($dniGuia);

                // Crear la reunión
                $meeting = Meeting::create([
                    'trek_id'    => $trek->id,
                    'user_id'    => $guiaId,
                    'day'        => $meetingData['day'],
                    'hour'       => $meetingData['time'],
                    'appDateIni' => $meetingData['day'],
                    'appDateEnd' => $meetingData['day'],
                ]);

                // 3) COMMENTS de esa reunión
                foreach ($meetingData['comments'] as $commentData) {

                    $dniAutor = strtoupper($commentData['DNI']);
                    $autorId  = $usuariosPorDni->get($dniAutor);

                    Comment::create([
                        'meeting_id' => $meeting->id,
                        'user_id'    => $autorId,
                        'comment'    => $commentData['comment'],
                        'score'      => (int) $commentData['score'], // Nos aseguramos de que sea un entero
                    ]);
                }
            }
        }
    }
}
