<?php

namespace App\Actions\Microsites;

use App\Models\Microsites;

class DeleteAction
{
    public function execute(Microsites $microsites): void
    {
        $microsites->delete();
    }
}
