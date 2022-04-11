<?php

namespace App\Http\Controllers;

use App\Models\AllotedProduct;
use App\Models\ProductInfo;
use App\Models\ProductMaster;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function edit(AllotedProduct $allot)
    {
        return view('allotments.index', [
            'allotments' => AllotedProduct::latest()->get(),
            'returnAllotment' => $allot,
            'items' => ProductInfo::latest()->get(),
            'users' => User::where('is_active', 1)->latest()->get()
        ]);
    }

    public function store(AllotedProduct $allot)
    {
        // $attributes = request()->validate([
        //     'product_info_id' => 'required|exist:product_info,id',
        //     'user_id' => 'required|exist:users,id',
        //     'alloted_date' => 'required'
        // ]);

        // $attributes['alloted_by'] = auth()->user()->id;

        // $product_info = ProductInfo::find(request()->product_info_id);
        // $product_info->update([
        //     'is_alloted' => 1
        // ]);

        // $product_master = ProductMaster::find()

        // AllotedProduct::create($attributes);
    }
}
