<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use App\Models\Comment;
use App\Models\Image;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command?->info(" INICIANDO CARGA DE DATOS (SEEDERS + FACTORIES)\n");

        $this->command?->info("1) Ejecutando seeders base:\n - Roles\n - Islas\n - Zonas\n - Municipios\n - Users.json (admin + guías)\n - Treks.json (treks, meetings, comments)\n - Places.json (types, places, pivote)\n");
        // 1) Seeders "fijos" + JSON
        $this->call([
            RoleSeeder::class,          // roles: admin, guia, visitant
            IslandSeeder::class,        // islands.json
            ZoneSeeder::class,          // zones.json
            MunicipalitySeeder::class,  // municipalities.json
            UserSeeder::class,          // admin + guías desde users.json
            TrekSeeder::class,          // treks.json → treks + meetings + comments
            PlaceSeeder::class,         // places.json → place_types + interesting_places + pivote
        ]);

        $this->command?->info("2) Creando 100 usuarios VISITANT con factory...\n");
        // 2) FACTORY: crear 100 usuarios de tipo "visitant"
        $visitantRoleId = Role::where('name', 'visitant')->value('id');

        User::factory(100)->create([
            'role_id' => $visitantRoleId,
            'password' => Hash::make('Password123!'),
        ]);

        $this->command?->info("3) Rellenando tabla MEETING_USER (20 visitants por meeting)...\n");
        // 3) Asignar 20 visitants a cada meeting (tabla pivote meeting_user)
        $this->call(MeetingUserSeeder::class);

        $this->command?->info("4) Creando 1000 imágenes y asignando comentarios aleatorios...\n");
        // 4) FACTORY: crear 1000 imágenes y asignarlas a comentarios aleatorios
        Image::factory(1000)->create();
        $this->command?->info(" CARGA DE DATOS COMPLETADA CORRECTAMENTE!\n");
    }
}
