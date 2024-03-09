<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
    {
        $startDate = '2022-08-01';
        $endDate = '2023-08-01';

        $subQuery = Order::betweenDate($startDate,$endDate)
        ->where('status', true)
        ->groupBy('id')
        ->selectRaw('id, sum(subtotal) as totalPerPurchase, DATE_FORMAT(created_at, "%Y%m%d") as date');

        $data = DB::table($subQuery)
        ->groupBy('date')
        ->selectRaw('date, sum(totalPerPurchase) as total')
        ->get();

        // dd($data);


        return Inertia::render('Analysis');
    }
}
