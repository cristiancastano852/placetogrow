<?php

namespace App\Actions\Microsites;

use App\Models\Microsites;
use Illuminate\Support\Facades\Log;

class DeleteAction
{
    public function execute(Microsites $microsites): void
    {
        $microsites->delete();
    }
}
