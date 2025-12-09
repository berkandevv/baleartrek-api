<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Role;

class MeetingUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el ID del rol "visitant" de forma dinámica
        $visitanteRoleId = Role::where('name', 'visitant')->value('id');

        // Obtener todos los IDs de los usuarios cuyo rol es 'visitant'
        $visitantesIds = User::where('role_id', $visitanteRoleId)->pluck('id');

        // Obtener todos los meetings
        $meetings = Meeting::all();

        // Para cada meeting, asignar 20 visitantes aleatorios
        foreach ($meetings as $meeting) {

            // Evitar pedir más usuarios de los que existen, si no hay 20, se cogerán los que haya
            $cantidad = min(20, $visitantesIds->count());

            // Seleccionar "X" visitantes aleatorios (máximo 20)
            $visitantesAleatorios = $visitantesIds->random($cantidad);

            $meeting->users()->attach($visitantesAleatorios);
        }
    }
}
