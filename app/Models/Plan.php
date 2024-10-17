<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'microsite_id',
        'name',
        'price',
        'description',
        'duration_unit',
        'billing_frequency',
        'duration_period',
    ];

    public function microsite()
    {
        return $this->belongsTo(Microsites::class);
    }
}
