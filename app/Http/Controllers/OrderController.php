<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderProduct;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('products')->get();
        return response([
            'status'    => true,
            'message'   => 'Successfully get all orders!',
            'data'      => $orders    
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carts = $request->carts;

        if (count($carts) < 1) {
            return response([
                'status'    => false,
                'message'   => 'Data pesanan tidak boleh kosong!',
                'data'      => $carts    
            ]);
        }


        $code = "OR".Carbon::now()->timestamp;
        $order = new Order;
        $order->code = $code;
        $order->total_price = 0;
        $order->quantity = 0;
        $order->save();

        $quantity = 0;
        $total_price = 0;
        foreach ($carts as $value) {
            $product = Product::find($value['product_id']);
            if($product) {
                if ($product->discount_price !== null) {
                    $subtotal = ($product->original_price - $product->discount_price) * $value['qty'] ;
                } else {
                    $subtotal = $value['qty'] * $product->original_price;
                }
                $total_price += $subtotal;
                $quantity += $value['qty'];
                OrderProduct::create([
                    'order_id'      => $order->id,
                    'product_id'    => $value['product_id'],
                    'qty'           => $value['qty'], 
                    'subtotal'      => $subtotal,
                ]);
            }
        }

        $update_order = Order::find($order->id);
        if(!$update_order) {
            return response([
                'status'    => false,
                'message'   => 'Data update order tidak dapat ditemukan!',
                'data'      => $update_order    
            ]);
        }
        $update_order->total_price = $total_price;
        $update_order->quantity = $quantity;
        $update_order->save();

        return response([
            'status'    => false,
            'message'   => 'Data order berhasil dibuat!',
            'data'      => $update_order    
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
