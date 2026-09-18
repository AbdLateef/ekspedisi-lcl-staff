<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAwb extends Model
{
    protected $fillable = [
        'customer_name',
        'no_container',
        'awb',
        'deskripsi',
        'jumlah_coli',
        'panjang',
        'lebar',
        'tinggi',
        'berat',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
