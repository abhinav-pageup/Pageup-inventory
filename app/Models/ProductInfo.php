<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\AllotedProduct;

class ProductInfo extends Model
{
    protected $table = 'product_info';

    use HasFactory;

    protected $guarded = ['id'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    
    public function allotment()
    {
        return $this->hasMany(AllotedProduct::class, 'product_info_id');
    }
}
