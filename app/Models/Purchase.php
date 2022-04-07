<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductInfo;

class Purchase extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(ProductMaster::class, 'product_master_id');
    }

    public function items()
    {
        return $this->hasMany(ProductInfo::class, 'product_info_id');
    }
}
