<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProductInfo;

class AllotedProduct extends Model
{
    protected $table = 'alloted_products';

    use HasFactory;

    protected $guarded = ['id'];

    public function items()
    {
        return $this->belongsTo(ProductInfo::class, 'product_info_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alloted()
    {
        return $this->belongsTo(User::class, 'alloted_by');
    }

    public function returned()
    {
        return $this->belongsTo(User::class, 'returned_to');
    }
}
