<?php

namespace App\Http\Controllers;

use App\Models\ProductInfo;
use App\Models\ProductMaster;
use App\Models\Purchase;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('purchases.index', [
            'purchases' => Purchase::where('is_active', 1)->latest()->get(),
            'allot' => ProductInfo::where('is_alloted', 1)->get()
        ]);
    }

    public function create()
    {
        return view('purchases.create', [
            'products' => ProductMaster::where('is_active', 1)->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $attributes = request()->validate([
            'product_master_id' => 'required',
            'bill_no' => 'required|min:5',
            'company' => '',
            'quantity' => 'required',
            'cost' => 'required',
            'date' => 'required',
            'unique.*' => 'required|distinct|min:2|unique:product_info,ref_no'
        ]);

        $purchase = Purchase::create([
            'product_master_id' => request()->product_master_id,
            'bill_no' => request()->bill_no,
            'company' => request()->company,
            'quantity' => request()->quantity,
            'cost' => request()->cost,
            'date' => request()->date,
            'created_by' => auth()->user()->id
        ]);

        foreach ($request['unique'] as $product) {
            ProductInfo::create([
                'purchase_id' => $purchase->id,
                'ref_no' => $product
            ]);
        };

        $product_master = ProductMaster::find(request()->product_master_id);
        $product_master->increment('stock', request()->quantity);
        $product_master->save();

        return redirect(RouteServiceProvider::PURCHASES)->with('success', 'Added Purchase Successful');
    }

    public function destroy(Purchase $purchase)
    {
        $product_master = ProductMaster::find($purchase->product_master_id);
        $product_master->decrement('stock', $purchase->quantity);
        $product_master->save();

        $purchase->delete();

        return redirect(RouteServiceProvider::PURCHASES)->with('success', 'Deleted Successful');
    }
}
