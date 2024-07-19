<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'file_pdf',
        'quantity',
        'number_of_page',
        'additional_id',
        'price',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function additionalProduct() {
        return $this->belongsTo(Product::class, 'additional_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
