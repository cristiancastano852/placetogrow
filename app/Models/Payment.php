<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $casts = [
        'expiration' => 'datetime',
        'fields_data' => AsArrayObject::class,
    ];

    protected $fillable = ['status', 'process_identifier'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsites::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function scopeTransactionsByRole($query, User $user): void
    {
        $userRole = $user->roles->first()->name;

        if ($userRole === 'Admin') {
            $query->with('microsite');
        }

        if ($userRole === 'Customer') {
            $query->with('microsite')
                ->whereHas('microsite', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }

        if ($userRole === 'Guests') {
            $query->where('user_id', $user->id)
                ->with('microsite');
        }
    }
}
