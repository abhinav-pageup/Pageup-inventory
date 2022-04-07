<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class ProductMaster extends Model
{
    protected $table = 'product_master';

    use HasFactory;

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_master_id');
    }
}
