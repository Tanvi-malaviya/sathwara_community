<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $defaultAreas = [
            ['name' => 'Ahmedabad East', 'pincode' => '380001'],
            ['name' => 'Ahmedabad West', 'pincode' => '380009'],
            ['name' => 'Maninagar', 'pincode' => '380008'],
            ['name' => 'Nikol', 'pincode' => '382350'],
            ['name' => 'Bapunagar', 'pincode' => '380024'],
            ['name' => 'Vastral', 'pincode' => '382418'],
            ['name' => 'Odhav', 'pincode' => '382415'],
            ['name' => 'Ghatlodia', 'pincode' => '380061'],
            ['name' => 'Satellite', 'pincode' => '380015'],
            ['name' => 'Sabarmati', 'pincode' => '380005'],
        ];

        foreach ($defaultAreas as $area) {
            Area::firstOrCreate(['name' => $area['name']], ['pincode' => $area['pincode']]);
        }
    }
}
