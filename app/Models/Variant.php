<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'option1',
        'option2',
        'price',
        'stock',
        'is_in_stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'default_variant');
    }

}
