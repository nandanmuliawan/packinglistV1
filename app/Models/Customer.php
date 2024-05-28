<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name', 'address', 'contact_person', 'contact_number'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
