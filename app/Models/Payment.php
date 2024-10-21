<?php

namespace App\Models;

use App\Constants\Roles;
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

    public function scopeTransactionsByRole($query, User $user, ?int $micrositeId = null): void
    {
        $userRole = $user->roles->first()->name;
        $query->with('microsite')
            ->when($userRole === Roles::CUSTOMER->value,
                fn ($query) => $query
                    ->whereHas('microsite', fn ($query) => $query->where('user_id', $user->id)
                    )
            )->when($userRole === Roles::GUEST->value, fn ($query) => $query->where('user_id', $user->id))
            ->when($micrositeId, fn ($query) => $query->where('microsite_id', $micrositeId))
            ->orderBy('created_at', 'desc');
    }
}
