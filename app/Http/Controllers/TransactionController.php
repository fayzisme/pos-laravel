<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\OrderDetail;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TransactionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function api(Request $request)
    {
        $transactions = Order::orderBy('id', 'desc')->with(['user','orderDetails'])->filter(compact('request'))->get();
        $datatables = datatables()->of($transactions)->addIndexColumn()->addColumn('no', function(Order $order) {
            return $order->DT_RowIndex;
        })->addColumn('name', function(Order $order) {
            return $order->user->name;
        })
        ->editColumn('nama_customer', function(Order $order) {
            return $order->nama_customer??$order->user->name;
        })
        ->editColumn('created_at', function(Order $order) {
            return convert_date($order->created_at);
        })
        ->make(true);
        return $datatables;
    }

    public function index(Request $request)
    {
        return Inertia::render('Transaction/Transaction', [
            'title'=>"Transaksi"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::where('id', $id)->with('orderDetails.produk')->with('payment')->with('user')->first();
        $order->tanggal_transaksi = convert_date($order->created_at);
        return Inertia::render('Transaction/Show', [
            'title'=>"Detail Transaksi",
            'transaction'=> $order,
            'show' => true
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {   
        $order = Order::where('id', $id)->with('orderDetails.produk')->with('payment')->with('user')->first();
        $order->tanggal_transaksi = convert_date($order->created_at);
        $base_url = url('');
        return Inertia::render('Order/Order', [
            'title'=>"Edit Order",
            'order'=> $order,
            'is_edit' => true,
            'base_url' => $base_url
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $resp_code = 500;
        $resp_data = null;
        $message = null;

        DB::beginTransaction();
        try {
            // hapus detail
            OrderDetail::where('order_id', $id)->delete();

            // hapus payment
            Payment::where('order_id', $id)->delete();

            // delete order
            Order::where('id', $id)->delete();

            DB::commit();

            $resp_code = 200;
            $resp_data = $id;
            $message = 'success';


        } catch (Exception $e) {
            DB::rollBack();
            $resp_code = 400;
            $message = $e->getMessage();
            // dd($e->getMessage());
        }

        // return json_encode($response);
        return to_route('transaksis.index')->with([
            'message' => $message,
            'class' => $resp_code == 200 ? 'alert alert-success' : 'alert alert-warning'
        ]);
    }
}
