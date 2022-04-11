<?php

namespace App\Http\Controllers;

use App\Models\ProductInfo;
use Illuminate\Http\Request;

class ProductInfoController extends Controller
{
    public function index()
    {
        return view('product_info.index', [
            'products' => ProductInfo::where('is_active', 1)->latest()->get()
        ]);
    }
}
