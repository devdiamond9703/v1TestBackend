<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductMaterial;

class ProductMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // koylay
        ProductMaterial::create([
            'product_id' => 1,
            'material_id' => 1,
            'quantity' => 1,
        ]);

        ProductMaterial::create([
            'product_id' => 1,
            'material_id' => 2,
            'quantity' => 10,
        ]);

        ProductMaterial::create([
            'product_id' => 1,
            'material_id' => 3,
            'quantity' => 5,
        ]);

        // shim
        ProductMaterial::create([
            'product_id' => 2,
            'material_id' => 1,
            'quantity' => 2,
        ]);

        ProductMaterial::create([
            'product_id' => 2,
            'material_id' => 2,
            'quantity' => 15,
        ]);

        ProductMaterial::create([
            'product_id' => 2,
            'material_id' => 4,
            'quantity' => 1,
        ]);
    }
}
