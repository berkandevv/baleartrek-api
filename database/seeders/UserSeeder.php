<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario admin creado de manera "eloquent"
        User::create([
            'name'      => 'admin',
            'lastname'  => 'admin',
            'dni'       => '00000000A',
            'email'     => 'admin@baleartrek.com',
            'phone'     => '000000000',
            'password'  => Hash::make('12345678'),
            'role_id'   => Role::where('name', 'admin')->value('id'),
        ]);

        /* Usuari d'inici
        $user = new User();
        $user->name = "admin";
        $user->lastname = "admin";
        $user->email = "admin@baleartrek.com";
        $user->password = Hash::make('12345678');
        $user->role_id = Role::where('name', 'admin')->value('id');
        $user->save(); */

        // Cargar el JSON usando File
        $jsonData = File::get(database_path('seeders/data/users.json'));
        $data = json_decode($jsonData, true);

        // El select se hace solo una vez
        $roleId = Role::where('name', 'guia')->value('id');

        foreach ($data['usuaris']['usuari'] as $user) {

            User::create([
                // Normalizamos DNI, nombres y apellidos a MAYÚSCULAS usando mb_strtoupper
                'dni'       => mb_strtoupper($user['dni']),
                'name'      => mb_strtoupper($user['nom']),
                'lastname'  => mb_strtoupper($user['llinatges']),
                // Email como clave única. Se pasa a minúsculas con mb_strtolower
                'email'     => mb_strtolower($user['email']),
                'phone'     => $user['telefon'],
                'password'    => Hash::make($user['password']),
                // Asignamos el rol guía
                'role_id'   => $roleId,
            ]);
        }
    }
}
