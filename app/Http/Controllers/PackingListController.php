<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class PackingListController extends Controller
{
    public function generatePackingList($orderId)
    {
        $order = Order::with(['customer', 'cartons.product'])->findOrFail($orderId);

        // Menghitung total cartons berdasarkan quantity dan items_per_carton
        $totalCartons = $order->cartons->sum(function ($carton) {
            return ceil($carton->quantity / $carton->items_per_carton);
        });

        $packingListData = [
            'order' => $order,
            'customer' => $order->customer,
            'cartons' => $order->cartons,
            'totalCartons' => $totalCartons,
            'totalQty' => $order->cartons->sum('quantity'),
            'totalWeight' => $order->cartons->sum(function ($carton) {
                return $carton->quantity * $carton->product->weight_per_unit;
            }),
            'totalVolume' => $order->cartons->sum(function ($carton) {
                return $carton->quantity * $carton->product->volume_per_unit;
            }),
        ];
        $pdf = Pdf::loadView('packing_list', $packingListData);

        return $pdf->stream('packing_list.pdf');
    }
}
