<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Warehous;
use App\Http\Requests\WarehousRequest;

class WarehousController extends Controller
{
    public function index() {
        $data = DB::table('warehouses AS w')
                ->select('w.id', 'w.material_id', 'w.remainder', 'w.price')
                ->get();
        return response()->json($data);
    }

    public function store(WarehousRequest $request) {
        $request->validated();
        Warehous::create([
            'material_id' => $request->material_id,
            'remainder' => $request->remainder,
            'price' => $request->price
        ]);
        return response()->json([
            "status" => 200,
            "message" => "Save successfully",
        ]);

    }
}
