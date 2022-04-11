<?php

namespace App\Http\Controllers;

use App\Models\ProductMaster;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductMasterController extends Controller
{
    public function index()
    {
        return view('product_master.index', [
            'products' => ProductMaster::where('is_active', '=', 1)->latest()->get()
        ]);
        // dd(ProductMaster::where('is_active', '=', 1)->latest()->get());
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => 'required|min:2|unique:product_master,name',
            'type' => 'required|in:Household,Electronic'
        ]);

        $attributes['created_by'] = auth()->user()->id;

        ProductMaster::create($attributes);

        return redirect(RouteServiceProvider::PRODUCTS)->with('success', 'Product Added');
    }

    public function edit(ProductMaster $product)
    {
        return view('product_master.index', [
            'products' => ProductMaster::where('is_active', '=', 1)->latest()->get(),
            'editProduct' => $product
        ]);
    }

    public function update(ProductMaster $product)
    {
        $attributes = request()->validate([
            'name' => 'required|min:2|unique:product_master,name,'.$product->id.'',
            'type' => 'required|in:Household,Electronic'
        ]);

        $attributes['updated_by'] = auth()->user()->id;
        
        $product->update($attributes);

        return redirect(RouteServiceProvider::PRODUCTS)->with('success', 'Update Successful');
    }
}
