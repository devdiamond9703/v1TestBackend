<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductMaterial;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\IndexProductResource;
use App\Http\Resources\Product\MaterialsByProductsResource;

class ProductController extends Controller
{
    public function index() {
        $data = Product::from('products AS p')
                ->select('p.id', 'p.name', 'p.code')
                ->get();
        return IndexProductResource::collection($data);
    }

    public function store(ProductRequest $request) {
        $request->validated();
        Product::create([
            'name' => $request->name,
            'code' => 'XML_' . $request->code
        ]);
        return response()->json([
            "status" => true,
            "message" => "Save successfully",
        ]);

    }

    public function getMaterialsByProduct(Request $request) {
        $data = ProductMaterial::from('product_materials AS pm')
                ->select(
                    'pm.id AS product_material_id',
                    'm.name AS material_name',
                    'pm.quantity As quantity'
                )
                ->leftJoin('materials AS m', 'pm.material_id', '=', 'm.id')
                ->where('pm.product_id', $request->id)
                ->get();
        return MaterialsByProductsResource::collection($data);
    }

}
