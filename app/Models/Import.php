<?php

namespace App\Models;

use App\Constants\ImportStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Import extends Model
{
    use HasFactory;

    public const DISK = 'imports';

    protected $fillable = [
        'path',
        'file_name',
        'status',
        'errors',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ImportStatus::class,
            'errors' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsites::class);
    }

    public function getFullPath(): string
    {
        return Storage::disk(self::DISK)->path($this->path);
    }
}
