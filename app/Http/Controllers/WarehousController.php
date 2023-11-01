<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
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
                'w.remainder as remainder',
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
            "status" => true,
            "message" => "Save successfully",
        ]);
    }

    public function order(Request $request) {
        // return response()->json([
        //     "status" => Session::flush(),
        // ]);

        //params
        $materialIds = null;
        $groupWarehous = null;
        $materialQuantity = [];
        $details = [];

        // step 1
        $storeSession = Session::get('store', []);
        $modelProduct = Product::find($request->product_id);
        $materials = ProductMaterial::from('product_materials AS pm')
            ->select(
                'pm.material_id AS material_id',
                'pm.quantity AS quantity'
            )
            ->where('pm.product_id', '=', $request->product_id)
            ->get();
        foreach( $materials as $material ) {
            $materialQuantity[$material->material_id] = $material->quantity * $request->quantity;
        }
        $materialIds = $materials->pluck('material_id')->toArray();

        // step 2
        $details = [
            "product_name" => $modelProduct->name,
            "product_qty" => $request->quantity
        ];

        $getStore = $this->getWarehous($materialIds);
        $siklCount = 0;
        foreach( $getStore["data"] as $item ) {
            $siklCount++;
            if(!empty($storeSession[0][$item->warehaus_id])) {
                if($storeSession[0][$item->warehaus_id]['quantity'] != 0) {
                    $item->quantity = $storeSession[0][$item->warehaus_id]['quantity'];
                } else {
                    $details['product_materials'][] = [
                        "warehouse_id" => null,
                        "material_name" => $item->material_name,
                        "qty" => $materialQuantity[$item->material_id],
                        "price" => null,
                    ];
                    continue;
                }
            }

            // group detail
            if( $item->quantity != 0 ) {
                if( $materialQuantity[$item->material_id] != 0 ) {
                    $bronQuantity = 0;
                    $result = $item->quantity - $materialQuantity[$item['material_id']];

                    if( $getStore['count'][$item->material_id] == $siklCount && $result < 0) {
                        if(!empty($storeSession[0][$item->warehaus_id])) {
                            $storeSession[0][$item->warehaus_id]['quantity'] = $storeSession[0][$item->warehaus_id]['quantity'] - $item->quantity;
                        }
                        $details['product_materials'][] = [
                            "warehouse_id" => null,
                            "material_name" => $item->material_name,
                            "qty" => $result * -1,
                            "price" => null,
                        ];
                        continue;
                    }

                    if( $result <= 0 ) {
                        $bronQuantity = $item->quantity;
                        $materialQuantity[$item['material_id']] = $result * -1;
                    }

                    if( $result > 0 ) {
                        $bronQuantity = $materialQuantity[$item['material_id']];
                        $materialQuantity[$item['material_id']] = 0;
                    }

                    $details['product_materials'][] = [
                        "warehouse_id" => $item->warehaus_id,
                        "material_name" => $item->material_name,
                        "qty" => $bronQuantity,
                        "price" => $item->price,
                    ];

                    // session store quantity update
                    if(!empty($storeSession[0][$item->warehaus_id])) {
                        $storeSession[0][$item->warehaus_id]['quantity'] = $storeSession[0][$item->warehaus_id]['quantity'] - $bronQuantity;
                    } else {
                        $groupWarehous[$item->warehaus_id] = [
                            "warehaus_id" => $item->warehaus_id,
                            "quantity" => $item->quantity - $bronQuantity
                        ];
                    }
                }
            } else {
                $details['product_materials'][] = [
                    "warehouse_id" => null,
                    "material_name" => $item->material_name,
                    "qty" => $materialQuantity[$item->material_id],
                    "price" => null,
                ];
                continue;
            }

        }

        if( !empty($storeSession[0]) ) {
            Session::forget("store");
            $merge = array_merge((array) $storeSession[0], (array) $groupWarehous);
            Session::push('store', $merge);
        } else {
            Session::push('store', $groupWarehous);
        }

        Session::push('orders.result', $details);
        return response()->json([
            "store" => Session::get('store', []),
            "orders" => Session::get('orders', []),
        ]);

        return new OrderWarehousResource(Session::get('orders', []));
    }

    private function getWarehous($materialIds = []) {
        $items = Warehous::from('warehouses AS w')
            ->select(
                'w.material_id AS material_id',
                'w.id AS warehaus_id',
                'm.name AS material_name',
                'w.remainder AS quantity',
                'w.price AS price'
            )
            ->leftJoin('materials AS m', 'w.material_id', '=', 'm.id')
            ->whereIn('w.material_id', $materialIds)
            ->groupBy('w.material_id', 'w.price')
            ->get();

        $collection = collect($items);
        $duplicatesCount = $collection->groupBy('material_id')->map(function ($row) {
            return $row->count();
        });

        return [
            "data" => $items,
            "count" => $duplicatesCount
        ];
    }

}
