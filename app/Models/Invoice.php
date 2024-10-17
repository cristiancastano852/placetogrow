<?php

namespace App\Models;

use App\Constants\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'status',
        'document_number',
        'document_type',
        'name',
        'surname',
        'email',
        'mobile',
        'description',
        'currency',
        'amount',
        'expiration_date',
        'microsite_id',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'amount' => 'decimal:2',
        'status' => InvoiceStatus::class,
    ];

    public function microsite()
    {
        return $this->belongsTo(Microsites::class);
    }

    public function scopeInvoicesByRole($query, User $user): void
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
            $query->where('email', $user->email)
                ->with('microsite');
        }
    }
}
