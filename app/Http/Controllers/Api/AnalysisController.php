<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        // 日別売上
        // dd($request);
        $subQuery = Order::betweenDate($request->startDate, $request->endDate);
        if($request->type === 'parDay'){
            $subQuery->where('status', true)
            ->groupBy('id')
            ->selectRaw('id, sum(subtotal) as totalPerPurchase, DATE_FORMAT(created_at, "%Y年%m月%d日") as date');

            $data = DB::table($subQuery)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->get();

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');
        }

        return response()->json([
            'data' => $data,
            'type' => $request->type,
            'labels' => $labels,
            'totals' => $totals
        ], Response::HTTP_OK);
    }
}
