<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Island;
use Illuminate\Support\Facades\File;

class IslandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = File::get(database_path('seeders/data/islands.json'));
        $data = json_decode($jsonData, true);

        foreach ($data['illes']['illa'] as $island) {
            Island::create(['name' => $island['Nom']]);
        }
    }
}
