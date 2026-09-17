<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAwb extends Model
{
    protected $fillable = [
        'customer_name',
        'awb',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
