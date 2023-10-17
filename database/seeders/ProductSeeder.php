<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Koylak',
            'code' => 'XML_1',
        ]);
        
        Product::create([
            'name' => 'Shim',
            'code' => 'XML_2',
        ]);
    }
}
