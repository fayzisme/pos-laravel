<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProdukController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function api(Request $request)
    {
        $products = Produk::orderBy('id', 'desc')->filter(compact('request'))->get();
        $datatables = datatables()->of($products)->addIndexColumn()->addColumn('no', function(Produk $produk) {
            return $produk->DT_RowIndex;
        })->editColumn('created_at', function(Produk $produk) {
            return convert_date($produk->created_at);
        })->editColumn('price', function(Produk $produk) {
            return rupiah(intval($produk->price));
        })->make(true);
        return $datatables;
    }

    public function api_active(Request $request)
    {
        $products = Produk::where('status', 1)->where('qty', '>', 0)->orderBy('id', 'desc')->filter(compact('request'))->get();
        $datatables = datatables()->of($products)->addIndexColumn()->addColumn('no', function(Produk $produk) {
            return $produk->DT_RowIndex;
        })->editColumn('created_at', function(Produk $produk) {
            return convert_date($produk->created_at);
        })->editColumn('price', function(Produk $produk) {
            return intval($produk->price);
        })->make(true);
        return $datatables;
    }

    public function index(Request $request)
    {
        return Inertia::render('Product/Product', [
            'title'=>"Produk"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Product/Create', [
            'title'=>"Add Produk"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdukRequest $request)
    {
         if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('products', 'public');
        }

        Produk::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_path,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'qty' => $request->qty,
            'status' => $request->status
        ]);

        return to_route('produks.index');
        
        // if (!$product) {
        //     return redirect()->back()->with('error', 'Sorry, there a problem while creating product.');
        // }
        // return redirect()->route('products.index')->with('success', 'Success, you product have been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {   
        return Inertia::render('Product/Edit', [
            'title'=>"Edit Produk",
            'produk'=> $produk
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdukRequest $request, Produk $produk)
    {
        $produk->name = $request->name;
        $produk->description = $request->description;
        $produk->barcode = $request->barcode;
        $produk->price = $request->price;
        $produk->qty = $request->qty;
        $produk->status = $request->status;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($produk->image) {
                Storage::disk('public')->delete($produk->image);
            }
            // Store image
            $image_path = $request->file('image')->store('products', 'public');
            // Save to Database
            $produk->image = $image_path;
        }

        if (!$produk->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating produk.');
        }
        return to_route('produks.index')->with([
            'message' => 'Product updated successfully',
            'class' => 'alert alert-success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        if ($produk->image) {
            Storage::disk('public')->delete($produk->image);
        }

        $produk->delete();

        return to_route('produks.index')->with([
            'message' => 'Product deleted successfully',
            'class' => 'alert alert-success'
        ]);
    }
}
