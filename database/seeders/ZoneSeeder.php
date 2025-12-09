<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zone;
use Illuminate\Support\Facades\File;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = File::get(env('JSON_PATH') . 'zones.json');
        $data = json_decode($jsonData, true);

        foreach ($data['zones']['zona'] as $zone) {
            Zone::firstOrCreate(['name' => $zone['Nom']]);
        }
    }
}
