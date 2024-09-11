<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'microsite_id',
        'plan_id',
        'reference',
        'description',
        'status',
        'status_message',
        'request_id',
        'name',
        'token',
        'subtoken',
        'price',
        'next_billing_date',
        'expiration_date',
        'billing_frequency',
        'payer',
    ];

    protected $casts = [
        'next_billing_date' => 'datetime',
        'expiration_date' => 'datetime',
        'payer' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsites::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
