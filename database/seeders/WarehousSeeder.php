<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehous;

class WarehousSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Warehous::create([
            'material_id' => 1,
            'remainder' => 100,
            'price' => 1000,
        ]);

        Warehous::create([
            'material_id' => 2,
            'remainder' => 100,
            'price' => 200,
        ]);

        Warehous::create([
            'material_id' => 3,
            'remainder' => 100,
            'price' => 500,
        ]);

        Warehous::create([
            'material_id' => 4,
            'remainder' => 100,
            'price' => 1000,
        ]);
    }
}
