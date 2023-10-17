<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Material::create([
            'name' => 'mato',
        ]);

        Material::create([
            'name' => 'ip',
        ]);

        Material::create([
            'name' => 'tugma',
        ]);
        
        Material::create([
            'name' => 'zamok',
        ]);
    }
}
