<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Warehous;
use App\Models\ProductMaterial;
use App\Http\Requests\WarehousRequest;
use App\Http\Resources\Warehous\IndexWarehousResource;
use App\Http\Resources\Warehous\OrderWarehousResource;


class WarehousController extends Controller
{
    public function index() {
        $data = Warehous::from('warehouses AS w')
            ->select(
                'w.id AS warehous_id',
                'm.name AS material_name',
                DB::raw('sum(w.remainder) as remainder'),
                'w.price'
            )
            ->leftJoin('materials AS m', 'w.material_id', '=', 'm.id')
            ->groupBy('w.material_id', 'w.price')
            ->get();
            return IndexWarehousResource::collection($data);
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

    public function order(Request $request) {

        $modelProduct = Product::find($request->product_id);
        $materials = ProductMaterial::from('product_materials AS pm')
            ->select(
                'pm.material_id AS material_id',
                'pm.quantity AS quantity'
            )
            ->where('pm.product_id', '=', $request->product_id)
            ->get();

        //params
        $arr = [];
        $details = [];
        $ids = null;

        foreach($materials as $material) {
            $arr[$material->material_id] = $material->quantity * $request->quantity;
        }

        $ids = $materials->pluck('material_id')->toArray();

        $items = Warehous::from('warehouses AS w')
            ->select(
                'w.material_id AS material_id',
                'w.id AS warehaus_id',
                'm.name AS material_name',
                'w.remainder AS qty',
                'w.price AS price',
            )
            ->leftJoin('materials AS m', 'w.material_id', '=', 'm.id')
            ->whereIn('w.material_id', $ids)
            ->groupBy('w.material_id', 'w.price')
            ->get();

        $details['result'] = [
            "product_name" => $modelProduct->name,
            "product_qty" => $request->quantity
        ];

        foreach($items as $item) {

            if($arr[$item['material_id']] != 0 && $item->qty != 0) {

                $result = $item->qty - $arr[$item['material_id']];

                if( $result < 0 ) {
                    $details['result']['product_materials'][] = [
                        "warehouse_id" => $item->warehaus_id,
                        "material_name" => $item->material_name,
                        "qty" => $item->qty,
                        "price" => $item->price,
                    ];
                    Warehous::create([
                        'material_id' => $item['material_id'],
                        'remainder' => $item->qty * -1,
                        'price' => $item->price
                    ]);
                    $arr[$item['material_id']] = $result * -1;

                }

                if( $result > 0 ) {
                    $details['result']['product_materials'][] = [
                        "warehouse_id" => $item->warehaus_id,
                        "material_name" => $item->material_name,
                        "qty" => $arr[$item['material_id']],
                        "price" => $item->price,
                    ];
                    Warehous::create([
                        'material_id' => $item['material_id'],
                        'remainder' => $arr[$item['material_id']] * -1,
                        'price' => $item->price
                    ]);
                    $arr[$item['material_id']] = 0;
                }

            }
        }

        return new OrderWarehousResource($details);
    }

}
