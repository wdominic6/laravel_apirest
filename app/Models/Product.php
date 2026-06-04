<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_path',
        'latitude',
        'longitude',
        'user_id',
    ];

    /**
     * El usuario que creó este producto.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
