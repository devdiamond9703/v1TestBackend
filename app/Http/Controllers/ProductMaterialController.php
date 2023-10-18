<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ProductMaterial;
use App\Http\Requests\ProductMaterialRequest;

class ProductMaterialController extends Controller
{
    public function index() {
        $data = DB::table('product_materials AS pm')
                ->select('pm.id', 'pm.product_id', 'pm.material_id', 'pm.quantity')
                ->get();
        return response()->json($data);
    }

    public function store(ProductMaterialRequest $request) {
        $request->validated();
        ProductMaterial::create([
            'product_id' => $request->product_id,
            'material_id' => $request->material_id,
            'quantity' => $request->quantity
        ]);
        return response()->json([
            "status" => 200,
            "message" => "Save successfully",
        ]);
    }
}
