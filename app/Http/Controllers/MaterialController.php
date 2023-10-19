<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Http\Requests\MaterialRequest;
use App\Http\Resources\Material\IndexMaterialResource;


class MaterialController extends Controller
{
    public function index() {
        $data = Material::from('materials as m')
            ->select('m.id', 'm.name')
            ->get();
        return IndexMaterialResource::collection($data);
    }

    public function store(MaterialRequest $request) {
        $request->validated();
        Material::create([
            'name' => $request->name,
        ]);
        return response()->json([
            "status" => true,
            "message" => "Save successfully",
        ]);
    }
}
