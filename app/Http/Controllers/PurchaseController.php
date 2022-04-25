<?php

namespace App\Http\Controllers;

use App\Models\ProductInfo;
use App\Models\ProductMaster;
use App\Models\Purchase;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        request()->validate([
            'product_master_id' => 'required',
            'bill_no' => 'required|min:5',
            'quantity' => 'required',
            'cost' => 'required',
            'date' => 'required',
            'unique.*' => 'required|distinct|min:2|unique:product_info,ref_no'
        ]);

        try {
            DB::beginTransaction();

            $purchase = Purchase::create([
                'product_master_id' => request()->product_master_id,
                'bill_no' => request()->bill_no,
                'company' => request()->company,
                'quantity' => request()->quantity,
                'cost' => request()->cost,
                'date' => request()->date,
                'remark' => request()->remark,
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

            DB::commit();
    
            return redirect(RouteServiceProvider::PURCHASES)->with('success', 'Purchase Successful');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Some Internal Problem');
        }
    }

    public function destroy(Purchase $purchase)
    {
        $product_master = ProductMaster::find($purchase->product_master_id);
        $product_master->decrement('stock', $purchase->quantity);
        $product_master->save();

        $purchase->delete();

        return redirect(RouteServiceProvider::PURCHASES)->with('success', 'Deleted Successful');
    }

    public function edit(Purchase $purchase)
    {
        return view('purchases.edit', [
            'products' => ProductMaster::where('is_active', 1)->latest()->get(),
            'purchase' => $purchase,
            'productInfo' => ProductInfo::where('purchase_id', $purchase->id)->get()
        ]);
    }

    public function update(Purchase $purchase, Request $request)
    {
        request()->validate([
            'product_master_id' => 'required',
            'bill_no' => 'required|min:5',
            'company' => '',
            'quantity' => 'required',
            'cost' => 'required',
            'date' => 'required',
            'unique.*' => ['required', 'distinct', 'min:2', Rule::unique('product_info', 'ref_no')->ignore($purchase->items->first()->purchase_id, 'purchase_id')]
        ]);

        try {
            DB::beginTransaction();

            ProductInfo::where('purchase_id', $purchase->id)->delete();

            $index = 0;
            foreach ($request['unique'] as $unique) {
                if (isset($request['damage']['unique' . $index])) {
                    ProductInfo::create([
                        'purchase_id' => $purchase->id,
                        'ref_no' => $unique,
                        'is_damage' => 1
                    ]);
                } else {
                    ProductInfo::create([
                        'purchase_id' => $purchase->id,
                        'ref_no' => $unique
                    ]);
                }
                $index++;
            }

            $product_master = $purchase->product;
            $product_master->decrement('stock', $purchase->quantity);
            $product_master->increment('stock', request()->quantity);

            $purchase->update([
                'product_master_id' => request()->product_master_id,
                'bill_no' => request()->bill_no,
                'company' => request()->company,
                'quantity' => request()->quantity,
                'cost' => request()->cost,
                'date' => request()->date,
                'remark' => request()->remark,
                'updated_by' => auth()->user()->id
            ]);
            DB::commit();

            return redirect(RouteServiceProvider::PURCHASES)->with('success', "Purchase Update Successful");
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Some Internal Problem');
        }
    }
}
