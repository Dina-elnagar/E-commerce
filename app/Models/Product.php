<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'average_rating',
        'default_variant',
        'is_in_stock',
        'options',
    ];


    public function options()
{
    return $this->belongsToMany(Option::class, 'product_option', 'product_id', 'option_id');
}
public function variants()
{
    return $this->hasMany(Variant::class );

}
}
