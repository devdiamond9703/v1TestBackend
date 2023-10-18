<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\ProductRequest;


class ProductController extends Controller
{
    public function index() {
        $data = DB::table('products AS p')
                ->select('p.id', 'p.name', 'p.code')
                ->get();
        return response()->json($data);
    }

    public function store(ProductRequest $request) {
        $request->validated();
        Product::create([
            'name' => $request->name,
            'code' => 'XML_' . $request->code
        ]);
        return response()->json([
            "status" => 200,
            "message" => "Save successfully",
        ]);

    }
}
