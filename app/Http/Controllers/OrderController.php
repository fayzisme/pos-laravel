<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Produk;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $code = 'NumberOrder/tanggal/id_user';
        $date = convert_date(Carbon::now());
        return Inertia::render('Order/Order', [
            'title'=>"Order",
            'code'=> $code,
            'date' => $date
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {   
        $produks = $request->input('produks');
        $amount = intval($request->input('amount'));
        $code = $request->input('code');
        $nama = $request->input('nama');
        $no_meja = $request->input('meja');
        $resp_code = 500;
        $resp_data = null;
        $message = null;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'code' => $code,
                'nama_customer' => $nama,
                'nomor_meja' => $no_meja
            ]);

            $data_details = [];
            $produk_ids = [];
            foreach ($produks as $key => $value) {
                $input = [
                    'price' => $value['price'],
                    'quantity' => $value['jumlah'],
                    'produk_id' => $value['id'],
                    'order_id' => $order->id
                ];
                $produk_ids[] = $value['id'];
                array_push($data_details, $input);
                $qty = $value['jumlah'];

                Produk::where('id', $value['id'])
                ->update([
                    'qty' => DB::raw("qty - {$qty}")
                ]);
            }

            OrderDetail::insert($data_details);

            Payment::create([
                'amount' => $amount,
                'order_id' => $order->id
            ]);

            DB::commit();

            $resp_code = 200;
            $resp_data = $order;
            $message = 'success';


        } catch (Exception $e) {
            DB::rollBack();
            $resp_code = 400;
            $message = $e->getMessage();
            // dd($e->getMessage());
        }

        // $response = [
        //     'resp_code' => $resp_code,
        //     'resp_data' => $resp_data,
        //     'resp_message' => $message,
        // ];

        // return json_encode($response);
        return to_route('orders.index')->with([
            'message' => $message,
            'class' => $resp_code == 200 ? 'alert alert-success' : 'alert alert-warning'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $produks = $request->input('produks');
        $amount = intval($request->input('amount'));
        $code = $request->input('code');
        $nama = $request->input('nama');
        $no_meja = $request->input('meja');
        $resp_code = 500;
        $resp_data = null;
        $message = null;
        DB::beginTransaction();
        try {
            $order->user_id = $request->user()->id;
            $order->code = $code;
            $order->nama_customer = $nama;
            $order->nomor_meja = $no_meja;

            $order->save();

            $produk_details = OrderDetail::where('order_id', $order->id)->get();
            foreach ($produk_details as $k => $val) {
                $qty = $val->quantity;
                Produk::where('id', $val->produk_id)
                ->update([
                    'qty' => DB::raw("qty + {$qty}")
                ]);
            }
            OrderDetail::where('order_id', $order->id)->delete();

            $data_details = [];
            $produk_ids = [];
            foreach ($produks as $key => $value) {
                $input = [
                    'price' => $value['price'],
                    'quantity' => $value['jumlah'],
                    'produk_id' => $value['id'],
                    'order_id' => $order->id
                ];
                $produk_ids[] = $value['id'];
                array_push($data_details, $input);
                $qty = $value['jumlah'];

                Produk::where('id', $value['id'])
                ->update([
                    'qty' => DB::raw("qty - {$qty}")
                ]);
            }

            OrderDetail::insert($data_details);

            Payment::where('order_id', $order->id)->delete();
            Payment::create([
                'amount' => $amount,
                'order_id' => $order->id
            ]);


            DB::commit();

            $resp_code = 200;
            $resp_data = $order;
            $message = 'success';


        } catch (Exception $e) {
            DB::rollBack();
            $resp_code = 400;
            $message = $e->getMessage();
            // dd($e->getMessage());
        }

        // $response = [
        //     'resp_code' => $resp_code,
        //     'resp_data' => $resp_data,
        //     'resp_message' => $message,
        // ];

        // return json_encode($response);
        return to_route('transaksis.index')->with([
            'message' => $message,
            'class' => $resp_code == 200 ? 'alert alert-success' : 'alert alert-warning'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
