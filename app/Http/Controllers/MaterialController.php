<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Http\Requests\MaterialRequest;
class MaterialController extends Controller
{
    public function index() {
        $data = DB::table('materials AS m')
                ->select('m.id', 'm.name')
                ->get();
        return response()->json($data);
    }

    public function store(MaterialRequest $request) {
        $request->validated();
        Material::create([
            'name' => $request->name,
        ]);
        return response()->json([
            "status" => 200,
            "message" => "Save successfully",
        ]);
    }
}
