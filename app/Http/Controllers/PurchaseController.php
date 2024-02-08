<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\Purchase;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Order::paginate(10));
        $orders = Order::groupBy('id')
            ->selectRaw('id, customer_name, sum(subtotal) AS total, status, created_at')
            ->paginate(10);
        // dd($orders);

        return Inertia::render('Purchases/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * 商品購入画面作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $customers = Customer::select('id','name','kana')->get();
        $items = Item::select('id', 'name', 'price')
            ->where('is_selling', true)->get();
        // $items = Item::isSelling(true)->select('id','name','price')->get();

        return Inertia::render('Purchases/Create', [
            // 'customers' => $customers,
            'items' => $items,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {
        DB::beginTransaction();
        // dd($request);

        try {
            // Purchaseテーブルへ登録
            $purchase = Purchase::create([
                'customer_id' => $request->customer_id,
                'status' => $request->status
            ]);

            // 中間テーブルitem_purchaseテーブルへ登録
            foreach ($request->items as $item) {
                $purchase->items()->attach($purchase->id, [
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);
            }
            DB::commit();
            return to_route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        // 合計
        $order = Order::groupBy('id')
            ->where('id', $purchase->id)
            ->selectRaw('id, customer_name, sum(subtotal) AS total, status, created_at')
            ->first();

        // 小計
        $items = Order::where('id', $purchase->id)
            ->get();

        // dd($order, $items);

        return Inertia::render('Purchases/Show', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $allItems = Item::select('id', 'name', 'price')
            ->get();

        $items = [];
        foreach ($allItems as $allItem) {
            $quantity = 0;
            foreach ($purchase->items as $item) {
                if ($allItem->id === $item->id) {
                    $quantity = $item->pivot->quantity;
                }
            }
            array_push($items, [
                'id' => $allItem->id,
                'name' => $allItem->name,
                'price' => $allItem->price,
                'quantity' => $quantity,
            ]);
        }
        $order = Order::groupBy('id')
            ->where('id', $purchase->id)
            ->selectRaw('id, customer_id, customer_name, status, created_at')
            ->first();

        return Inertia::render('Purchases/Edit', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();
        try {


            // Purchaseテーブルの更新
            $purchase->status = $request->status; // ステータス
            $purchase->save();

            // 中間テーブルitem_purchasesの更新
            $items = [];

            foreach ($request->items as $item) {
                $items = $items + [
                    $item['id'] => [
                        'quantity' => $item['quantity']
                    ]
                ];
            }
            // dd($items);
            $purchase->items()->sync($items);
            DB::commit();
            return to_route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
