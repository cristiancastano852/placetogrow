<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Microsites extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'document_type',
        'document_number',
        'logo',
        'category_id',
        'currency',
        'site_type',
        'payment_expiration',
        'payment_fields',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'payment_fields' => AsArrayObject::class,
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
