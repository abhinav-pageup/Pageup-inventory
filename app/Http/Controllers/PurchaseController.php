<?php

namespace App\Http\Controllers;

use App\Models\ProductMaster;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('purchases.index', [
            'purchases' => Purchase::where('is_active', '=', 1)->latest()->get()
        ]);
    }

    public function create()
    {
        return view('purchases.create', [
            'products' => ProductMaster::where('is_active', '=', 1)->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        // $attributes = [
        //     'product_master_id' => 'required',
        //     'bill_no' => 'required|min:2',
        //     'company' => 'min:2',
        //     'quantity' => 'required',
        //     'cost' => 'required',
        //     'date' => 'required'
        // ];

        // for ($i=0; $i < $request->quantity; $i++) { 
        //     $attributes['unique'.$i+1] = 'required|min:2';
        // };

        // $this->validate($request, $attributes);

        $attributes = request()->validate([
            'product_master_id' => 'required',
            'bill_no' => 'required|min:2',
            'company' => 'min:2',
            'quantity' => 'required',
            'cost' => 'required',
            'date' => 'required',
        ]);

        for ($i=0; $i < request()->quantity; $i++) { 
            $this->validate($request, [$request['unique'.$i+1] => 'required']);
            $attributes['unique'.$i+1] = $request['unique'.$i+1];
        };

        dd($attributes);
    }
}
