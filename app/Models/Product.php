<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product_name', 'weight_per_unit', 'volume_per_unit', 'dimension', 'items_per_carton'];

    public function cartons()
    {
        return $this->hasMany(Carton::class);
    }

    // Fungsi untuk mendapatkan volume per karton
    public function getVolumePerCartonAttribute()
    {
        return $this->volume_per_unit * $this->items_per_carton;
    }

    // Fungsi untuk mendapatkan berat per karton
    public function getWeightPerCartonAttribute()
    {
        return $this->weight_per_unit * $this->items_per_carton;
    }
}
