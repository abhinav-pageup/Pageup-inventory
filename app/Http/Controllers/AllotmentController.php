<?php

namespace App\Http\Controllers;

use App\Models\AllotedProduct;
use App\Models\ProductInfo;
use App\Models\ProductMaster;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllotmentController extends Controller
{
    public function index()
    {
        return view('allotments.index', [
            'allotments' => AllotedProduct::latest()->get(),
            'items' => ProductInfo::where('is_alloted', 0)->latest()->get(),
            'users' => User::where('is_active', 1)->latest()->get()
        ]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'product_info_id' => 'required',
            'user_id' => 'required',
            'alloted_date' => 'required'
        ]);

        $attributes['alloted_by'] = auth()->user()->id;
        $attributes['remark'] = request()->remark;

        try {
            DB::beginTransaction();
            $product_info = ProductInfo::find(request()->product_info_id);
            $product_info->update([
                'is_alloted' => 1
            ]);

            $product_master = $product_info->purchase->product;

            $product_master->increment('alloted', 1);

            AllotedProduct::create($attributes);

            DB::commit();
            return redirect(RouteServiceProvider::ALLOTMENTS)->with('success', 'Alloted Successful');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Some Internal Problem');
        }
    }

    public function edit(AllotedProduct $allot)
    {
        return view('allotments.index', [
            'allotments' => AllotedProduct::latest()->get(),
            'returnAllotment' => $allot,
            'items' => ProductInfo::latest()->get(),
            'users' => User::where('is_active', 1)->latest()->get()
        ]);
    }

    public function update(AllotedProduct $allot)
    {
        request()->validate([
            'return_date' => 'required',
            'is_damage' => 'required'
        ]);
        
        try {
            DB::beginTransaction();
            $product_info = $allot->items;
            $product_info->update([
                'is_alloted' => 0,
                'is_damage' => request()->is_damage
            ]);
    
            $product_master = $product_info->purchase->product;
            $product_master->decrement('alloted', 1);

            $allot->update([
                'return_date' => request()->return_date,
                'returned_to' => auth()->user()->id,
            ]);
    
            DB::commit();
            return redirect(RouteServiceProvider::ALLOTMENTS);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Some Internal Problem');
        }
    }
    
    public function show($user)
    {
        $user_products = AllotedProduct::where(['user_id' => $user, 'return_date' => null])->with('items.purchase.product')->get();
        return response()->json([
            'users' => $user_products,
        ]);
    }
}
